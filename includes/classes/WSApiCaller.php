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

        $error_msg = null;
        $ch = curl_init();
        $ch = $this->setCurlOptions($ch, $path, $method, $data);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            // echo PHP_EOL, 'Curl failed: ', $error_msg, PHP_EOL;
        }
        // echo PHP_EOL, 'HTTP response status: ', $http_status;
        curl_close($ch);

        return $this->handle_response($http_status, $response, $error_msg);
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
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
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

    private function handle_response($http_status, $response_json, $error_msg = null)
    {
        if ($http_status === 200 || $http_status === 201 || $http_status === 204) {
            $response = json_decode($response_json, true);
            if (!isset($response['status'])) {
                $response['status'] = 'success';
            }
            return json_encode($response);
        } else if ($response_json) {
            $response = json_decode($response_json, true);
            if (!isset($response['status'])) {
                $response['status'] = 'error';
                if (isset($response['message'])) {
                    $response['errors'] = array('content' => array($response['message']));
                }                   
                return json_encode($response);
            }
            return $response_json;
        } else if (isset($error_msg)) {
            $response['status'] = 'error';
            // $new_response['errors'] = array('content' => array($error_msg));
            // TODO: Dat genericku hlasku
            $new_response['errors'] = array('content' => array($error_msg));
            error_log('CURL call failed. Msg: '.$error_msg );
            return json_encode($response);
        }
        return null;
    }
}
