<div id="logo-outline" >
<div id="logo-center" >
<a href="<?=ROOT_DIR;?>"><img id="logo" src="<?=ROOT_DIR;?>/system/templates/parts/logo.png" /></a>

<?php
if (logged_in_as($db) != false) {
  print '<a class="rounded_gray_button" href="' . ROOT_DIR . '/logout">logout</a>';
} else {
  print '<a class="rounded_gray_button" style="background-color: #ffba01;" href="' . ROOT_DIR . '/login">login</a>';
} ?>

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
  $crumbtitle = get_block_data($db, $crumbpage['id'], "title", false, ucwords($crumbsplitpath[count($crumbsplitpath)-1]));
  echo '<li><a href="' . ROOT_DIR . '/' . $crumburl . '">' . $crumbtitle . '</a><img src="' . ROOT_DIR . '/system/templates/parts/breadcrumbs-divider.png" /></li>';
}
?>
<li><a id="select-crumb" href="#">Select...</a><img src="<?=ROOT_DIR;?>/system/templates/parts/outline-breadcrumbs-divider.png" />
<select id="select" onchange="if (this.value=='new') { $('#select-crumb').html('New...'); $('#newpagespan').show(); } else if (this.value=='del') { window.location='<?=ROOT_DIR;?>/<?php if ($url!="") { echo $url . "/"; }?>delete' } else if (this.value=='sel') { $('#select-crumb').html('Select...'); $('#newpagespan').hide(); } else { window.location='<?=ROOT_DIR;?>/'+this.value; }" style="position: fixed; opacity: 0; margin-top: 6px; margin-left: -105px; -webkit-tap-highlight-color:rgba(0,0,0,0);" >
<option value="sel" selected >Select...</option>
<?php if (logged_in($db, $url)) { ?>
<option value="new" >New...</option>
<option value="del" >&lt;-Delete</option>
<?php
}
$subpages = get_subpages($db, $page['url']);
foreach ($subpages as $subpage) {
  $subpagesplitpath = split('/', $subpage['url']);
  $pagetitle = get_block_data($db, $subpage['id'], "title", false, ucwords($subpagesplitpath[count($subpagesplitpath)-1]));
  echo '<option value="' . $subpage['url'] . '" >' . $pagetitle . '</option>';
}
?>
</select>
<?php if (logged_in($db, $url)) { ?>
<span style="display: none;" id="newpagespan" >
<input style="margin-left: 20px;" id="newpagetextfield" autocapitalize="off" />
<button onclick="window.location = '<?=ROOT_DIR;?>/<?php if ($url!="") { echo $url . "/"; }?>'+$('#newpagetextfield').val();" >Go</button>
</span>
<?php } ?>
</li>
</ul>

<div id="container" >
