<?php
require './includes/classes/WSApiCaller.php';

$method = 'GET';
$path = '/v1/user/self/zone/php-assignment-9.ws/record';;

$caller = new WSApiCaller();

$response = $caller->call($path, $method);

echo $response;
