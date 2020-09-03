<?php
require './includes/classes/WSApiCaller.php';

$method = 'GET';
$path = '/v1/user/self/zone/'.DOMAIN_NAME.'/record';;

$caller = new WSApiCaller();

$response = $caller->call($path, $method);

echo $response;
