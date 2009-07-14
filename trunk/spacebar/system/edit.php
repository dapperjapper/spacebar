<?php
include("funcs.php");

$db = db_connection();

if (logged_in()) {
  if (isset($_REQUEST['pageid']) && isset($_REQUEST['id'])) {
    if (isset($_REQUEST['value'])) {
      change_block($db, $_REQUEST['pageid'], $_REQUEST['id'], $_REQUEST['value']);
      //If it's not ajax
      if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: edit.php?id=" . $_REQUEST['id'] . "&pageid=" . $_REQUEST['pageid']);
      }
      if (isset($_REQUEST['textile'])) {
        print(textile(stripslashes($_REQUEST['value'])));
      } else {
        print(stripslashes($_REQUEST['value']));
      }
    } else {
      $content = get_block_data($db, $_REQUEST['pageid'], $_REQUEST['id'], false);
      $page = get_page_by_id($db, $_REQUEST['pageid']);
      $url = $page['url'];
      ?>
<html>
<head>
</head>
<body>
<form action="edit.php" method="post">
<input type="hidden"name="id" value="<?=$_REQUEST['id'];?>" />
<input type="hidden"name="pageid" value="<?=$_REQUEST['pageid'];?>" />
<textarea name="value" rows="10" cols="50" ><?=$content;?></textarea><br />
<input type="submit" value="Ok" /> <a href="../<?=$url;?>" >Back</a>
</form>
<h2>Preview:</h2>
<div><?=textile($content);?></div>
</body>
</html>
      <?php
    }
  } else {
    print("Missing block id! Request: ");
    print_r($_REQUEST);
    die();
  }
} else {
  die("Login, you!");
}
?>
