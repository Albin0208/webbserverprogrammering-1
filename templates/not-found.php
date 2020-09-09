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
    ?>

    <div role="main">
        <h2>Sidan finns inte (404)</h2>
        <p>DU verkar ha angivit en ogiltig URL. Navigera vidare i menyn -> till vänster</p>
    </div>
    <?php
    require "footer.php";
    ?>
</body>

</html>