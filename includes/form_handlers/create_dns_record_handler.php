<?php
define('DNS_TYPES', array('A', 'AAAA', 'ANAME', 'CAA', 'CNAME', 'MX', 'NS', 'SRV', 'TXT'));

$error_message = '';
if (isset($_POST['create_button'])) {
    validate_input();
    $dns_type = getTextFromForm('dns_type');
    error_log('FORM submitted. Dns type: ' . $dns_type);
    header('Location:index.php');
    exit();
} else {
    error_log('DNS form loaded');
}


function getTextFromForm($input_name)
{
    $form_data = $_POST[$input_name];
    if ($form_data == null) {
        echo "Undefined element: " . $input_name;
        return null;
    }
    // stripe forbidden html and php tags;
    $form_data = strip_tags($form_data);
    // replace empty strings
    $form_data = str_replace(' ', '', $form_data);
    return $form_data;
}

function submit_record_to_api()
{
    // odosle sa do api
    // ak pride error message, tak ten sa musi zobrazit.
}

function validate_dns_type() {
    if (!is_valid_dns_type($_REQUEST['dns_type'])) {
        error_log('Invalid DNS type: '.$_REQUEST['dns_type']);
        header('Location: index.php');
        exit();
    }else {
        error_log('DNS type is valid');
    }    
}

function is_valid_dns_type($dns_type)
{
    return $dns_type && in_array(strtoupper($dns_type), DNS_TYPES);
}

function validate_input()
{
    $dns_type = getTextFromForm('dns_type');
    if (!is_valid_dns_type($dns_type)) {
        error_log('Invalid DNS type');
    }
    // Pokial neplatny typ DNES zaznamu, tak error message a koniec
    // Zvaliduj vsetky vyzadovane polia pre dany typ DNS zaznamu

}
