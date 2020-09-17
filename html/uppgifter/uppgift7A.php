<?php

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uppgift 7A</title>
</head>

<body>
    <form action="uppgift7A.php" method="GET">
        <label for="ord1">Skriv ord 1</label>
        <input type="text" name="ord1" id="textInput">
        <label for="ord2">Skriv ord 2</label>
        <input type="text" name="ord2" id="textInput">
        <label for="ord3">Skriv ord 3</label>
        <input type="text" name="ord3" id="textInput">
        <button type="submit" name="Submit">Submit</button>
    </form>
    <?php
    if (isset($_GET['Submit'])) {
        //Hämtar ordet och tar bort om det finns något av de 32 första tecken i ASCII-tabellen med FILTER_FLAG_STRIP_LOW,
        //Tar bort alla html taggar för att undvika html injektion med FILTER_SANITIZE_STRING,
        //Tar bort alla whitespace före och efter ordet med trim()
        $ord1 = trim(filter_input(INPUT_GET, 'ord1', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
        $ord2 = trim(filter_input(INPUT_GET, 'ord2', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
        $ord3 = trim(filter_input(INPUT_GET, 'ord3', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));

        echo "ordet $ord1 innehåller " . strlen($ord1) . " bokstäver<br>";
        echo "ordet $ord2 innehåller " . strlen($ord2) . " bokstäver<br>";
        echo "ordet $ord3 innehåller " . strlen($ord3) . " bokstäver";
    }
    ?>
</body>

</html>