<?php
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uppgifter kap 9</title>
</head>

<body>
    <form action="" method="post">
        <input type="submit" value="Uppgift 9D" name="button9D">
        <input type="submit" value="Uppgift 9E" name="button9E">
        <input type="submit" value="Uppgift 9F" name="button9F">
    </form>

    <?php
    if (array_key_exists('button9D', $_POST)) {
        for ($i = 0; $i < 5; $i++) {
            list($tal1, $tal2) = roll_dice();
            echo "Första tärningen blev $tal1 och den andra tärningen blev $tal2<br>";
        }
    } elseif (isset($_POST['button9E'])) {
        for ($i = 0; $i < 10; $i++) {
            echo nextFib() . " ";
        }
    } elseif (isset($_POST['button9F'])) {
        if (CheckLuhn(("4111111111111111")))
            echo "Valid";
        else {
            echo "Invalid";
        }
    }

    function roll_dice()
    {
        $tal1 = mt_rand(1, 6);
        $tal2 = mt_rand(1, 6);

        return array($tal1, $tal2);
    }

    function nextFib()
    {
        static $previous, $pre_previous;

        if (!isset($previous)) {
            $previous = 1;
            $pre_previous = 0;
            return 1;
        }

        $sum = $previous + $pre_previous;
        $pre_previous = $previous;
        $previous = $sum;
        return $sum;
    }

    function CheckLuhn($number)
    {
        //Tar bort allt som inte är siffror
        $number = preg_replace('/\D/', '', $number);

        $nDigits = strlen($number);

        $nSum = 0;
        $isSecond = false;

        for ($i = $nDigits - 1; $i >= 0; $i--) {
            $digit = $number[$i];

            if ($isSecond) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9; //Adderar ihop siffra 1 och 2
                }
            }

            $nSum += $digit;

            $isSecond = !$isSecond;
        }

        return ($nSum % 10 == 0);
    }

    ?>
</body>

</html>