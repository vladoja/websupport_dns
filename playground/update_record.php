<?php
require './includes/classes/WSApiCaller.php';

$method = 'PUT';
$domain_name = 'php-assignment-9.ws';
$record_id = 18842847;
$path = '/v1/user/self/zone/' . $domain_name . '/record/' . $record_id;
$data_json = '{
    "name":"@",
    "content": "1.2.3.4",
    "ttl": 600
}';



$caller = new WSApiCaller();

$response = $caller->call($path, $method, $data_json);


echo PHP_EOL, $response;
