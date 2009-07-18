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
<?php
$tempsplitpath = $splitpath;
if ($splitpath != array("")) {
  array_unshift($tempsplitpath, "");
}
foreach ($tempsplitpath as $key => $crumb) {
  $crumbsplitpath = array_slice($splitpath, 0, $key);
  $crumburl = implode("/", $crumbsplitpath);
  $crumbpage = get_page_by_url($db, $crumburl);
  $crumbtitle = get_block_data($db, $crumbpage['id'], "title", false);
  echo '<li><a href="' . ROOT_DIR . '/' . $crumburl . '">' . $crumbtitle . '</a><img src="' . ROOT_DIR . '/system/templates/parts/breadcrumbs-divider.png" /></li>';
}
?>
<li><a id="select-crumb" href="#">Select...</a><img src="<?=ROOT_DIR;?>/system/templates/parts/outline-breadcrumbs-divider.png" />
<select id="select" onchange="if (this.value=='new') { $('#select-crumb').html('New...'); $('#newpagespan').show(); } else if (this.value=='select') { $('#select-crumb').html('Select...'); $('#newpagespan').hide(); } else { window.location='<?=ROOT_DIR;?>/'+this.value; }" style="position: fixed; opacity: 0; margin-top: 6px; margin-left: -105px;" >
<option value="select" selected >Select...</option>
<option value="new" >New...</option>
<?php
$subpages = get_subpages($db, $page['url']);
foreach ($subpages as $subpage) {
  $pagetitle = get_block_data($db, $subpage['id'], "title", false);
  echo '<option value="' . $subpage['url'] . '" >' . $pagetitle . '</option>';
}
?>
</select>
<span style="display: none;" id="newpagespan" >
<input style="margin-left: 20px;" id="newpagetextfield" />
<button onclick="window.location = '<?=ROOT_DIR;?>/<?php if ($url!="") { echo $url . "/"; }?>'+$('#newpagetextfield').val();" >Go</button>
</span>
</li>
</ul>

<div id="container" >
