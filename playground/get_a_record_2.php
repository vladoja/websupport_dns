<?php
require './includes/classes/WSApiCaller.php';

$method = 'GET';
$id = 18715182;
$id = 18715170;
$path = '/v1/user/self/zone/php-assignment-9.ws/record/' . $id;


$caller = new WSApiCaller();

$response = $caller->call($path);

echo $response;
