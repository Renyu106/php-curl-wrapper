<?php

class CURL {
    private $url;
    private $method;
    private $HEDAERS;
    private $postData;
    private $PROXY;
    private $REDIRECT_ALLOW = false;
    private $RANDOM_USER_AGENT = false;

    private $PROXYS = array(
        "127.0.0.1:1234", // 이용중인 프록시
    );

    private function RETURN($STATUS, $MSG = null, $STATUS_CODE = null, $BODY = null, $HEADER = null) {
        $RETURN = ["STATUS" => $STATUS === "OK" ? "OK" : "ERR"];
        if ($STATUS_CODE) $RETURN["CODE"] = $STATUS_CODE;
        if ($MSG) $RETURN["MSG"] = $MSG;
        if ($BODY) $RETURN["BODY"] = $BODY;
        if ($HEADER) $RETURN["HEADER"] = $HEADER;
        return $RETURN;
    }

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

    public function RANDOM_USER_AGENT(){
        $this->RANDOM_USER_AGENT = true;
    }

    public function SEND() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        if($this->RANDOM_USER_AGENT){
            // 이부분은 알아서 하세요
            // $USER_AGENT = file(PROJECT_LOCATION."/LIB/AUTOLOAD_GLOBAL/FRAMEWORK/USER-AGENT.txt");
            // $this->HEDAERS[] = "User-Agent: ". $USER_AGENT[rand(0, count($USER_AGENT) - 1)];
         }else{
            $this->HEDAERS[] = 'User-Agent: PHP-CURL-WRAPPER';
        }

        if ($this->HEDAERS) {
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

        if($httpCode == 0){
            return self::RETURN("ERR", curl_error($ch), curl_errno($ch));
        }else{

            $headers = explode("\n", $header);
            $headerArray = [];
            foreach ($headers as $header) {
                $parts = explode(': ', $header, 2);
                if (count($parts) == 2) {
                    $headerArray[trim($parts[0])] = trim($parts[1]);
                }
            }

            return self::RETURN($status, "Fetch Success", $httpCode, $body, $headerArray);
        }
    }
}
