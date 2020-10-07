<?php

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordcount PHP</title>
</head>

<body>
    <form method="post">
        <label for="nameInput">Skriv en mening och få reda på hur många ord den innehåller</label>
        <input type="text" name="word" id="wordInput">
        <input type="submit" name="submit" value="Calculate">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        if (isset($_POST['word'])) {
            $word = $_POST['word'];
            echo "Meningen \"" . $word . "\" innehåller " . str_word_count($word) . " ord";
        }
    }

    function FixString($word)
    {
        for ($i = 0; $i < strlen($word) - 1; $i++) {
            if ($word[$i] == "å" || "ä" || "ö") {
                # code...
            }
        }
    }
    ?>
</body>

</html>