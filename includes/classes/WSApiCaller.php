<?php

class WSApiCaller
{

    private $apiKey = '3ce3178d-25c4-4ed3-8c64-f5142de20efe';
    private $secret = 'c48e8c9dbd7e72ff7854433e2ee97927c919568b';
    private $api = 'https://rest.websupport.sk';

    public function __construct()
    {
    }


    public function call($path, $method = 'GET', $data = null)
    {

        $ch = curl_init();
        $ch = $this->setCurlOptions($ch, $path, $method, $data);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    private function setCurlOptions($ch, $path, $method, $data_json = null)
    {
        $time = time();
        $canonicalRequest = sprintf('%s %s %s', $method, $path, $time);
        $signature = hash_hmac('sha1', $canonicalRequest, $this->secret);

        $ch = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($data_json)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data_json)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
                break;
        }

        curl_setopt($ch, CURLOPT_URL, sprintf('%s:%s', $this->api, $path));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ':' . $signature);

        $header = ['Date: ' . gmdate('Ymd\THis\Z', $time)];
        $header[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        return $ch;
    }
}
