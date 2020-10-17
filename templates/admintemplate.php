<?php

/**
 * Mall för adminsidan
 * 
 * De olika tillstånden sidan kan ha sköts med extramallar
 */
?>

<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $admintitle; ?></title>
  <link href='http://fonts.googleapis.com/css?family=Merienda+One' rel='stylesheet' />
  <link rel="stylesheet" href="css/laxhjalpen.css">
</head>

<body class="subpage">
  <?php
  require "masthead.php";
  require "menu.php";

  require "{$adminbody}.php";

  require "footer.php";

  ?>
</body>

</html>