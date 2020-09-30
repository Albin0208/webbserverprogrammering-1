<?php

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Echo PHP</title>
</head>

<body>
    <form method="post">
        <label for="nameInput">Skriv in ett tal du vill göra kvadratroten ur</label>
        <input type="number" name="number" id="nameInput">
        <input type="submit" name="submit" value="Calculate">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        if (isset($_POST['number'])) {
            $number = $_POST['number'];
            $result = sqrt($number);
            echo "Roten ur " . $number . " är " . $result;
        }
    }
    ?>
</body>

</html>