<?php
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "{$blogpost['title']}" ?> - Läxhjälpen</title>
    <link href='http://fonts.googleapis.com/css?family=Merienda+One' rel='stylesheet' />
    <link href="css/laxhjalpen.css" rel="stylesheet" />
</head>

<body class="subpage">
    <?php
    require "masthead.php";
    require "menu.php";

    echo <<<MAIN
    <div role="main">
        <article class="singleblogpost">
            <h2>{$blogpost['title']}</h2>
            <p><small>Postad {$blogpost['pubdate']} av 
            {$blogpost['author']}</small></p>
            <div class="blogtext">
            {$blogpost['text']}
            </div>
    </article>
    </div>
    MAIN;

    foreach ($comments as $cmt) {
        echo <<<CMT
            <article class="comment">
                <h4><a class="c_name" rel="nofollow" href="{$cmt['website']}">{$cmt['name']}</a> säger:</h4>
                <p class="c_time"><small>{$cmt['ctime']}</small></p>
                <div class="c_text">
                    {$cmt['text']}
                </div>
            </article>
            CMT;
        if (empty($comments)) {
            echo "<p>Inga kommentarer ännu</p>\n";
        }
    }

    require "footer.php";
    ?>
</body>

</html>