<?php
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
<title><?=$title;?></title>
</head>
<body>
<h1 class="editable" id="title" ><?=$title;?></h1>
<small>published <?=$datepub?></small>
<?=edit_link($page['id'], 'title', "Edit title");?>
<div class="editable" id="content" ><?=get_block_data($db, $page['id'], "content");?></div>
<?=edit_link($page['id'], 'content', "Edit content");?>
</body>
</html>
