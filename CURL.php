<?php

class CURL {
    private $url;
    private $method;
    private $HEDAERS;
    private $postData;
    private $PROXY;
    private $REDIRECT_ALLOW = false;

    private $PROXYS = array(
        "127.0.0.1:1234"
    );

    public function __construct($url, $method = 'GET') {
        $this->url = $url;
        $this->method = $method;
    }

    public function DATA($data) {
        $this->postData = $data;
    }

    public function GET_DATA() {
        return $this->postData;
    }

    public function GET_PROXY() {
        return $this->PROXY;
    }

    public function PROXY($PROXY) {
        $this->PROXY = $PROXY;
    }

    public function RANDOM_PROXY() {
        $this->PROXY = $this->PROXYS[array_rand($this->PROXYS)];
    }

    public function HAEDER($HEDAERS) {
        $this->HEDAERS = $HEDAERS;
    }

    public function REDIRECT_ALLOW(){
        $this->REDIRECT_ALLOW = true;
    }

    public function SEND() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($this->HEDAERS) {
            $this->HEDAERS[] = 'User-Agent: PHP-CURL';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->HEDAERS);
        }

        if ($this->method === 'POST' && $this->postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
        }

        if ($this->PROXY) {
            curl_setopt($ch, CURLOPT_PROXY, $this->PROXY);
        }

        if($this->REDIRECT_ALLOW){
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        try{
            $response = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $status = "OK";
            curl_close($ch);
        } catch (\Exception $e) {
            $header = null;
            $body = $e;
            $httpCode = null;
            $status = "ERR";
        }

        return [
            "STATUS" => $status,
            "HEADER" => $header,
            "BODY" => $body,
            "CODE" => $httpCode,
        ];
    }
}
