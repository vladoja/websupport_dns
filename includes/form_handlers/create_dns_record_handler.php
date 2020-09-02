<?php
require './includes/classes/RequestContentFactory.php';
require './includes/classes/WSApiCaller.php';


$error_message = '';
if (isset($_POST['create_button'])) {
    // error_log('HTTP_REFERER: ' . $_SERVER['HTTP_REFERER']);
    $dns_type = get_dns_type_from_url($_SERVER['HTTP_REFERER']);
    $values = validate_input();
    if (check_for_error_messages(FORM_ERROR_MESSAGES)) {
        header('Location: create_new_record.php?dns_type=' . $dns_type);
    } else {
        if ($values) {
            $request_json = RequestContentFactory::create_body($values['dns_type'], $values);
            if (!make_api_call($request_json)) {

                header('Location: create_new_record.php?dns_type=' . $dns_type);
                exit();
            }
        }

        add_msg_to_success('Zaznam uspesne pridany .');
        header('Location:index.php');
    }
    exit();
} else {
    error_log('DNS form loaded');
}


// function getTextFromForm($input_name)
// {
//     $form_data = $_POST[$input_name];
//     if ($form_data == null) {
//         echo "Undefined element: " . $input_name;
//         return null;
//     }
//     // stripe forbidden html and php tags;
//     $form_data = strip_tags($form_data);
//     // replace empty strings
//     $form_data = str_replace(' ', '', $form_data);
//     return $form_data;
// }

function submit_record_to_api()
{
    // odosle sa do api
    // ak pride error message, tak ten sa musi zobrazit.
}

function validate_dns_type()
{
    if (!is_valid_dns_type($_REQUEST['dns_type'])) {
        error_log('Invalid DNS type: ' . $_REQUEST['dns_type']);
        add_msg_to_errors(GLOBAL_ERROR_MESSAGES, 'Invalid dns type: ' . $_REQUEST['dns_type']);
        header('Location: index.php');
        exit();
    } else {
        error_log('DNS type is valid');
    }
}

function is_valid_dns_type($dns_type)
{
    return $dns_type && in_array(strtoupper($dns_type), DNS_TYPES);
}

function validate_input()
{
    $dns_type = getTextFromForm('dns_type');
    if (!is_valid_dns_type($dns_type)) {
        add_msg_to_errors(FORM_ERROR_MESSAGES, 'Neplatny typ DNS zaznamu !');
        return false;
    } else {
        $dns_type = strtoupper($dns_type);
    }

    $content = getTextFromForm('content');

    if ($dns_type === 'A' || $dns_type === 'AAAA') {
        if (!filter_var($content, FILTER_VALIDATE_IP)) {
            add_msg_to_errors(FORM_ERROR_MESSAGES, 'Neplatna IP adresa !');
            return false;
        }
    }

    $ttl = getTextFromForm('ttl');
    if (!filter_var($ttl, FILTER_VALIDATE_INT)) {
        add_msg_to_errors(FORM_ERROR_MESSAGES, 'TTL musi byt cislo !');
        return false;
    }

    $values = add_form_input_values_to_session($dns_type);
    return $values;
}


function make_api_call($data_json)
{
    $method = 'POST';
    $path = '/v1/user/self/zone/php-assignment-9.ws/record';
    $caller = new WSApiCaller();
    if ($data_json) {
        // error_log("DATA JSON: " . $data_json);
    }

    $response = $caller->call($path, $method, $data_json);
    // $response = '{"status":"error","item":{"type":"A","id":null,"name":"@","content":"1.2.3.4","ttl":600,"note":null,"zone":{"name":"php-assignment-9.ws","service_id":84825,"updateTime":1598805340}},"errors":{"content":["For specified address already exists A record. It can not be overwritten. You need to edit it or delete it."]}}';
    // $response = '{"status":"success","item":{"type":"A","id":18857508,"name":"@","content":"1.2.3.8","ttl":600,"note":null,"zone":{"name":"php-assignment-9.ws","service_id":84825,"updateTime":1598805340}},"errors":{}}';
    // $response = '{"message":"Record not found","code":404,"status":"error","error_msg":"Record not found"}';
    // $response = '{"message":"Error while parsing json","code":400,"status":"error","error_msg":"Error while parsing json"}';

    if ($response) {
        $response = json_decode($response, true);
        // error_log(print_r($response, true));
    } else {
        return false;
    }

    if (!isset($response['status'])) {
        add_msg_to_errors(FORM_ERROR_MESSAGES, 'API Call failed !');
        return false;
    }

    if ($response['status'] === 'error') {
        if (isset($response['error_msg'])) {
            $error_msg  = $response['error_msg'];
        } else {
            $error_msg = 'API Call failed!';
        }
        add_msg_to_errors(FORM_ERROR_MESSAGES, $error_msg);
        return false;
    }

    if ($response['status'] !== 'success') {
        add_msg_to_errors(FORM_ERROR_MESSAGES, "Something went wrong!");
        return false;
    }

    return true;
}

function get_dns_type_from_url($url)
{
    $url_query = parse_url($url, PHP_URL_QUERY);
    parse_str($url_query, $params);
    $dns_type =  $params['dns_type'];

    return $dns_type;
}

function add_form_input_values_to_session($dns_type)
{
    // if (in_array($dns_type, ['A', 'AAAA', 'ANAME', 'CNAME', 'NS', 'TXT',])

    $standard_fields = array('dns_type', 'name', 'content', 'ttl', 'note');
    $dns_type = strtoupper($dns_type);

    $_SESSION[FORM_INPUT] = array();
    foreach ($standard_fields as $field) {
        $_SESSION[FORM_INPUT][$field] = getTextFromForm($field);
    }
    // Kvoli prevodu na upper case sa zopakuje pridanie
    $_SESSION[FORM_INPUT]['dns_type'] = strtoupper($dns_type);

    if ($dns_type === 'MX') {
        $_SESSION[FORM_INPUT]['prio'] = getTextFromForm('prio');
    }


    if ($dns_type === 'SRV') {
        $_SESSION[FORM_INPUT]['prio'] = getTextFromForm('prio');
        $_SESSION[FORM_INPUT]['port'] = getTextFromForm('port');
        $_SESSION[FORM_INPUT]['weight'] = getTextFromForm('weight');
    }

    // error_log('SESSION values: ' . print_r($_SESSION[FORM_INPUT], true));
    return $_SESSION[FORM_INPUT];
}
