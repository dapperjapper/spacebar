<?php
$subpages = get_subpages($db, $url);
foreach ($subpages as $key => $subpage) {
  $subpages[$key]['blocks']['title'] = get_block_data($db, $subpage['id'], "title", false, "Unnamed Post");
  $subpages[$key]['blocks']['datepub'] = get_block_data($db, $subpage['id'], "datepub", false, date("D M j Y"));
  $subpages[$key]['blocks']['tspub'] = strtotime($subpages[$key]['blocks']['datepub']);
}
usort($subpages, "sort_by_date");
function sort_by_date ($a, $b) {
  return ($a['blocks']['tspub'] < $b['blocks']['tspub']) ? 1 : -1;
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
<h1 class="editable" id="title" ><?=$title;?></h1><?=edit_link($page['id'], 'title');?>
<ul>
<?php
foreach ($subpages as $subpage) {
  print '<li><a href="' . ROOT_DIR . '/' . $subpage['url'] . '" >' . $subpage['blocks']['title'] . ' (' . $subpage['blocks']['datepub'] . ')</a></li>';
}
?>
</ul>
</body>
</html>
