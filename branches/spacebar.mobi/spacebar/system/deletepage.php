<?php
$splitpath = array_slice($splitpath, 0, -1);
$name = $splitpath[count($splitpath)-1];
$url = implode('/', $splitpath);
if (!logged_in($db, $url)) {
  die("Bad boy/girl! Login first!");
}
?>
<html>
<head>
<?=all_head();?>
</head>
<body>
<?php include('templates/parts/header.php'); ?>
<h1>Are you sure you want to delete page "<?php if ($url == "") {print "index";} else {print $url;} ?>" and all the content on it?</h1>
<a class="rounded_gray_button" href="<?=ROOT_DIR?>/<?=$url?>" >no</a> or <a class="rounded_gray_button" href="<?=ROOT_DIR?>/<?=$url?>/reallydelete" >yes</a>
<?php include('templates/parts/footer.php'); ?>
</body>
</html>
