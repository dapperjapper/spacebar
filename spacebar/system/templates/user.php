<html>
<head>
<?=template_head($db, $page);?>
<title><?=$name;?></title>
</head>
<body>
<?php include('parts/header.php'); ?>
Username: <?=$name?><br/>
Password: <?=block($db, $page['id'], "password", "div", false);?>
<?php include('parts/footer.php'); ?>
</body>
</html>
