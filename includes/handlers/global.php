<?php

$timezone = date_default_timezone_set("Europe/Bratislava");
require_once './config/constants.php';

function clear_form_input_values()
{
    if (isset($_SESSION[FORM_INPUT])) {
        $_SESSION[FORM_INPUT] = null;
    }
}

function check_for_error_messages(string $msg_category)
{
    return isset($_SESSION[ERROR_ARRAY][$msg_category]) && count($_SESSION[ERROR_ARRAY][$msg_category]) > 0;
}

function check_for_success_messages()
{
    return isset($_SESSION[SUCCESS_ARRAY]) && is_array($_SESSION[SUCCESS_ARRAY]) && count($_SESSION[SUCCESS_ARRAY]) > 0;
}

function output_validation_error(string $formInputName)
{
    if (check_for_error_messages($formInputName)) {
        foreach ($_SESSION[ERROR_ARRAY][$formInputName] as $key => $err_message) {
            error_log('Printing out error message: ' . $err_message);
            echo '<div class="alert-box">';
            echo '<span class="close-btn">×</span>';
            echo $err_message;
            echo '</div>';

            unset($_SESSION[ERROR_ARRAY][$formInputName][$key]);
        }
        return true;
    }
    return false;
}


function output_success_messages()
{
    if (check_for_success_messages()) {
        foreach ($_SESSION[SUCCESS_ARRAY] as $key => $msg) {
            // error_log('Printing out success message: ' . $msg);
            echo '<div class="alert-box success">';
            echo '<span class="close-btn">×</span>';
            echo $msg;
            echo '</div>';

            unset($_SESSION[SUCCESS_ARRAY][$key]);
        }
        return true;
    }
    return false;
}


function add_msg_to_errors(string $formInputName, string $error_message)
{
    if (!isset($_SESSION[ERROR_ARRAY])) {
        $_SESSION[ERROR_ARRAY] = array();
    }
    $err_array = $_SESSION[ERROR_ARRAY][$formInputName] ?? array();
    array_push($err_array, $error_message);
    $_SESSION[ERROR_ARRAY][$formInputName] = $err_array;
}

function add_msg_to_success(string $error_message)
{
    if (!isset($_SESSION[SUCCESS_ARRAY])) {
        $_SESSION[SUCCESS_ARRAY] = array();
    }
    $msg_array = $_SESSION[SUCCESS_ARRAY];
    array_push($msg_array, $error_message);
    $_SESSION[SUCCESS_ARRAY] = $msg_array;
}

function get_form_input_value(string $input_name, $default = null)
{
    return isset($_SESSION[FORM_INPUT][$input_name]) ? $_SESSION[FORM_INPUT][$input_name] : $default;
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


function getTextFromArray($input_name, $array)
{

    if ($array) {
        if (isset($array[$input_name])) {
            return $array[$input_name];
        }
    }
    return null;
}

function add_array_values_to_session($dns_type, $array_values)
{
    $standard_fields = array('id', 'type', 'name', 'content', 'ttl', 'note');
    $dns_type = strtoupper($dns_type);

    $_SESSION[FORM_INPUT] = array();
    foreach ($standard_fields as $field) {
        $_SESSION[FORM_INPUT][$field] = getTextFromArray($field, $array_values);
    }
    // Kvoli prevodu na upper case sa zopakuje pridanie
    $_SESSION[FORM_INPUT]['dns_type'] = strtoupper($dns_type);

    if ($dns_type === 'MX') {
        $_SESSION[FORM_INPUT]['prio'] = getTextFromArray('prio', $array_values);
    }


    if ($dns_type === 'SRV') {
        $_SESSION[FORM_INPUT]['prio'] = getTextFromArray('prio', $array_values);
        $_SESSION[FORM_INPUT]['port'] = getTextFromArray('port', $array_values);
        $_SESSION[FORM_INPUT]['weight'] = getTextFromArray('weight', $array_values);
    }

    return $_SESSION[FORM_INPUT];
}


function parse_form_type()
{
    if (isset($_GET['dns_type'])) {
        $formType = $_GET['dns_type'];
        return $formType;
    } else {
        return null;
    }
}

function get_query_param($param_name) {
    if (isset($_GET[$param_name])) {
        return $_GET[$param_name];
    }else {
        return null;
    }
}


function redirect_to_index() {
    header('Location: index.php');
    exit();
}

function handle_error_messages_from_response($response,string $message_category) {
    if (!isset($response['status'])) {
        add_msg_to_errors($message_category, 'API Call failed !');
        return false;
    }

    if ($response['status'] === 'error') {
        if (isset($response['error_msg'])) {
            $error_msg  = $response['error_msg'];
        } else {
            $error_msg = 'API Call failed!';
        }
        add_msg_to_errors($message_category, $error_msg);
        return false;
    }

    if ($response['status'] !== 'success') {
        add_msg_to_errors($message_category, "Something went wrong!");
        return false;
    }
    return true;
}