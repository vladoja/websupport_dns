<?php


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

    foreach ($records as $record) {
        $item = "<li class='d-row'>";
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-1'>", $record['type'], '</span>');
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-2'>", $record['name'], '</span>');
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-3'>", $record['content'], '</span>');
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-4'>", $record['ttl'], '</span>');
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-5'>", '&#9998;', '</span>');
        $item .= sprintf('%s %s %s', "<span class='d-col d-col-6'>", '&#128465;', '</span>');
        $item .= "</li>";
        $html .= $item . PHP_EOL;
    }
    $html .= "</ul>" . PHP_EOL;
    $html .= "</div>" . PHP_EOL;
    return $html;
}

echo "DNS table";
$records = read_all_records__poc();
if (isset($records)) {
    echo "<br>", "Records: ", count($records);
}

echo show_records_table__poc($records);
