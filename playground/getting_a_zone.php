<?php
$time = time();
$method = 'GET';
$domain_name = 'php-assignment-9.ws';
// $path= 'v1/user/self/zone/'. urlencode($domain_name);
$path = 'v1/user/self/zone/' . $domain_name;
$api = 'https://rest.websupport.sk';
$apiKey = '3ce3178d-25c4-4ed3-8c64-f5142de20efe';
$secret = 'c48e8c9dbd7e72ff7854433e2ee97927c919568b';
$canonicalRequest = sprintf('%s %s %s', $method, $path, $time);
$signature = hash_hmac('sha1', $canonicalRequest, $secret);



echo PHP_EOL, 'api:', $api;
echo PHP_EOL, 'URL:', sprintf('%s:%s', $api, $path);
echo PHP_EOL, 'canonicalRequest:', $canonicalRequest;
echo PHP_EOL, 'signature:', $signature;
echo PHP_EOL, 'Date:', gmdate('Ymd\THis\Z', $time);
echo PHP_EOL, PHP_EOL;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, sprintf('%s/%s', $api, $path));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ':' . $signature);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Date: ' . gmdate('Ymd\THis\Z', $time),
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo PHP_EOL, 'Request Error:' . curl_error($ch);
}
echo PHP_EOL,print_r(curl_getinfo($ch));
curl_close($ch);

echo $response;
