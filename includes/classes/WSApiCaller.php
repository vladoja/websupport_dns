<?php

class WSApiCaller
{

    private $apiKey = API_KEY;
    private $secret = API_SECRET;
    private $api = API_WEBSUPPORT;

    public function __construct()
    {
    }


    public function call($path, $method = 'GET', $data = null)
    {
        if ($data) {
            error_log('REQUEST JSON: ' . $data);
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

        return $this->handle_response($http_status, $response, $method, $error_msg);
    }

    public function set_apiKey(string $strApiKey)
    {
        if ($strApiKey && is_string($strApiKey)) {
            $this->apiKey = $strApiKey;
        }
    }

    public function set_secret(string $strSecret)
    {
        if ($strSecret && is_string($strSecret)) {
            $this->secret = $strSecret;
        }
    }

    public function set_api(string $strApiKey)
    {
        if ($strApiKey && is_string($strApiKey)) {
            $this->api = $strApiKey;
        }
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

    private function handle_response($http_status, $response_json, $request_method, $error_msg = null)
    {
        error_log("RESPONSE: CODE: $http_status  MSG: " .  $response_json);
        if ($http_status === 200 && $request_method === 'GET') {
            $response = json_decode($response_json, true);
            $response['status'] = 'success';
            return json_encode($response);
        }
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
