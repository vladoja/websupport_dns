<?php
$error_message = '';
if (isset($_POST['create_button'])) {
    error_log('FORM submitted');
    header('Location:index.php');
    exit();
} else {
    error_log('DNS form loaded');
}
