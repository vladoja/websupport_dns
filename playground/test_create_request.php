<?php
require './includes/classes/RequestContentFactory.php';


$dns_type = 'A';
$values = array('server_ip' => '1.2.3.5', 'name' => '@', 'ttl' => 600);


$request_json = RequestContentFactory::create_body($dns_type, $values);

echo PHP_EOL, $request_json;
