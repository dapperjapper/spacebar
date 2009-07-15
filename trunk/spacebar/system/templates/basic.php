<?php
//If datepub hasn't been set, do it!
$datepub = get_block_data($db, $page['id'], "datepub", false);
if ($datepub == "") {
  $datepub = date("D M j Y");
  change_block($db, $page['id'], "datepub", $datepub);
}
//We save the title because we use it twice (cuts down on db requests)
$title = get_block_data($db, $page['id'], "title", false);
?>
<html>
<head>
<?=template_head($page['id']);?>
<link rel="stylesheet" type="text/css" href="<?=ROOT_DIR;?>/system/templates/sb_parts/style.css" />
<title><?=$title;?></title>
</head>
<body>
<?php include('sb_parts/header.php'); ?>
<?=block($db, $page['id'], "title", "h1", false, $title);?>
<small>published <?=$datepub?></small>
<?=block($db, $page['id'], "content");?>
<?php include('sb_parts/footer.php'); ?>
</body>
</html>
