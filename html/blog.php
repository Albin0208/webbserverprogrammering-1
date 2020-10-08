<?php

/**
 * Page controler för bloggfunktionen
 * 
 * Denna påbörjas i kapitel 4 och får sin slutliga form i kapitel 13
 */

// En array som innehåller tre fingerade inlägg
// Varje inlägg är en inre array
// $temporary = array(
//     'test-1' => array(
//         'slug' => 'test1',
//         'title' => 'Första testartikeln',
//         'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ultrices hendrerit massa eget feugiat. In ornare mauris dui, vitae vestibulum sapien commodo quis. Pellentesque lacinia faucibus orci in facilisis. Proin elementum ornare neque at scelerisque. Mauris eget congue nulla. Donec venenatis, turpis in pellentesque egestas, enim erat varius magna, id semper elit turpis vel elit. Ut blandit vitae mi ut viverra. In varius adipiscing augue a feugiat. Ut non fermentum purus, in faucibus est. Mauris eget porttitor dui, a congue odio. Nulla placerat gravida mi, sed cursus felis accumsan lacinia. Nulla facilisis purus ut leo semper, et euismod nibh congue. Proin fringilla orci quis rhoncus laoreet. Morbi malesuada quam vel risus tincidunt ultricies. Integer feugiat laoreet lacus, at lacinia nulla rutrum id.',
//         'username' => 'mia',
//         'pubdate' => '2013-06-12 19:41.50'
//     ),
//     'pelles-forsta-artikel' => array(
//         'slug' => 'pelles-forsta-artikel',
//         'title' => 'Pelles första artikel',
//         'text' => 'Donec placerat sapien eu facilisis pellentesque. Integer congue, nunc eget interdum cursus, nunc nisl tincidunt velit, nec accumsan felis metus eu erat. Maecenas gravida volutpat dignissim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur scelerisque porta viverra. Donec suscipit ac ligula a tincidunt. Suspendisse tristique enim dui, a aliquet arcu blandit commodo. Aenean eu semper dolor. Vivamus adipiscing lectus justo, vel mollis lorem varius quis. Curabitur cursus ut elit in sollicitudin. Donec eu accumsan orci, nec sodales arcu. Morbi bibendum quam mi, ut auctor erat hendrerit nec. In sed metus libero. Integer lobortis est magna, eget vestibulum diam malesuada non.',
//         'username' => 'pelle',
//         'pubdate' => '2013-06-13 19:41.53'
//     ),
//     'petra-tycker-till' => array(
//         'slug' => ' petra-tycker-till ',
//         'title' => 'Petra tycker till',
//         'text' => 'Nullam faucibus diam tellus, vel feugiat diam lacinia vitae. Mauris eros turpis, porttitor et nisl vitae, laoreet fringilla orci. In elementum sem nec gravida accumsan. In hac habitasse platea dictumst. Nullam bibendum, magna a ullamcorper tincidunt, nulla ante lacinia augue, eget venenatis sem felis et nibh. Nunc elementum velit sed eros porta tristique. Duis sapien mi, commodo vel sem vitae, consectetur sodales erat. Donec a ultrices nibh. Praesent bibendum nulla sed elit egestas facilisis. Morbi vel dignissim orci, ac rutrum est.',
//         'username' => 'petra',
//         'pubdate' => '2013-06-15 19:41.53'
//     )
// );

header("Content-type: text/html; charset: utf-8");
$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_URL);
$h1span = "Blogg";

require "../includes/setting.php";
require "../includes/global.inc.php";
$dbh = get_dbh();

if (empty($slug)) {
    // $sql = "SELECT * FROM articles ORDER BY pubdate DESC LIMIT 0,5";
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
    // $sql = "SELECT * FROM articles WHERE slug =:slug";
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

// if (empty($slug)) {
    //     // echo "<h1>De senaste inläggen</h1>";
    //     // foreach ($temporary as $slug => $blogpost) {
        //     //     echo "<h2><a href='blog.php?slug={$slug}'>{$blogpost['title']}</h2>\n";
        //     // }
        //     $template = "list-blog-posts";
        // } elseif (array_key_exists($slug, $temporary)) {
            //     // echo "<h1>{$temporary[$slug]['title']}</h1>\n";
            //     $blogpost = $temporary[$slug];
//     $template = 'single-blog-post';
// } else {
//     header("HTTP/1.0 404 Not Found");
//     $template = 'not-found';
// }
