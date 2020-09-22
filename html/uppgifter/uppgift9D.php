<?php
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    function roll_dice()
    {
        $tal1 = mt_rand(1, 6);
        $tal2 = mt_rand(1, 6);

        return [$tal1, $tal2];
    }

    echo roll_dice();
    ?>
</body>

</html>