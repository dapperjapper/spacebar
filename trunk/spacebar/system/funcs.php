<?php
include("textile.php");
include("config.php");

function logged_in () {
  $realusername = LOGIN_USERNAME;
  $realpassword = LOGIN_PASSWORD;
  $username = $_COOKIE["username"];
  $password = $_COOKIE["password"];
  return($username==$realusername and $password==$realpassword);
}

function login ($username, $password) {
  setcookie("username", $username);
  setcookie("password", $password);
}

function logout () {
  setcookie("username");
  setcookie("password");
}

function db_connection () {
  $connection = new SQLiteDatabase("db.sqlite", 0666, $sqlerror) or die($sqlerror);
  return $connection;
}

function get_templates () {
  $list = scandir("templates");
  //Remove . and .. from the list.
  return array_diff($list, array('.', '..'));
}

function template_head ($pageid, $editableclass = "editable") {
  if (logged_in()) {
    return '<script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.js" ></script>
            <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.jeditable.js" ></script>
            <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.jeditable.autogrow.js" ></script>
            <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.autogrow.js" ></script>
            <script type="text/javascript" >
            $(document).ready(function() {
              $(".' . $editableclass . '").editable("' . ROOT_DIR . '/system/edit.php", {
                submitdata: {pageid: "' . $pageid . '"},
                loaddata: {pageid: "' . $pageid . '"},
                loadurl: "' . ROOT_DIR . '/system/load.php",
                type: "autogrow",
                cancel: "Cancel",
                submit: "OK",
                indicator: "Saving...",
                tooltip: "Double click to edit...",
                event: "dblclick",
                onblur: "ignore",
                autogrow: {
                  lineHeight: 16,
                  minHeight: 32
                }
              });
            });
            </script>';
  }
}

function get_block_data ($db, $pageid, $blockid, $textile = true, $default = "") {
  $result = get_block ($db, $pageid, $blockid);
  if ($result['content'] == "") {
    $content = $default;
  } else {
    $content = $result['content'];
  }
  $content = stripslashes($content);
  if ($textile) {
    $content = textile($content);
  }
  return $content;
}

function get_block ($db, $pageid, $blockid) {
  $result = $db->query("SELECT * FROM blocks WHERE name='" . $blockid . "' AND pageid='" . $pageid . "' LIMIT 1;", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  if ($result->numRows() > 0) {
    return $result->fetch();
  } else {
    return "";
  }
}

function change_block ($db, $pageid, $blockid, $newcontent) {
  $result = $db->query("SELECT id FROM blocks WHERE name='" . sqlite_escape_string($blockid) . "' AND pageid='" . sqlite_escape_string($pageid) . "' LIMIT 1;", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  if ($result->numRows() > 0) {
    $result = $result->fetch();
    $db->query("UPDATE blocks SET content='" . sqlite_escape_string($newcontent) . "' WHERE id='" . sqlite_escape_string($result['id']) . "';", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  } else {
    $db->query("INSERT INTO blocks (content, name, pageid) VALUES ('" . sqlite_escape_string($newcontent) . "', '" . sqlite_escape_string($blockid) . "', '" . sqlite_escape_string($pageid) . "');", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  }
}

function get_page_by_id ($db, $pageid) {
  $result = $db->query("SELECT * FROM pages WHERE id='" . sqlite_escape_string($pageid) . "' LIMIT 1;", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  if ($result->numRows() > 0) {
    return $result->fetch();
  } else {
    return "";
  }
}

function get_page_by_url ($db, $pageurl) {
  $result = $db->query("SELECT * FROM pages WHERE url='" . sqlite_escape_string($pageurl) . "' LIMIT 1;", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  if ($result->numRows() > 0) {
    return $result->fetch();
  } else {
    return "";
  }
}

function get_all_pages ($db) {
  $result = $db->query("SELECT * FROM pages;");
  return $result->fetchAll();
}

function get_subpages ($db, $pageurl, $limit=-1, $offset=0) {
  $result = $db->query("SELECT * FROM pages WHERE url GLOB '" . $pageurl . "/*' LIMIT " . $limit . " OFFSET " . $offset . ";");
  return $result->fetchAll();
}

function create_page ($db, $url, $template) {
  $db->query("INSERT INTO pages (url, template) VALUES ('" . sqlite_escape_string($url) . "', '" . sqlite_escape_string($_POST['template']) . "')", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

function delete_page ($db, $pageid) {
  $db->query("DELETE FROM pages WHERE id=" . sqlite_escape_string($pageid) . ";", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  $db->query("DELETE FROM blocks WHERE pageid=" . sqlite_escape_string($pageid) . ";", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

function edit_link ($pageid, $blockid, $text = "Edit", $noscript = true) {
  $result = "";

  if ($noscript) {
    $result .= '<noscript>';
  }
  $result .= '<a href="' . ROOT_DIR . '/system/edit.php?id=' . $blockid . '&pageid=' . $pageid . '" >' . $text . '</a>';
  if ($noscript) {
    $result .= '</noscript>';
  }

  return $result;
}

function textile ($in, $safe = true) {
  $textile = new Textile();
  if ($safe) {
    return $textile->TextileThis($in);
  } else {
    return $textile->TextileRestricted($in);
  }
}
?>
