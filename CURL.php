<?php

class CURL
{
    private $url;
    private $method;
    private $HEDAERS;
    private $postData;
    private $PROXY;

    private $PROXYS = array(
        "localhost:12345"
    );

    public function __construct($url, $method = 'GET', $HEDAERS = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->HEDAERS = $HEDAERS;
    }

    public function DATA($data)
    {
        $this->postData = $data;
    }

    public function PROXY($PROXY)
    {
        $this->PROXY = $PROXY;
    }

    public function RANDOM_PROXY()
    {
        $this->PROXY = $this->PROXYS[array_rand($this->PROXYS)];
    }

    public function HAEDER($HEDAERS)
    {
        $this->HEDAERS = $HEDAERS;
    }

    public function SEND()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($this->HEDAERS) {
            $this->HEDAERS[] = 'User-Agent: WinSub-CURL';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->HEDAERS);
        }

        if ($this->method === 'POST' && $this->postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
        }

        if ($this->PROXY) {
            curl_setopt($ch, CURLOPT_PROXY, $this->PROXY);
        }

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'HEADER' => $header,
            'BODY' => $body,
            'CODE' => $httpCode
        ];
    }
}
