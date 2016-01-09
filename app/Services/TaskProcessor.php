<?php

namespace App\Services;

use App\Models\Tasks;
use App\Repository\TaskRepository;
use GuzzleHttp\Client;

Class Teste {
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
     * Teste constructor.
     */
    public function __construct()
    {
        libxml_use_internal_errors(true);

        $this->client = new Client(self::$defaultOptions);
        $this->taskRepository = new TaskRepository();
    }

    public function process(Tasks $tasks)
    {
        $this->login();

        foreach ($tasks as $task) {
            $responseForm = $this->client->get(self::URL_FORM);
            $page = $responseForm->getBody()->getContents();

            $html = new \DOMDocument();
            $html->strictErrorChecking = false;
            $html->loadHtml($page);

            die('pk');
        }
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
            'Email' => 'rodrigo.pereira@mobly.com.br',
            'Passwd' => 'xxxx',
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