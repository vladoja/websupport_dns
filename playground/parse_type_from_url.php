<?php
$url = 'http://localhost:8000/create_new_record.php?dns_type=aaaa';


$path = parse_url($url, PHP_URL_PATH);
$url_query = parse_url($url, PHP_URL_QUERY);

parse_str($url_query, $params);
$dns_type =  $params['dns_type'];

echo "path= ", $path, PHP_EOL;
echo "params= ", $url_query, PHP_EOL;
echo "dns_type= ", $dns_type, PHP_EOL;
