<?php
$file_path = './config/dns_types_settings.json';
$output_path = './includes/dns_forms/';
$str = file_get_contents($file_path);
if ($str) {
    $conf = json_decode($str, true);
    generate_forms($conf);
} else {
    echo "Failed to file read: " . $file_path;
}

// TODO: System depended path separator 
function write_to_file($file_name = 'form.php', $content) {
    global $output_path;
    $file_path = $output_path . $file_name;
    $file = fopen($file_path, 'w');
    fwrite($file, $content);
    fclose($file);

}


function generate_forms($conf) {
    $dns_types = $conf['items'];
    $counter = 0;
    foreach($dns_types as $type => $form_data) {
        // echo $type, PHP_EOL;
        // echo "   " , print_r($form_data, true);
        $content =  generate_form($form_data);
        $file_name = 'form_'.strtolower($type).'.php';
        write_to_file($file_name, $content);
        $counter++;
        // if ($counter) break;
    }
    echo PHP_EOL, "Forms generated: ", $counter, "  successfuly";
}


function generate_form($conf)
{
    // var_dump($ns_conf);
    $response = "";
    foreach ($conf as $key => $form_data) {
        // echo $key, ' = ', print_r($form_data), PHP_EOL;
        $response .= generate_form_items($key, $form_data['label'],$form_data['default'], $form_data['required']);
        $response .= PHP_EOL;
    }
    return $response;
}


function generate_form_items($name = 'content', $label = 'IPv4 Address', $default_value = null, $required = null)
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
