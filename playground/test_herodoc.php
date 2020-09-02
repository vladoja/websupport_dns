<?php
$var = ' test variable ';

$name = 'content';
$label = 'IPv4 Address';
$default_value = null;


$str = <<<EOD
<div class="form-group">
<label for="$name">$label</label>
<input type="text" name="$name" id="$name" class="form-control" required="" autofocus="" value="$default_value">
</div>
EOD;



echo PHP_EOL, $str;

// echo sprintf($str, $var);