<?php
$array = [
    "Sverige" => "Stockholm",
    "Norge" => "Oslo",
    "Spanien" => "Madrid",
    "Frankrike" => "Paris",
    "Afghanistan" => "Kabul",
    "Turkiet" => "Ankara",
    "Tyskland" => "Berlin",
    "Kina" => "Bejing",
    "Colombia" => "Bogota",
    "Grekland" => "Rom"
];

PrintArray($array);

rsort($array); #Fixa detta, tar bort key och ersätter med ett nummer

PrintArray($array);

function PrintArray($array)
{
    foreach ($array as $country => $capitol) {
        echo "Huvudstaden i {$country} heter {$capitol}.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uppgift 8B</title>
</head>

<body>

</body>

</html>