<?php

session_start();

/**
 * Page controler för bloggfunktionen
 * 
 * Denna påbörjas i kapitel 4 och får sin slutliga form i kapitel 13
 */
header("Content-type: text/html; charset: utf-8");
$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_URL);
$h1span = "Blogg";

require "../includes/setting.php";
require "../includes/global.inc.php";
$dbh = get_dbh();

if (empty($slug)) {
    $sql = <<<SQL
        SELECT a . *, CONCAT(u.firstname, ' ', u.lastname) AS author
        FROM articles AS a
        NATURAL JOIN users AS u
        ORDER BY a.pubdate DESC
        LIMIT 0 , 5
        SQL;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $template = "list-blog-posts";
} else {
    $sql = <<<SQL
        SELECT a . *, CONCAT(u.firstname, ' ', u.lastname) AS author
        FROM articles AS a
        NATURAL JOIN users AS u
        WHERE slug =:slug
        SQL;
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":slug", $slug);
    $stmt->execute();
    $blogpost = $stmt->fetch();
    if (empty($blogpost)) {
        header("HTTP/1.0 404 Not Found");
        $template = "not-found";
    } else {
        $comments = fetch_blog_comments($blogpost['articlesID'], $dbh);
        $template = 'single-blog-post';
    }
}

require "../templates/{$template}.php";
