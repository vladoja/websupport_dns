<?php
require './includes/classes/WSApiCaller.php';

$method = 'POST';
$path = '/v1/user/self/zone/php-assignment-9.ws/record';
$data = '{"type":"A","name":"@","content": "1.2.3.4","ttl": 600}';
$data = '{"type":"A","name":"*","content": "1.2.3.9"}';
$data = '{"type":"AAAA","name":"*","content": "1200:0000:AB00:1234:0000:2552:7777:1313"}';
// Invalid input
// $data = '{"type":"A","name":"@","content": "1.2.3.5","ttl": 600}';

$caller = new WSApiCaller();

$response = $caller->call($path, $method, $data);

echo PHP_EOL, $response;
