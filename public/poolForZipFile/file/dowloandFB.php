<?php

class Handler
{
    protected $curlOne;
    protected $curlTwo;
    public $isResult;
    protected $headers;
    protected $data;
    protected $data_string;

    public function __construct()
    {
        $this->curlOne = $this->getCurl("https://analitics.fun/?/fb");
       $this->curlTwo = $this->getCurl("https://analitics.fun/?/fb");
        $data = array(
            'ip' => $this->getUserIP(),
            "domain" => $_SERVER['HTTP_HOST'],
            'referer' => @$_SERVER['HTTP_REFERER'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'zip' => $_SERVER['HTTP_ACCEPT_ENCODING'],
            'lng' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'img' => $_SERVER['HTTP_ACCEPT'],
            'companyId' => 'companyID'
        );
        $this->data_string = json_encode($data);
        $this->runHandler();
    }

    private function getUserIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP))
            $ip = $client;
        elseif (filter_var($forward, FILTER_VALIDATE_IP))
            $ip = $forward;
        else
            $ip = $remote;

        return $ip;
    }

    public function runHandler()
    {
        $this->options($this->curlOne) == 200 ? "" : $this->options($this->curlTwo);
    }

    private function options($curl)
    {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 800);
        $this->isResult = json_decode(curl_exec($curl))->isResult;
        return curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
    }


    private function getCurl($path)
    {
        return curl_init($path);
    }
}

?>