<?php
session_start();

if (empty($_SESSION['username'])) {
  $_SESSION['referer'] = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
  header("Location: admin.php");
}

require "../includes/setting.php";
require "../includes/global.inc.php";
require "../includes/articles.php";

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

  $blogpost = Articles::fetch($b_id, $dbh);
  // $blogpost = $blogpost->asArray();

  if (empty($blogpost)) {
    //Finns inget sådant inlägg
    //Provisorisk felhantering = dålig felhantering = ej klart
    exit("<h1>Det finns inget bloginlägg med id {$b_id}</h1>\n");
  }
} else {
  //Grundläggande sanering
  $a_id = filter_input(INPUT_POST, 'articlesID', FILTER_SANITIZE_NUMBER_INT);
  $slug = filter_input(INPUT_POST, 'slug', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $title = filter_input(INPUT_POST, 'title', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $text = filter_input(INPUT_POST, 'text', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $text = str_replace("\n", "_NEWLINE_", $text);
  $text = filter_var($text, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
  $text = str_replace("_NEWLINE_", "\n", $text);

  $article = new Articles($title, $text, $_SESSION['username'], $dbh, $a_id);

  //Töm variabler för att förhindrea oönskad användning
  $_POST = array();
  unset($a_id);
  unset($slug);
  unset($title);
  unset($text);

  if ($article->validate()) {
    $slug = $article->getSlug();
    //Kolla om en slug kunde skapas/hämtas
    if ($slug) {
      $article->save();
      header("Location: blog.php?slug={$slug}");
      exit;
    }
  }

  //Kommer vi hit är det fel på indata
  $b_error = $article->getErrorMessages();
  $blogpost['title'] = $article->$title;
  $blogpost['text'] = $article->$text;

  //Automatisk skapad data hämtas ur DB vid uppdatering
  //för att användas ihop med formuläret
  if ($article->articlesID) {
    //Återanvänds variabeln $article
    $article = articles::fetch($article->articlesID, $dbh);
    $blogpost['articlesID'] = $article->articlesID;
    $blogpost['slug'] = $article->slug;
    $blogpost['username'] = $article->username;
    $blogpost['pubdate'] = $article->pubdate;
  } else {
    $blogpost['articlesID'] = "";
    $blogpost['slug'] = "(skapas automatiskt)";
    $blogpost['username'] = $_SESSION['username'];
    $blogpost['pubdate'] = "snart (nytt inlägg)";
  }
}

//Formelementet bör alltid ha escapad HTML-kod
$blogpost['title'] = htmlspecialchars($blogpost['title'], ENT_QUOTES);
$blogpost['slug'] = htmlspecialchars($blogpost['slug'], ENT_QUOTES);
$blogpost['text'] = htmlspecialchars($blogpost['text'], ENT_QUOTES);
$blogpost['username'] = htmlspecialchars($blogpost['username'], ENT_QUOTES);

$h1span = "Redigera blogginlägg";

header("Content-type: text/html; charset=utf-8");
require "../templates/form-edit-blog-post.php";
