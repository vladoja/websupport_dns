<?php
class RequestContentFactory
{


    public static function create_body(string $dns_type, array $values)
    {
        if ($dns_type === 'A') {
            $request_body = array();
            $request_body['type'] = $dns_type; 
            $request_body['name'] =  $values['name'];
            $request_body['content'] =  $values['server_ip'];
            $request_body['ttl'] =  $values['ttl'];            
            $request_body['note'] =  $values['note'];
            return json_encode($request_body);
        }
    }
}
