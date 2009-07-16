<?php
include("funcs.php");

$db = db_connection();

$content = get_block_data($db, $_REQUEST['pageid'], $_REQUEST['id'], false);

print $content;
?>
