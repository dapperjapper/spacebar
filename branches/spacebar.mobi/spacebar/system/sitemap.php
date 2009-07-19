<?php
$pages = get_all_pages($db);
?>
<html>
<head>
<title>Sitemap</title>
<?=all_head();?>
</head>
<body>
<?php include('templates/parts/header.php'); ?>
<ul>
<?php
foreach ($pages as $page) {
  print '<li><a href="' . ROOT_DIR . '/' . $page['url'] . '" >' . ROOT_DIR . '/' . $page['url'] . '</a>';
  if (logged_in()) {
    print ' <a class="rounded_gray_button" href="' . ROOT_DIR . '/' . $page['url'] . '/delete" >X</a>';
  }
  print '</li>';
}
?>
</ul>
<?php include('templates/parts/footer.php'); ?>
</body>
</html>
