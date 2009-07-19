<?php
if (logged_in()) {
  $splitpath = array_slice($splitpath, 0, -1);
  $name = $splitpath[count($splitpath)-1];
  $url = implode('/', $splitpath);
} else {
  die("Bad boy/girl! Login first!");
}
?>
<html>
<head>
</head>
<body>
<h1>Are you sure you want to delete page "<?php if ($url == "") {print "index";} else {print $url;} ?>" and all the content on it?</h1>
<a href="<?=ROOT_DIR?>/<?=$url?>" >no</a> or <a href="<?=ROOT_DIR?>/<?=$url?>/reallydelete" >yes</a>
</body>
</html>
