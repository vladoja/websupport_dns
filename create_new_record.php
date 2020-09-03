<?php
require 'includes/header.php';
require 'includes/form_handlers/create_dns_record_handler.php';

validate_dns_type();

// $uri = $_SERVER['REQUEST_URI'];
// error_log($uri);

$form_type = parse_form_type();
if (!$form_type) {
    $form_type = "a";
}

?>

    <div class="container">
        <div class="row">
            <?php output_validation_error(FORM_ERROR_MESSAGES);?>
        </div>
        <div class="container-form">
            <?php if (isset($form_type)){
                echo '<div class="form-header">Vytvorenie DNS typu: '. strtoupper($form_type).' </div>';
            }?>
            <form method="POST" action="create_new_record.php">
                <div class="form-wrapper">
                    <input type="hidden" id="dns_type" name="dns_type" value="<?php echo $form_type;?>">
                    <?php
                        include('./includes/dns_forms/form_'.strtolower($form_type).'.php');
                    ?>

                    <div class="form-group">
                        <a href="index.php" class="btn btn-secondary">Zrušiť</a>
                        <input type="submit" name="create_button" class="form-control btn btn-primary" value="Vytvorit">
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
<script src='/assets/js/main.js'></script>
</html>