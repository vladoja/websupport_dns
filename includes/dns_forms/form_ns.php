        <div class="form-group">
        <label for="name">Subdomain name</label>
        <input type="text" name="name" id="name" class="form-control" required autofocus="" value="<?php echo get_form_input_value('name','@');?>">
        </div>
        <div class="form-group">
        <label for="content">Canonical hostname</label>
        <input type="text" name="content" id="content" class="form-control" required autofocus="" value="<?php echo get_form_input_value('content');?>">
        </div>
        <div class="form-group">
        <label for="ttl">TTL</label>
        <input type="text" name="ttl" id="ttl" class="form-control"  autofocus="" value="<?php echo get_form_input_value('ttl',600);?>">
        </div>
        <div class="form-group">
        <label for="note">Poznamka</label>
        <input type="text" name="note" id="note" class="form-control"  autofocus="" value="<?php echo get_form_input_value('note');?>">
        </div>
