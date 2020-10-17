<?php

/**
 * Page controler för adminsidan
 * 
 * Denna ska skapas i kapitel 15
 */

session_start();

require "../includes/setting.php";
require "../includes/global.inc.php";

$dbh = get_dbh();

if (empty($_SESSION['username'] && empty($_POST))) {
  //Visa blankt innlogningsformulär
  $h1span = "Logga in";
  $admintitle = "Logga in som admin på läxhjälpen";
  $adminbody = "loginform";
  $login_username = "";
  $login_errormsg = "";
  exit;
} elseif (empty($_SESSION['username'])) {

  exit;
} elseif (empty($_POST)) {

  exit;
} else {

  if (isset($_POST['new_password'])) {

    exit;
  } else {

    exit;
  }
}

header("Content-type: text/html; charset=utf-8");
require "../templates/admintemplate.php";
