<?php
require 'includes/header.php';
require 'includes/classes/ComponentFactory.php';
require 'includes/show_all_records.php';

clear_form_input_values();
?>

    <h4>Welcome to DNS manager</h4>
    
    <?php 
    $records = get_all_records_from_api();
    // TODO: Dat ako konstantu
    if (!output_validation_error(GLOBAL_ERROR_MESSAGES)) {
        output_success_messages();
    }
    ?>
    <div class="row">
        <div class="col">
            <h1 class="subpage-header">Vsetky DNS zaznamy</h1>
        </div>
        <div class="col">
            <a class="btn btn-primary add" id="createRecordLink" href="create_new_record.php?dns_type=a">Vytvoriť nový záznam</a>
            <?php echo ComponentFactory::create_dns_dropdown();?>
        </div>
    </div>
    <?php echo show_records_table__poc($records);?>
</div>
</body>
<script src='/assets/js/main.js'></script>

</html>