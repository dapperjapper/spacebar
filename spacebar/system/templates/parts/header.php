<div id="logo-outline" >
<div id="logo-center" >
<img id="logo" src="<?=ROOT_DIR;?>/system/templates/parts/logo.png" />
<?php if (preg_match("/iPhone/", $_SERVER['HTTP_USER_AGENT']) == 1 && logged_in()) { ?>
<a class="rounded_gray_button" onclick="$('.editable').dblclick(); $('.editable-notextile').dblclick();">edit</a>
<?php } ?>
</div>
</div>
<div id="container" >
