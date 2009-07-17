<div id="logo-outline" >
<div id="logo-center" >
<a href="<?=ROOT_DIR;?>"><img id="logo" src="<?=ROOT_DIR;?>/system/templates/parts/logo.png" /></a>

<?php if (logged_in()) { ?>
  <a class="rounded_gray_button" href="<?=ROOT_DIR;?>/logout">logout</a>
<?php } else { ?>
  <a class="rounded_gray_button" style="background: #ffba01;" href="<?=ROOT_DIR;?>/login">login</a>
<?php } ?>

</div>
</div>

<ul id="breadcrumbs">
  <li><a href="#">Susan Smith</a><img src="<?=ROOT_DIR;?>/system/templates/parts/breadcrumbs-divider.png" /></li>
  <li><a href="#">Poems</a><img src="<?=ROOT_DIR;?>/system/templates/parts/breadcrumbs-divider.png" />
  <li><a href="#">My Animal Series</a><img src="<?=ROOT_DIR;?>/system/templates/parts/breadcrumbs-divider.png" />
  <li><a href="#">Mammals</a><img src="<?=ROOT_DIR;?>/system/templates/parts/breadcrumbs-divider.png" />
  <li><a id="select-crumb" href="#">Squirrel</a><img src="<?=ROOT_DIR;?>/system/templates/parts/outline-breadcrumbs-divider.png" /></li>
</ul>

<div id="container" >
