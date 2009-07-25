<?php
//login("blah", "hi");

if (isset($_POST['username']) && isset($_POST['password'])) {
  login($_POST['username'], $_POST['password']);
  header("Location: login");
} else {
  if (logged_in()) {
?>
<html>
<head>
<?=all_head();?>
</head>
<body>
<?php include('templates/parts/header.php'); ?>
<h1>Hello VIP.</h1>
<p>Your highness is currently logged in. Would you like to <a href="logout" >logout?</a></p>
<p>You could also <a href="<?=ROOT_DIR;?>/" >go home</a>.</p>
<?php include('templates/parts/footer.php'); ?>
</body>
</html>
<?php
  } else {
?>
<html>
<head>
<?=all_head();?>
</head>
<body>
<?php include('templates/parts/header.php'); ?>
<h1>Login, you not-logged-in person!!!</h1>
<form action="login" method="post">
<input name="username" value="your name, sire" />
<input type="password" name="password" value="password" />
<input type="submit" value="Try me" />
</form>
<?php include('templates/parts/footer.php'); ?>
</body>
</html>
<?php }} ?>
