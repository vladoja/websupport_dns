<?php

$timezone = date_default_timezone_set("Europe/Bratislava");
require_once './config/constants.php';


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
            error_log('Printing out success message: ' . $msg);
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
