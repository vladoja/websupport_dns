<?php

$timezone = date_default_timezone_set("Europe/Bratislava");
require_once './config/constants.php';

function output_validation_error(string $formInputName)
{
    if (isset($_SESSION[ERROR_ARRAY][$formInputName]) && count($_SESSION[ERROR_ARRAY][$formInputName]) > 0) {
        foreach ($_SESSION[ERROR_ARRAY][$formInputName] as $key => $err_message) {
            error_log('Printing out error message: ' . $err_message);
            echo '<div class="alert-box">';
            echo '<span class="close-btn">×</span>';
            echo $err_message;
            echo '</div>';
            
            unset($_SESSION[ERROR_ARRAY][$formInputName][$key]);
        }
    }
}

// TODO: Validation error pojdu do sesssion
function add_msg_to_errors(string $formInputName, string $error_message)
{
    if (!isset($_SESSION[ERROR_ARRAY])) {
        $_SESSION[ERROR_ARRAY] = array();
    }
    $err_array = $_SESSION[ERROR_ARRAY][$formInputName] ?? array();
    array_push($err_array, $error_message);
    $_SESSION[ERROR_ARRAY][$formInputName] = $err_array;
}
