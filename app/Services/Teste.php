<?php

namespace App\Services;


use GuzzleHttp\Client;

Class Teste {
    const URL_FORM = 'https://docs.google.com/a/mobly.com.br/forms/d/11CFIHRL33Pw-vMLbPubP5NUZe_eGhI-equ-EhB9HjIg/viewform';
    const URL_LOGIN = 'https://accounts.google.com/ServiceLogin';
    const URL_LOGIN_AUTH = 'https://accounts.google.com/ServiceLoginAuth';

    protected $hiddenValues = [];

    /**
     * @var Client
     */
    protected $client;


    protected $_username = 'rodrigo.pereira@mobly.com.br';
    protected $_password = 'ap167dc1';


    /**
     * Teste constructor.
     */
    public function __construct()
    {
        libxml_use_internal_errors(true);
        $this->client = new Client(['cookies' => true]);

    }


    private function updateHiddenValues($page)
    {
        $html = new \DOMDocument();
        $html->loadHtml($page);

        $nodes = $html->getElementsByTagName('input');

        foreach($nodes as $node) {
            if ( $node->hasAttributes() ) {
                foreach ( $node->attributes AS $attribute ) {
                    if ( $attribute->nodeName == 'type' && $attribute->nodeValue == 'hidden' ) {
                        $this->hiddenValues[$node->getAttribute('name')] = $node->getAttribute('value');
                    }
                }
            }
        }
    }

    public function login()
    {

        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $options['verify'] = false;
        $options['headers'] = [
            'User-Agent' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",
        ];

        // get login page and update hidden values
        $response = $this->client->get(self::URL_LOGIN, $options);
        $this->updateHiddenValues($response->getBody()->getContents());


        // post login
        $data = [
            'Email' => 'rodrigo.pereira@mobly.com.br',
            'Passwd' => 'xxxx',
        ];

        $allData = $this->mergeData($data);
        unset($allData['PersistentCookie']);

        $options['form_params'] = $allData;
        $options['allow_redirects'] = [
            'max'             => 15,
            'strict'          => false,
            'referer'         => true,
            'protocols'       => ['http', 'https'],
            'track_redirects' => false
        ];

        $resultLogin = $this->client->post(self::URL_LOGIN_AUTH, $options)->getBody()->getContents();
        if (!$this->checkAuthentication($resultLogin)) {
            die('erro login');
        }

        $responseForm = $this->client->get(self::URL_FORM);
        $page = $responseForm->getBody()->getContents();

        $html = new \DOMDocument();
        $html->strictErrorChecking = false;
        $html->loadHtml($page);

        echo $page;
        die('fom');


    }

    private function checkAuthentication($page) {
        $html = new \DOMDocument();
        $html->strictErrorChecking = false;
        $html->loadHtml($page);

        $r = $html->getElementsByTagName('title')->item(0)->nodeValue;

        if ( $r == 'Account Overview' || $r == 'Visão geral da conta') {
            return true;
        }

        return false;
    }

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