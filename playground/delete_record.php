<?php
require './includes/classes/WSApiCaller.php';
$method = 'DELETE';
$domain_name = 'php-assignment-9.ws';
$record_id = 18842766;
$path = '/v1/user/self/zone/' . $domain_name . '/record/' . $record_id;


$caller = new WSApiCaller();

$response = $caller->call($path, $method);

echo PHP_EOL . $response;
