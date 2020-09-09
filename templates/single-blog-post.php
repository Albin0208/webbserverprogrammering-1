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
            {$blogpost['username']}</small></p>
            <div class="blogtext">
            {$blogpost['text']}
            </div>
    </article>
    </div>
    MAIN;

    require "footer.php";
    ?>
</body>

</html>