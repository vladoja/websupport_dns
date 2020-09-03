<?php
require './includes/handlers/global.php';
require './includes/classes/RequestContentFactory.php';
require './includes/classes/WSApiCaller.php';


handle_update_action();

function handle_update_action(){

    $dns_type = $_POST['dns_type'];
    $id = $_GET['dns_id'];
    
    if (!$dns_type) {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'DNS type not available.');
        redirect_to_index();
    }
    
    if (!$id) {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'DNS id not available.');
        redirect_to_index();
    }
    
    $values = add_form_input_values_to_session($dns_type);
    
    $request_json = RequestContentFactory::create_update_body($values['dns_type'], $values);
    
    // echo "DNS type: ", $dns_type, '<br>';
    // echo "DNS id: ", $id, '<br>';
    // echo "request_json: ", $request_json, '<br>';
    
    
    $result = call_api_for_update($id, $request_json);
    
    if ($result) {
        add_msg_to_success('DNS record updated successfuly .');
    }
    redirect_to_index();
}


function call_api_for_update($id, $data_json)
{
    $method = 'PUT';
    $path = '/v1/user/self/zone/' . DOMAIN_NAME . '/record/' . $id;
    $caller = new WSApiCaller();

    $response = $caller->call($path, $method, $data_json);

    if ($response) {
        // error_log('RESPONSE json ' . $response);
        $response = json_decode($response, true);
        $status = handle_error_messages_from_response($response, FORM_ERROR_MESSAGES);

        if (!$status) {
            return false;
        }
        // error_log('RESPONSE: ' . print_r($response, true));
    } else {
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'API Call failed!');
        return false;
    }

    return $response;
}
