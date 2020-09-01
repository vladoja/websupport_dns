<?php
require 'includes/header.php';
require 'includes/form_handlers/create_dns_record_handler.php';

validate_dns_type();


function parse_form_type()
{
    if (isset($_GET['dns_type'])) {
        $formType = $_GET['dns_type'];
        error_log("Create new record: " . $formType);
        return $formType;
    } else {
        return null;
    }
}

// $uri = $_SERVER['REQUEST_URI'];
// error_log($uri);

$form_type = parse_form_type();
if (!$form_type) {
    $form_type = "A";
}

?>
    <h2><a href="index.php">DNS manager</a>
    </h2>
    <h4>Create new DNS Record</h4>
    <div class="row">
        <?php output_validation_error(FORM_ERROR_MESSAGES);?>
    </div>
    <div class="container-form">
        <?php if (isset($form_type)){
            echo '<div class="form-header">DNS Zaznam typu: '. strtoupper($form_type).' </div>';
        }?>
        <form method="POST" action="create_new_record.php">
            <div class="form-wrapper">
                <input type="hidden" id="dns_type" name="dns_type" value="<?php echo $form_type;?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required="" autofocus="" value="<?php echo get_form_input_value('name');?>">
                </div>
                <div class="form-group">
                    <label for="content">Server IP</label>
                    <input type="text" name="content" id="content" class="form-control" required="" autofocus="" value="<?php echo get_form_input_value('content');?>">
                </div>
                <?php if (strtoupper($form_type) === 'MX') {
                    echo '<div class="form-group">';
                    echo  '<label for="priority">Record Priority</label>';
                    echo '<input type="text" name="priority" id="priority" class="form-control" required="" autofocus="" value="'.get_form_input_value('priority') .'">';
                    echo '</div>';
                }?>

                <div class="form-group">
                    <label for="ttl">TTL</label>
                    <input type="text" name="ttl" id="ttl" class="form-control" value="<?php echo get_form_input_value('ttl', 600);?>" required="" autofocus="">
                </div>
                <div class="form-group">
                    <label for="note">Poznamka</label>
                    <input type="text" name="note" id="note" class="form-control" autofocus="" value="<?php echo get_form_input_value('note');?>">
                </div>
                <div class="form-group">
                    <input type="submit" name="create_button" class="form-control" value="Vytvorit">
                </div>
            </div>
        </form>

    </div>
</div>
</body>
<script src='/assets/js/main.js'></script>
</html>