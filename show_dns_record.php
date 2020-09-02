<?php
require 'includes/header.php';
require 'includes/form_handlers/show_dns_record_handler.php';

$id = 18715170;
$id = get_query_param('dns_id');
// Ziskam id z url

// Zavolam ws api
$response = make_api_call($id);
if (!$response) {
    header('Location: index.php');
    exit();
}
$form_type = $response['type'];
add_array_values_to_session($form_type, $response);

// Ziskam error message , ak je tak pridat hlasku a presmerovat na index

//  Ak vporiadku, tak naciat prislusnu form include
//  Naplnit data do session
// $_SESSION[FORM_INPUT]['content'] = "1.2.3.4";
?>

<div class="container-form">
    <?php if (isset($form_type)) {
        echo '<div class="form-header">DNS Zaznam typu: ' . strtoupper($form_type) . ' </div>';
    } ?>
    <form method="POST" action="create_new_record.php">
        <div class="form-wrapper">
            <input type="hidden" id="dns_type" name="dns_type" value="<?php echo $form_type; ?>">
            <?php
            include('./includes/dns_forms/form_' . strtolower($form_type) . '.php');
            ?>

            <div class="form-group">
                <input type="submit" name="create_button" class="form-control" value="Ulozit">
            </div>
        </div>
    </form>

</div>
</div>
</body>
<script src='/assets/js/main.js'></script>

</html>