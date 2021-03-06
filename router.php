<?php
ob_start(); // Turns on output buffering
session_start();
define('ERROR_ARRAY', 'error_array');
define('SUCCESS_ARRAY', 'success_array');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// error_log('Incoming request: ' . $path);
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
    return false;
} else {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/index.php';
}