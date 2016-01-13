<?php

namespace App\Services;

use App\Models\Configuration;
use App\Models\Tasks;
use App\Repository\TaskRepository;
use App\Repository\ConfigurationRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Log;

Class TaskProcessor
{

    const URL_FORM = 'https://docs.google.com/a/mobly.com.br/forms/d/11CFIHRL33Pw-vMLbPubP5NUZe_eGhI-equ-EhB9HjIg/viewform';
    const URL_LOGIN = 'https://accounts.google.com/ServiceLogin';
    const URL_LOGIN_AUTH = 'https://accounts.google.com/ServiceLoginAuth';

    const ERROR_PASSWORD_CONFIGURATION = 'You did not fill the password in the system settings.
                        The password is required for authentication on Google, please enter your password:';

    /**
     * @var array
     */
    protected static $messagesLoginSuccessful = [
        'Account Overview',
        'Visão geral da conta'
    ];

    /**
     * @var array
     */
    protected static $defaultOptions = [
        'verify' => false,
        'headers' => [
            'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)"
        ]
    ];

    protected static $defaultRedirectOptions = [
        'max'             => 15,
        'strict'          => false,
        'referer'         => true,
        'protocols'       => ['http', 'https'],
        'track_redirects' => false
    ];

    /**
     * @var array
     */
    protected $hiddenValues = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $password;


    /**
     * Teste constructor.
     */
    public function __construct($password = null)
    {
        libxml_use_internal_errors(true);

        $configurationRepository = new ConfigurationRepository();
        $this->configuration = $configurationRepository->findFirst();
        $this->password = $password;
        $this->checkConfiguration();

        $this->taskRepository = new TaskRepository();

        $jar = new \GuzzleHttp\Cookie\CookieJar;
        $options = array_merge(['cookies' => $jar], self::$defaultOptions);
        $this->client = new Client($options);
    }

    public function checkConfiguration()
    {
        $password = $this->password ? $this->password : $this->configuration->password;
        if (!strlen($password)) {
            throw new \Exception(self::ERROR_PASSWORD_CONFIGURATION);
        }

        return true;
    }

    public function process()
    {

        Log::info('Start process');

        $this->login();

        $tasks = $this->taskRepository->getPending();
        foreach ($tasks as $task) {
            $this->processTask($task);
        }

        return true;
    }


    private function processTask(Tasks $task)
    {
        Log::info("Start task [{$task->id}]");

        $urlGet = $task->link ? $task->link : self::URL_FORM;
        $urlPost = str_replace('viewform', 'formResponse', $urlGet);

        $data = [
            'entry.116160053' => $task->task,
            'entry.789822953' => $task->date,
            'entry.368040154' => $task->time,
            'entry_1252005894' => $task->description,
            'emailReceipt' => $this->configuration->send_email_process ? true : false
        ];

        $responseGetForm = $this->client->get($urlGet);

        $this->updateHiddenValues($responseGetForm->getBody()->getContents());
        $allData = $this->mergeData($data);

        //request options
        $options = self::$defaultOptions;
        $options['form_params'] = $allData;
        $options['allow_redirects'] = self::$defaultRedirectOptions;;

        $responsePostForm = $this->client->post($urlPost, $options)->getBody()->getContents();
        $link = $this->getLinkFromResponseForm($responsePostForm);

        if (!$link) {
            $updateData = [
                'status' => Tasks::STATUS_ERROR,
                'error_message' => 'Cant save form',
            ];
            $this->updateTask($task, $updateData);
            Log::error("Link not found to task [{$task->id}]");
            return true;
        }

        $updateData = [
            'status' => Tasks::STATUS_PROCESSED,
            'sent_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'link' => $link
        ];

        $this->updateTask($task, $updateData);
        Log::info("Task [{$task->id}] finish successfully");
        return true;
    }

    /**
     * @param $responsePostForm
     * @return bool
     */
    protected function getLinkFromResponseForm($responsePostForm)
    {

        $html = new \DOMDocument();
        $html->strictErrorChecking = false;
        $html->loadHtml($responsePostForm);

        $xpath = new \DOMXpath($html);

        $results = $xpath->query("//*[@class='ss-bottom-link']");

        foreach ($results as $nodeLink) {
            foreach ($nodeLink->attributes as $attribute) {
                if ($attribute->name == 'href') {
                    return $nodeLink->getAttribute('href');
                }
            }
        }

        return false;
    }

    private function updateTask(Tasks $task, $data)
    {
        $this->taskRepository->update($data, $task->id);
        return true;
    }

    private function updateHiddenValues($page)
    {
        $html = new \DOMDocument();
        $html->loadHtml($page);

        $nodes = $html->getElementsByTagName('input');

        foreach($nodes as $node) {
            if ($node->hasAttributes()) {
                foreach ($node->attributes AS $attribute) {
                    if ($attribute->nodeName == 'type' && $attribute->nodeValue == 'hidden') {
                        $this->hiddenValues[$node->getAttribute('name')] = $node->getAttribute('value');
                    }
                }
            }
        }
    }

    private function login()
    {
        Log::info('Start login');

        // get login page and update hidden values
        $response = $this->client->get(self::URL_LOGIN);
        $this->updateHiddenValues($response->getBody()->getContents());

        // authenticate
        $resultAuthenticate = $this->authenticate();
        if (!$this->checkAuthentication($resultAuthenticate)) {
            Log::error('Authentication failed, check your credentials.');
            throw new \Exception('Authentication failed, check your credentials.');
        }

        Log::info('Logged in successfully');

        return true;
    }

    /**
     * @return string
     */
    private function authenticate()
    {
        Log::info('Start authentication..');
        // post login
        $data = [
            'Email' => $this->configuration->email,
            'Passwd' => $this->password ? $this->password : Crypt::decrypt($this->configuration->password),
        ];

        $allData = $this->mergeData($data);
        unset($allData['PersistentCookie']);

        //request options
        $options = self::$defaultOptions;
        $options['form_params'] = $allData;
        $options['allow_redirects'] = self::$defaultRedirectOptions;

        return $this->client->post(self::URL_LOGIN_AUTH, $options)->getBody()->getContents();
    }

    /**
     * @param $page
     * @return bool
     */
    private function checkAuthentication($page) {
        $html = new \DOMDocument();
        $html->strictErrorChecking = false;
        $html->loadHtml($page);

        $title = $html->getElementsByTagName('title')->item(0)->nodeValue;
        if ( in_array($title, self::$messagesLoginSuccessful)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @param bool|false $force
     * @return array
     */
    private function mergeData(array $data, $force = false)
    {
        foreach ( $this->hiddenValues AS $i => $v) {
            if ( array_key_exists($i, $data) && $force ) {
                continue;
            }
            $data[$i] = $v;
        }

        return $data;
    }
}