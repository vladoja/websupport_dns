<?php
require 'includes/header.php';
clear_form_input_values();
?>

    <h4>Welcome to DNS manager</h4>
    
    <?php 
    // TODO: Dat ako konstantu
    if (!output_validation_error(GLOBAL_ERROR_MESSAGES)) {
        output_success_messages();
    }
    ?>
    <div>
        <a class="btn btn-primary add" href="create_new_record.php?dns_type=a">Vytvoriť nový záznam</a>
    </div>
    <?php include 'includes/show_all_records.php';?>
</div>
</body>
<script src='/assets/js/main.js'></script>

</html>