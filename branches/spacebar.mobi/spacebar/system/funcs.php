<?php
include("textile.php");
include("config.php");
session_start();

function logged_in ($db, $url = "") {
  $who = logged_in_as($db);
  if ($who === false) {
    return false;
  } elseif ($who === true) {
    return true;
  } else {
    $splitpath = split("/", $url);
    return ($who == $splitpath[0]) or (($who == $splitpath[1]) and ($splitpath[0] == "members"));
  }
}

function logged_in_as ($db) {
  $adminusername = LOGIN_USERNAME;
  $adminpassword = LOGIN_PASSWORD;
  $username = $_SESSION["username"];
  $password = $_SESSION["password"];
  if ($username==$adminusername and $password==$adminpassword) {
    return true;
  } else {
    $page = get_page_by_url($db, "members/" . $username);
    if ($page != "" and $password==get_block_data($db, $page['id'], "password", false)) {
      return $username;
    } else {
      return false;
    }
  }
}

function login ($username, $password) {
  $_SESSION["username"] = $username;
  $_SESSION["password"] = $password;
}

function logout () {
  unset($_SESSION["username"]);
  unset($_SESSION["password"]);
}

function db_connection () {
  $connection = new SQLiteDatabase("db.sqlite", 0666, $sqlerror) or die($sqlerror);
  return $connection;
}

function get_templates () {
  $list = scandir("templates");
  //Remove ., .., .svn, and parts from the list.
  return array_diff($list, array('.', '..', '.svn', 'parts'));
}

function all_head () {
  $toreturn .= '<link rel="icon" type="image/vnd.microsoft.icon" href="' . ROOT_DIR . '/favicon.ico" />
                <meta name="viewport" content="width=device-width, user-scalable=no" />
                <link rel="apple-touch-icon" href="' . ROOT_DIR . '/system/templates/parts/web-clip.png"/>
                <link rel="apple-touch-startup-image" href="' . ROOT_DIR . '/system/templates/parts/startup.png">
                <meta name="apple-mobile-web-app-capable" content="yes" />
                <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.js" ></script>
                <link rel="stylesheet" type="text/css" href="' . ROOT_DIR . '/system/templates/parts/style.css" />';

  if (is_iphone()) {
    $toreturn .= '<link rel="stylesheet" type="text/css" href="' . ROOT_DIR . '/system/templates/parts/iphone.css" />';
  }

  return $toreturn;
}

function template_head ($db, $page) {
  $toreturn .= all_head();
  if (logged_in($db, $page['url'])) {
    $toreturn .= '<script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.jeditable.js" ></script>
                  <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.jeditable.autogrow.js" ></script>
                  <script type="text/javascript" src="' . ROOT_DIR . '/system/jquery.autogrow.js" ></script>
                  <link type="text/css" rel="stylesheet" href="' . ROOT_DIR . '/system/logged-in.css" />
                  <script type="text/javascript" >
                  $(document).ready(function() {
                    $(".editable").editable("' . ROOT_DIR . '/system/edit.php", {
                      submitdata: {pageid: "' . $page['id'] . '", textile: "yes"},
                      loaddata: {pageid: "' . $page['id'] . '"},
                      loadurl: "' . ROOT_DIR . '/system/load.php",
                      type: "autogrow",
                      cancel: "Cancel",
                      submit: "OK",
                      indicator: "Saving...",
                      tooltip: "Double click to edit...",
                      event: "dblclick",
                      onblur: "submit",
                      autogrow: {
                        lineHeight: 16,
                        minHeight: 32
                      }
                    });
                    $(".editable-notextile").editable("' . ROOT_DIR . '/system/edit.php", {
                      submitdata: {pageid: "' . $page['id'] . '"},
                      loaddata: {pageid: "' . $page['id'] . '"},
                      loadurl: "' . ROOT_DIR . '/system/load.php",
                      type: "autogrow",
                      cancel: "Cancel",
                      submit: "OK",
                      indicator: "Saving...",
                      tooltip: "Double click to edit...",
                      event: "dblclick",
                      onblur: "submit",
                      autogrow: {
                        lineHeight: 16,
                        minHeight: 32
                      }
                    });
                  });
                  </script>';
  }
  return $toreturn;
}

function block ($db, $pageid, $blockid, $tag = "div", $textile = true, $content = null, $default = "", $edit_link = true) {
  //If you use a block multiple times in a template (Example: title in <title> and <h1> tags),
  //be sure to only request it once from the db. Save it to a variable, and use it as the 6th argument to this function.
  //Clunky, but if the content default was "", empty content spaces wouldn't save any db requests.
  if ($content == null) {
    $content = get_block_data($db, $pageid, $blockid, $textile, $default);
  }
  if ($edit_link) {
    $to_return .= edit_link($db, $pageid, $blockid, "Edit " . $blockid);
  }
  if ($textile) {
    $to_return .= '<' . $tag . ' class="editable" id="' . $blockid . '" >' . $content . '</' . $tag . '>';
  } else {
    $to_return .= '<' . $tag . ' class="editable-notextile" id="' . $blockid . '" >' . $content . '</' . $tag . '>';
  }

  return $to_return;
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

function get_subpages ($db, $pageurl, $depth=0, $limit=-1, $offset=0) {
  //FIXME limit doesn't work if depth is not -1
  if ($pageurl == "") {
    $result = $db->query("SELECT * FROM pages WHERE url != '' LIMIT " . $limit . " OFFSET " . $offset . ";");
  } else {
    $result = $db->query("SELECT * FROM pages WHERE url GLOB '" . $pageurl . "/*' LIMIT " . $limit . " OFFSET " . $offset . ";");
  }
  $pages = $result->fetchAll();
  if ($pageurl == "") {
    $basedepth = 0;
  } else {
    $basedepth = count(split('/', $pageurl));
  }
  if ($depth == -1) {
    return $pages;
  } else {
    $filteredpages = array();
    foreach ($pages as $page) {
      if (count(split('/', $page['url'])) < $basedepth+($depth+2)) {
        $filteredpages[] = $page;
      }
    }
    return $filteredpages;
  }
}

function create_page ($db, $url, $template) {
  $db->query("INSERT INTO pages (url, template) VALUES ('" . sqlite_escape_string($url) . "', '" . sqlite_escape_string($template) . "')", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

function delete_page ($db, $pageid) {
  $db->query("DELETE FROM pages WHERE id=" . sqlite_escape_string($pageid) . ";", SQLITE_BOTH, $sqlerror) or die($sqlerror);
  $db->query("DELETE FROM blocks WHERE pageid=" . sqlite_escape_string($pageid) . ";", SQLITE_BOTH, $sqlerror) or die($sqlerror);
}

function edit_link ($db, $pageid, $blockid, $text = "Edit", $noscript = true) {
  $result = "";

  if (is_iphone()) {
    $result .= '<a class="edit-tab" onclick="$(\'#' . $blockid . '\').dblclick();" >' . $text . '</a>';
  }
  if ($noscript) {
    $result .= '<noscript>';
  }
  $result .= '<a href="' . ROOT_DIR . '/system/edit.php?id=' . $blockid . '&pageid=' . $pageid . '" >' . $text . '</a>';
  if ($noscript) {
    $result .= '</noscript>';
  }

  $page = get_page_by_id($db, $pageid);
  if (logged_in($db, $page['url'])) {
    return $result;
  }
}

function is_iphone () {
  return (stripos($_SERVER['HTTP_USER_AGENT'], "iphone") != false) or (stripos($_SERVER['HTTP_USER_AGENT'], "ipod") != false);
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
