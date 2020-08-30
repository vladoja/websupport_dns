<?php
require 'includes/header.php';
?>

    <h4>Welcome to DNS manager</h4>
    <div>
        <a class="btn btn-primary add" href="create_new_record.php?dns_type=a">Vytvoriť nový záznam</a>
    </div>
    <?php include 'includes/show_all_records.php';?>
</div>
</body>

</html>