<?php

namespace App\Services;

use App\Models\Tasks;
use App\Models\Configuration;
use App\Repository\TaskRepository;
use App\Repository\ConfigurationRepository;
use GuzzleHttp\Client;

Class TaskProcessor {
    const URL_FORM = 'https://docs.google.com/a/mobly.com.br/forms/d/11CFIHRL33Pw-vMLbPubP5NUZe_eGhI-equ-EhB9HjIg/viewform';
    const URL_LOGIN = 'https://accounts.google.com/ServiceLogin';
    const URL_LOGIN_AUTH = 'https://accounts.google.com/ServiceLoginAuth';

    protected static $messagesLoginSuccessful = [
        'Account Overview',
        'Visão geral da conta'
    ];

    protected static $defaultOptions = [
        'verify' => false,
        'cookies' => true,
        'headers' => [
            'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)"
        ]
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


    protected $password;

    /**
     * Teste constructor.
     */
    public function __construct($password = null)
    {
        libxml_use_internal_errors(true);

        $this->taskRepository = new TaskRepository();
        $configurationRepository = new ConfigurationRepository();

        $this->configuration = $configurationRepository->findFirst();
        $this->client = new Client(self::$defaultOptions);
        $this->password = $password;
        $this->checkConfiguration();
    }

    public function checkConfiguration()
    {
        $password = $this->password ? $this->password : $this->configuration->password;
        if (! strlen($password) || ! strlen($this->configuration->email)) {
            return false;
        }

        return true;
    }

    public function process()
    {
        //$this->login();

        $tasks = $this->taskRepository->getPending();
        foreach ($tasks as $task) {
            echo $task->id . '<br>';
            // $responseForm = $this->client->get(self::URL_FORM);
            // $page = $responseForm->getBody()->getContents();

            // $html = new \DOMDocument();
            // $html->strictErrorChecking = false;
            // $html->loadHtml($page);    
        }
        die('pk');
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
        // get login page and update hidden values
        $response = $this->client->get(self::URL_LOGIN);
        $this->updateHiddenValues($response->getBody()->getContents());

        // authenticate
        $resultAuthenticate = $this->authenticate();
        if (!$this->checkAuthentication($resultAuthenticate)) {
            throw new \Exception('Authentication failed');
        }

        return true;
    }

    /**
     * @return string
     */
    private function authenticate()
    {
        // post login
        $data = [
            'Email' => $configuration->email,
            'Passwd' => $this->password ? $this->password : $configuration->password,
        ];

        $allData = $this->mergeData($data);
        unset($allData['PersistentCookie']);

        //request options
        $options = self::$defaultOptions;
        $options['form_params'] = $allData;
        $options['allow_redirects'] = [
            'max'             => 15,
            'strict'          => false,
            'referer'         => true,
            'protocols'       => ['http', 'https'],
            'track_redirects' => false
        ];

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