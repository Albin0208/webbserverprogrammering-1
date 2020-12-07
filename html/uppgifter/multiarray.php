<?php

?>

<!DOCTYPE html>
<html lang="sv">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>multiarray</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
    }
  </style>
</head>

<body>
  <form action="multiarray.php" method="POST">
    <label for="textinput">Skriv en mening:</label> <br>
    <textarea type="textarea" name="textinput"></textarea> <br>
    <input type="submit" name="submit">
  </form>

  <?php
  //Ser till så att något är skrivet i textrutan och att submit knappen är tryck på
  if (!empty($_POST['submit']) && !empty($_POST['textinput'])) {
    $multiArray = [];

    $input = filter_input(INPUT_POST, 'textinput', FILTER_SANITIZE_STRING, FILTER_SANITIZE_ENCODED);
    //Ta bort whitespace före och efter strängen
    $input = trim($input);

    //Dela upp strängen i ord för ord
    $splitedString = explode(" ", $input);

    /* Gå igenom varje ord och dela upp det i chars,
       sortera sedan ordet alfabetiskt,
       avsluta med att lägga till i en array
    */
    foreach ($splitedString as $word) {
      //Ta bort allt från ordet som inte är bokstäver
      $cleanWord = preg_replace("/[^a-öA-Ö\s]/", "", $word);
      //Dela upp ordet i bokstäver
      $temp = mb_str_split(mb_strtolower($cleanWord));
      //Sortera bokstäverna alfabetiskt
      sort($temp);
      $multiArray += [$cleanWord => $temp];
    }

    //Skriv ut resultatet
    foreach ($multiArray as $word => $value) {
      echo "Bokstäverna i ordet <strong>{$word}</strong> sorterat i alfabetiskordning blir ";
      foreach ($value as $char) {
        echo "{$char}, ";
      }
      echo "<br>";
    }
  }
  ?>
</body>

</html>