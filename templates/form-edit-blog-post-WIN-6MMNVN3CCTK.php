<?php

/**
 * Mall för redigering av blogginlägg
 */
?>
<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "{$blogpost['title']} - Läxhjälpens blogg"; ?></title>
  <link href='http://fonts.googleapis.com/css?family=Merienda+One' rel='stylesheet' />
  <link href="css/laxhjalpen.css" rel="stylesheet" />
</head>

<body class="subpage">

  <?php
  require "masthead.php";
  require "menu.php";

  ?>

  <div role="main">
    <form action="editblogpost.php" method="post" class="editblog">
      <input type="hidden" name="articlesID" value="{$blogpost['articlesID']" />
      <p>
        <label for="b_title">
          Inläggets rubrik
          <strong class="error">{$b_error['title']}</strong>
        </label>
        <input name="text" id="b_title" value="{$blogpost['title']}">
      </p>
      <p>
        <label for="b_title">
          Inläggets brödtext
          <strong class="error">{$b_error['text']}</strong>
        </label>
        <textarea name="text" id="b_text" value="{$blogpost['text']}"></textarea>
      </p>
      <!-- Saker som skapas automatisk av systemet -->
      <p>
        <small>Postat: <i>{$blogpost['pubdate']}</i> av
          <i>{$blogpost['username']}</i>
          Slug: <i>{$blogpost['slug']}</i>
        </small>
      </p>
      <p>
        <input type="submit" value="spara">
      </p>
    </form>
  </div>

</body>

</html>