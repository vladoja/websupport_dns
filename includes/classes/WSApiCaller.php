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
        if ($data) {
            error_log('REQUEST JSON: '. $data);
        }
        
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
        // curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $header = ['Date: ' . gmdate('Ymd\THis\Z', $time)];
        $header[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        return $ch;
    }

    private function handle_response($http_status, $response_json, $error_msg = null)
    {
        error_log("RESPONSE: CODE: $http_status  MSG: " .  $response_json);
        if (in_array($http_status, [200, 201, 204, 400, 401, 403, 404,])) {
            $response = json_decode($response_json, true);
            $first_error_msg = $this->get_first_error_msg_from_response($response);
            if ($first_error_msg || ($http_status >= 400 && $http_status <= 404)) {
                // Odpoved obsahuje chyby
                $response['status'] = 'error';
                $response['error_msg'] = $first_error_msg;
            }
            if (in_array($http_status, [200, 201, 204]) && isset($response['status']) === false) {
                $response['status'] = 'success';
            }
            return json_encode($response);
        } else if (isset($error_msg)) {
            $response['status'] = 'error';
            $new_response['error_msg'] = $error_msg;
            error_log('API call failed. Msg: ' . $error_msg);
            return json_encode($response);
        } else {
            // TODO: Dat genericku hlasku
            error_log('handle_response failed on unkown error. HTTP STATUS: ' . $http_status . '. Orginal response: ' . $response_json);
            $response = array();
            $response['status'] = 'error';
            $new_response['error_msg'] = "API call failed. Unknown error.";
            return json_encode($response);
        }
    }


    public function get_error_msg_from_response(array $response)
    {
        if (isset($response['errors']) && is_array($response['errors']) && count($response['errors']) > 0) {
            return $response['errors'];
        } else {
            return null;
        }
    }


    public function get_first_error_msg_from_response(array $response)
    {
        if (isset($response['errors']) && is_array($response['errors']) && count($response['errors']) > 0) {
            $key = null;
            $value = null;
            foreach (array_slice($response['errors'], 0, 1, true) as $key => $value);
            $value = (is_array($value) && count($value) > 0) ? $value[0] : $value;
            return sprintf('%s For parameter %s', $value, $key);
        } else if (isset($response['message'])) {
            if (is_array($response['message']) && count($response['message']) > 0) {
                return $response['message'][0];
            } else {
                return $response['message'];
            }
        } {
            return null;
        }
    }
}
