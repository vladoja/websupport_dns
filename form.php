<?php
$file_path = 'playground/dns_types_settings.json';
$str = file_get_contents($file_path);
if ($str) {
    $conf = json_decode($str, true);
    // var_dump($conf);
    // display_items($conf);
    display_items_2($conf, 'NS');
    // $template = generate_form_item();
    // echo $template;
} else {
    echo "Failed to file read: " . $file_path;
}

function display_items($conf, $dns_type = 'A')
{
    $conf = $conf['items'];
    $ns_conf = $conf[$dns_type];
    // var_dump($ns_conf);
    foreach ($ns_conf as $key => $value) {
        echo generate_form_item($key, $value);
        echo PHP_EOL;
    }
}

function display_items_2($conf, $dns_type = 'A')
{
    $conf = $conf['items_2'];
    $ns_conf = $conf[$dns_type];
    // var_dump($ns_conf);
    foreach ($ns_conf as $key => $form_data) {
        // echo $key, ' = ', print_r($form_data);
        echo generate_form_item_2($key, $form_data['label'],$form_data['default'], $form_data['required']);
        echo PHP_EOL;
    }
}



function generate_form_item($name = 'content', $label = 'IPv4 Address', $default_value = null)
{
    $template = <<<EOD
        <div class="form-group">
        <label for="$name">$label</label>
        <input type="text" name="$name" id="$name" class="form-control" required="" autofocus="" value="$default_value">
        </div>
EOD;
    // Note: before the closing EOD or EOT; there should be no spaces or tabs. otherwise you will get an error

    return $template;
}


function generate_form_item_2($name = 'content', $label = 'IPv4 Address', $default_value = null, $required = null)
{
    $required = ($required) ? 'required' : '';
    $template = <<<EOD
        <div class="form-group">
        <label for="$name">$label</label>
        <input type="text" name="$name" id="$name" class="form-control" $required autofocus="" value="$default_value">
        </div>
EOD;
    // Note: before the closing EOD or EOT; there should be no spaces or tabs. otherwise you will get an error

    return $template;
}
