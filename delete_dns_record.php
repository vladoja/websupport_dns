<?php
require './includes/handlers/global.php';
require './includes/classes/WSApiCaller.php';


function call_api_delete_record($id)
{
    $method = 'DELETE';
    $path = '/v1/user/self/zone/'.DOMAIN_NAME.'/record/' . $id;;

    $caller = new WSApiCaller();

    $response_json = $caller->call($path, $method);


    if (!$response_json) {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'API Call failed!');
        return false;
    } else {
        $response = json_decode($response_json, true);
        return handle_error_messages_from_response($response, GLOBAL_ERROR_MESSAGES);
    }
}

$id = get_query_param('dns_id');
if (!isset($id)) {
    add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'DNS id is missing!');
} else {
    if (call_api_delete_record($id)) {
        add_msg_to_success("DNS record with id: $id was deleted !");
    }
}


redirect_to_index();
