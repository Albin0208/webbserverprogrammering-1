<?php

class gadget
{
    protected $name;
    protected $material;
    protected $price;

    function __construct($name, $material, $price)
    {
        $this->name = $name;
        $this->material = $material;
        $this->price = $price;
    }

    function PrintGadgetInfo()
    {
        echo "$this->name är gjord av $this->material och kostar $this->price kr<br>";
    }

    function __set($name, $value)
    {
        switch ($name) {
            case 'name':
                $this->name = $value;
                break;
            case 'material':
                $this->material = $value;
                break;
            case 'price':
                $this->price = $value;
                break;

            default:
                echo $name . "not found";
                break;
        }
    }
}

class Hammer extends gadget
{
    protected $weight;

    function GetWeight()
    {
        echo $this->name . " väger " . $this->weight . " kg";
    }

    function __set($name, $value)
    {
        if ($name == "weight") {
            $this->weight = $value;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uppgift 10E</title>
</head>

<body>
    <?php
    $gadget_one = new gadget("Skruvmejsel", "metal", 99.90);
    $gadget_one->PrintGadgetInfo();
    $gadget_two = new Hammer("Hammare", "sten", 169.90);
    $gadget_two->weight = 21;
    $gadget_two->PrintGadgetInfo();
    $gadget_two->GetWeight();
    ?>
</body>

</html>