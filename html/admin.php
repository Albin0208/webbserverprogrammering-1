<?php

/**
 * Page controler för adminsidan
 * 
 * Denna ska skapas i kapitel 15
 */

session_start();

require "../includes/setting.php";
require "../includes/global.inc.php";
require "../includes/user.php";
require "../includes/password.php";

$dbh = get_dbh();

if (empty($_SESSION['username']) && empty($_POST)) {
  //Visa blankt innlogningsformulär
  $h1span = "Logga in";
  $admintitle = "Logga in som admin på läxhjälpen";
  $adminbody = "loginform";
  $login_username = "";
  $login_errormsg = "";
  // exit;
} elseif (empty($_SESSION['username'])) {
  //Kontrollera om inloggning lyckats
  $username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $login_ok = users::verify_login($username, $password, $dbh, $errormsg);

  if ($login_ok) {
    session_regenerate_id(true);
    $_SESSION['username'] = $username;

    if (isset($_SESSION['referer'])) {
      //Kopierar till en icke-sessionsvariabel
      $referer = $_SESSION['referer'];

      //Ta bort sessionsvariabel så den inte följer i framtiden
      unset($_SESSION['referer']);

      header("Location: {$referer}");
      exit; //Viktigt att stoppa exekveringen efter omdirigering
    }

    $h1span = "Administration";
    $admintitle = "Administration av Laxhjälpen";
    $adminbody = "adminpanel";
  } else {
    $h1span = "Logga in";
    $admintitle = "Logga in som admin på läxhjälpen";
    $adminbody = "loginform";
    $login_username = htmlspecialchars($username, ENT_QUOTES);
    $login_errormsg = "<p class=\"error\">Inloggning misslyckades. Orsak: {$errormsg}</p>";
  }
  // exit;
} elseif (empty($_POST)) {
  echo "<h2>Inloggad som {$_SESSION['username']}</h2>";
  echo "<p><a href=\"logout.php\">Logga ut</p>";
  // exit;
} else {

  if (isset($_POST['new_password'])) {

    exit("Del 4 ej skriven");
  } else {
    echo "<h2>Inloggad som {$_SESSION['username']}</h2>";
    echo "<p><a href=\"logout.php\">Logga ut</p>";
    // exit;
  }
}

header("Content-type: text/html; charset=utf-8");
require "../templates/admintemplate.php";
