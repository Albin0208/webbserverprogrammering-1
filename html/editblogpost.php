<?php
session_start();

if (empty($_SESSION['username'])) {
  $_SESSION['referer'] = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
  header("Location: admin.php");
}

require "../includes/setting.php";
require "../includes/global.inc.php";
// require "../includes/articles.php";

$dbh = get_dbh();

//Felmeddelande
$b_error['title'] = "";
$b_error['text'] = "";

if (empty($_GET['id']) && empty($_POST)) {
  //Ett nytt inlägg skrivs, sätt defaultvärden
  $blogpost['articlesID'] = "";
  $blogpost['slug'] = "(skapas automatiskt)";
  $blogpost['title'] = "";
  $blogpost['text'] = "";
  $blogpost['username'] = $_SESSION['username'];
  $blogpost['pubdate'] = "snart (nytt inlägg)";
} elseif (empty($_POST)) {
  //Ett befintligt inlägg ska redigeras
  //I framtiden flyttar vi denna DB-anropande kod till en klass
  $b_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
  // $stmt = $dbh->prepare("SELECT * FROM articles WHERE articlesID = :id");
  // $stmt->bindParam(":id", $b_id);
  // $stmt->execute();
  // $blogpost = $stmt->fetch();

  $blogpost = Articles::fetch($b_id, $dbh);
  $blogpost = $blogpost->asArray();

  if (empty($blogpost)) {
    //Finns inget sådant inlägg
    //Provisorisk felhantering = dålig felhantering = ej klart
    exit("<h1>Det finns inget bloginlägg med id {$b_id}</h1>\n");
  }
} else {
  //Här ska det ske kontroller och lagring i DB
  exit("<h1>Kontroller och lagring ej klara ännu</h1>");
}

//Formelementet bör alltid ha escapad HTML-kod
$blogpost['title'] = htmlspecialchars($blogpost['title'], ENT_QUOTES);
$blogpost['slug'] = htmlspecialchars($blogpost['slug'], ENT_QUOTES);
$blogpost['text'] = htmlspecialchars($blogpost['text'], ENT_QUOTES);
$blogpost['username'] = htmlspecialchars($blogpost['username'], ENT_QUOTES);

$h1span = "Redigera blogginlägg";

header("Content-type: text/html; charset=utf-8");
require "../templates/form-edit-blog-post.php";
