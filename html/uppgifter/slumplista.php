<?php

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slumplista PHP</title>
</head>

<body>
    <?php
    $list = [];

    for ($i = 0; $i < random_int(5, 20); $i++) {
        $list[$i] = random_int(0, 100);
    }

    foreach ($list as $item) {
        echo $item . "<br>";
    }

    echo "Största värdet är " . max($list) . "<br>";
    echo "Minsta värdet är " . min($list);
    ?>
</body>

</html>