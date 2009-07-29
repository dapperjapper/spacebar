<?php
$blogsubpages = get_subpages($db, $url);
foreach ($blogsubpages as $key => $blogsubpage) {
  $blogsubpages[$key]['blocks']['title'] = get_block_data($db, $blogsubpage['id'], "title", false, "Unnamed Post");
  $blogsubpages[$key]['blocks']['datepub'] = get_block_data($db, $blogsubpage['id'], "datepub", false, date("D M j Y"));
  $blogsubpages[$key]['blocks']['tspub'] = strtotime($blogsubpages[$key]['blocks']['datepub']);
}
usort($blogsubpages, "sort_by_date");
function sort_by_date ($a, $b) {
  return ($a['blocks']['tspub'] < $b['blocks']['tspub']) ? 1 : -1;
}
//We save the title because we use it twice (cuts down on db requests)
$title = get_block_data($db, $page['id'], "title", false);
?>
<html>
<head>
<?=template_head($db, $page);?>
<title><?=$title;?></title>
</head>
<body>
<?php include('parts/header.php'); ?>
<?=block($db, $page['id'], "title", "h1", false, $title);?>
<ul>
<?php
foreach ($blogsubpages as $blogsubpage) {
  print '<li><a href="' . ROOT_DIR . '/' . $blogsubpage['url'] . '" >' . $blogsubpage['blocks']['title'] . ' (' . $blogsubpage['blocks']['datepub'] . ')</a></li>';
}
?>
</ul>
<?php include('parts/footer.php'); ?>
</body>
</html>
