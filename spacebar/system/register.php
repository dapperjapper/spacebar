<?php
if ($_POST['username'] and $_POST['password']) {
  $basepage = get_page_by_url($db, $_POST['username']);
  $userpage = get_page_by_url($db, "members/" . $_POST['username']);
  $taken = array('login', 'logout', 'sitemap', 'register', 'delete', 'reallydelete', LOGIN_USERNAME);
  if ($basepage == '' and $userpage == '' and !in_array($_POST['username'], $taken)) {
    if(file_get_contents("http://www.opencaptcha.com/validate.php?ans=".$_POST['captcha']."&img=".$_POST['captcharand'])=='pass') {
      create_page($db, "members/" . $_POST['username'], "user.php");
      $page = get_page_by_url($db, "members/" . $_POST['username']);
      change_block($db, $page['id'], 'password', $_POST['password']);
      login($_POST['username'], $_POST['password']);
      header('Location: login');
    } else {
      $error = 'The captcha text was not correct.';
    }
  } else {
    $error = 'Username already taken.';
  }
}
$captcharand = $_SERVER['SERVER_NAME'] . time();
?>
<html>
<head>
<?=all_head();?>
<title>Register</title>
</head>
<body>
<?php include('templates/parts/header.php'); ?>
<h1>Get an account!</h1>
<?php if ($error) { ?>
<div class="error" ><?=$error;?></div>
<?php } ?>
<form action="register" method="post" >
<input onfocus="this.value='';" type="text" name="username" value="<?php if ($_POST['username']) { print $_POST['username']; } else { print 'username'; }?>" /><br/>
<input onfocus="this.value='';" type="password" name="password" value="<?php if ($_POST['password']) { print $_POST['password']; } else { print 'password'; }?>" /><br/>
<img src="http://www.opencaptcha.com/img/<?=$captcharand?>.jpg" class="captcha" /><br/>
<input onfocus="this.value='';" type="text" name="captcha" value="enter the text above" /><br/>
<input type="hidden" name="captcharand" value="<?=$captcharand?>" />
<input type="submit" value="Register" />
</form>
<?php include('templates/parts/footer.php'); ?>
</body>
</html>
