<?php
$pages = get_all_pages($db);
?>
<html>
<head>
<title>Sitemap</title>
</head>
<body>
<ul>
<?php
foreach ($pages as $page) {
  print '<li><a href="' . ROOT_DIR . '/' . $page['url'] . '" >' . ROOT_DIR . '/' . $page['url'] . '</a> <a href="' . ROOT_DIR . '/' . $page['url'] . '/delete" >X</a></li>';
}
?>
</ul>
</body>
</html>
