<?php
require './includes/classes/RequestContentFactory.php';

define('DNS_TYPES', array('A', 'AAAA', 'ANAME', 'CAA', 'CNAME', 'MX', 'NS', 'SRV', 'TXT'));


$error_message = '';
if (isset($_POST['create_button'])) {
    $values = validate_input();
    if (check_for_error_messages(FORM_ERROR_MESSAGES)) {
        header('Location: create_new_record.php?dns_type=a');
    } else {
        if ($values) {
            $request_json = RequestContentFactory::create_body($values['dns_type'], $values);
            error_log('values: ' . $request_json);
        }
        add_msg_to_success('Zaznam uspesne pridany .');
        header('Location:index.php');
    }
    exit();
} else {
    error_log('DNS form loaded');
}


function getTextFromForm($input_name)
{
    $form_data = $_POST[$input_name];
    if ($form_data == null) {
        echo "Undefined element: " . $input_name;
        return null;
    }
    // stripe forbidden html and php tags;
    $form_data = strip_tags($form_data);
    // replace empty strings
    $form_data = str_replace(' ', '', $form_data);
    return $form_data;
}

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

    $name = getTextFromForm('note');


    $server_ip = getTextFromForm('server_ip');
    if (!filter_var($server_ip, FILTER_VALIDATE_IP)) {
        add_msg_to_errors(FORM_ERROR_MESSAGES, 'Neplatna IP adresa !');
        return false;
    } 

    $ttl = getTextFromForm('ttl');
    if (!filter_var($ttl, FILTER_VALIDATE_INT)) {
        add_msg_to_errors(FORM_ERROR_MESSAGES, 'TTL musi byt cislo !');
        return false;
    }

    $note = getTextFromForm('note');

    return compact('dns_type', 'name', 'server_ip', 'ttl', 'note');
}


function make_api_call()
{
}
