<?php
require './includes/classes/WSApiCaller.php';

$method = 'POST';
$path = '/v1/user/self/zone/php-assignment-9.ws/record';
$data = '{"type":"A","name":"@","content": "1.2.3.4","ttl": 600}';
// Invalid input
// $data = '{"type":"A","name":"@","content": "1.2.3.5","ttl": 600}';

$caller = new WSApiCaller();

$response = $caller->call($path, $method, $data);

echo $response;
