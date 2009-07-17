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
<link rel="stylesheet" type="text/css" href="<?=ROOT_DIR;?>/system/templates/parts/style.css" />
<title><?=$title;?></title>
</head>
<body>
<?php include('parts/header.php'); ?>
  <?=block($db, $page['id'], "title", "h1", false, $title);?>
  <?=block($db, $page['id'], "content");?> 
  <p class="published-date">published <?=$datepub?></p>
<?php include('parts/footer.php'); ?>
</body>
</html>
