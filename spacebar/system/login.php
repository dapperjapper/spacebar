<?php
//login("blah", "hi");

if (isset($_POST['username']) && isset($_POST['password'])) {
  login($_POST['username'], $_POST['password']);
  header("Location: login");
} else {
  if (logged_in()) {
    if ($_POST['action'] == "logout") {
      logout();
      header("Location: .");
    }
?>
<html>
<head>
</head>
<body>
<h1>Hello VIP.</h1>
<p>Your highness is currently logged in. Would you like to</p><form action="login" method="post"><input type="hidden" name="action" value="logout" /><input type="submit" value="logout?" /></form>
</body>
</html>
<?php
  } else {
?>
<html>
<head>
</head>
<body>
<h1>Login, you not-logged-in person!!!</h1>
<form action="login" method="post">
<input name="username" value="your name, sire" />
<input type="password" name="password" value="password" />
<input type="submit" value="Try me" />
</form>
</body>
</html>
<?php }} ?>
