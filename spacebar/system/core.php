<?php
include("funcs.php");

$url = $_GET['q'];
//Make pretty: "/url//blah///hi///" -> "url/blah/hi"
$url = preg_replace('/\/+/', '/', $url);
$url = preg_replace('/^\/+/', '', $url);
$url = preg_replace('/\/+$/', '', $url);

$splitpath = split("/", $url);
$name = $splitpath[count($splitpath)-1];

$db = db_connection();

if ($db->query("SELECT * FROM sqlite_master WHERE name='pages' LIMIT 1;")->numRows() < 1) {
  $db->query("CREATE TABLE pages (
              id INTEGER PRIMARY KEY,
              url TEXT,
              template TEXT);
              ", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

if ($db->query("SELECT * FROM sqlite_master WHERE name='blocks' LIMIT 1;")->numRows() < 1) {
  $db->query("CREATE TABLE blocks (
              id INTEGER PRIMARY KEY,
              name TEXT,
              pageid INTEGER,
              content TEXT);
              ", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

$page = get_page_by_url($db, $url);

if ($splitpath[0] == "login") {
  include("login.php");
} elseif ($splitpath[0] == "logout") {
  logout();
  header("Location: " . ROOT_DIR);
} elseif ($splitpath[0] == "sitemap") {
  include("sitemap.php");
} elseif ($name == "delete") {
  include("deletepage.php");
} elseif ($name == "reallydelete") {
  $splitpath = array_slice($splitpath, 0, -1);
  $url = implode('/', $splitpath);

  $page = get_page_by_url($db, $url);

  if ($page == '') {
    die("Page doesn't exist!");
  } else {
    delete_page($db, $page['id']);
    header("Location: .");
  }
} else {
  if ($page == '') {
    if (logged_in()) {
      include('newpage.php');
    } else {
      include('notfound.php');
    }
  } else {
    include("templates/" . $page['template']);
  }
}
?>
