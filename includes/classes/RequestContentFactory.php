<?php
class RequestContentFactory
{


    public static function create_body(string $dns_type, array $values)
    {
        // if ($dns_type === 'A' || $dns_type === 'AAAA') {
        //     $request_body = array();
        //     $request_body['type'] = $dns_type; 
        //     $request_body['name'] =  $values['name'];
        //     $request_body['content'] =  $values['content'];
        //     $request_body['ttl'] =  $values['ttl'];            
        //     $request_body['note'] =  $values['note'];
        //     return json_encode($request_body);
        // }
        // TODO: Lepsie overenie parametrov
        $values['type'] = $dns_type;
        return json_encode($values);
    }


    public static function create_update_body(string $dns_type, array $values)
    {
        if (isset($values['type'])) {
            unset($values['type']);
        }
        if (isset($values['dns_type'])) {
            unset($values['dns_type']);
        }
        return json_encode($values);
    }
}
