<?php
require './includes/classes/WSApiCaller.php';


function read_all_records__poc()
{
    $file_json = file_get_contents('./playground/list_all_records.json');

    if ($file_json) {
        $records = json_decode($file_json, true);
        if (isset($records['items'])) {
            return $records['items'];
        }
    }
    return null;
}


function get_all_records_from_api()
{
    $method = 'GET';
    $path = '/v1/user/self/zone/php-assignment-9.ws/record';;

    $caller = new WSApiCaller();

    // $response_json = null;
    $response_json = $caller->call($path, $method);

    $records = null;
    if ($response_json) {
        $records = json_decode($response_json, true);
        if (isset($records['items'])) {
            return $records['items'];
        }
    }

    return $records;
}


function create_table_header()
{
    $html = "<li class='d-row d-header'>" . PHP_EOL;
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-1'>", 'type', '</span>');
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-2'>", 'name', '</span>');
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-3'>", 'content', '</span>');
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-4'>", 'ttl', '</span>');
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-5'>", '', '</span>');
    $html .= sprintf('%s %s %s', "<span class='d-col d-col-6'>", '', '</span>');

    $html .= "</li>" . PHP_EOL;
    return $html;
}

function show_records_table__poc($records)
{
    $html = PHP_EOL . "<div class='d-table'>" . PHP_EOL;
    $html .= "<ul>" . PHP_EOL;
    $html .= create_table_header();
    $edit_record_path = 'show_dns_record.php?dns_id=';

    if ($records) {
        foreach ($records as $record) {
            $item = "<li class='d-row' id=" . $record['id'] . ">";
            $item .= sprintf('%s %s %s', "<span class='d-col d-col-1'>", $record['type'], '</span>');
            $item .= sprintf('%s %s %s', "<span class='d-col d-col-2'>", $record['name'], '</span>');
            $item .= sprintf('%s %s %s', "<span class='d-col d-col-3'>", $record['content'], '</span>');
            $item .= sprintf('%s %s %s', "<span class='d-col d-col-4'>", $record['ttl'], '</span>');
            // $item .= sprintf('%s %s %s', "<span class='d-col d-col-5'><a href=", '&#9998;', '</span>');
            $item .= sprintf("<span class='d-col d-col-5'><a href='%s'>&#9998;</a></span>", $edit_record_path . $record['id']);
            $item .= sprintf('%s %s %s', "<span class='d-col d-col-6'>", '&#128465;', '</span>');
            $item .= "</li>";
            $html .= $item . PHP_EOL;
        }
    }
    $html .= "</ul>" . PHP_EOL;
    $html .= "</div>" . PHP_EOL;
    return $html;
}


$records = get_all_records_from_api();
// if (isset($records)) {
//     echo "<br>", "Records: ", count($records);
// }

echo show_records_table__poc($records);
