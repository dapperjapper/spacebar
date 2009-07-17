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
  print '<li><a href="' . ROOT_DIR . '/' . $page['url'] . '" >' . ROOT_DIR . '/' . $page['url'] . '</a>';
  if (logged_in()) {
    print ' <a href="' . ROOT_DIR . '/' . $page['url'] . '/delete" >X</a>';
  }
  print '</li>';
}
?>
</ul>
</body>
</html>
