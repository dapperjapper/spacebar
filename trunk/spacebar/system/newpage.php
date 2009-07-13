<?php
if ($_POST["template"]) {
  create_page($db, $url, $_POST['template']);
  header("Location: ./" . $name);
} else {
?>
<html>
<head>
</head>
<body>
<h1>Make new page "<?php if ($name == "") {print "index";} else {print $name;} ?>"?</h1>
Select a template
<form action="<?=ROOT_DIR;?>/<?=$url;?>" method="post">
<select name="template">
<?php
foreach (get_templates() as $template) {
  print("<option>" . $template . "</option>");
}
?>
</select>
<input type="submit" value="and go!" />
</form>
</body>
</html>
<?php } ?>
