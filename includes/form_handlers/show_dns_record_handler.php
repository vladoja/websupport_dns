<?php
require './includes/classes/WSApiCaller.php';

function make_api_call($id)
{
    $method = 'GET';
    $path = '/v1/user/self/zone/' . DOMAIN_NAME . '/record/' . $id;
    $caller = new WSApiCaller();


    $response = $caller->call($path, $method);
    // $response = ' {"message":"Record not found","code":404,"status":"error","error_msg":"Record not found"}';
    // $response = '{"type":"MX","prio":100,"id":18715170,"name":"@","content":"mailin1.php-assignment-9.ws","ttl":600,"note":"","zone":{"name":"php-assignment-9.ws","service_id":84825,"updateTime":1599048880},"status":"success"}';

    if ($response) {
        // error_log('RESPONSE json ' . $response);
        $response = json_decode($response, true);
        // error_log('RESPONSE: ' . print_r($response, true));
    } else {
        return false;
    }

    if (!isset($response['status'])) {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'API Call failed !');
        return false;
    }

    if ($response['status'] === 'error') {
        if (isset($response['error_msg'])) {
            $error_msg  = $response['error_msg'];
        } else {
            $error_msg = 'API Call failed!';
        }
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, $error_msg);
        return false;
    }

    if ($response['status'] !== 'success') {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, "Something went wrong!");
        return false;
    }

    return $response;
}
