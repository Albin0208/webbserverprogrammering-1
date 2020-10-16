<?php
$show_form = true;

//Variabler för defaultvärden
$name = "";
$age = "";
$mail = "";

//Felmeddelande
$name_error = "";
$age_error = "";
$mail_error = "";

//Testa hur PHP hanterar formulärdata
if (isset($_POST['raw'])) {
    header("Content-type: text/plain; charset=utf-8");
    var_dump($_POST);
    exit;
}

//Visas formuläret för andra gången (eller senare)?
if (!empty($_POST)) {
    $_POST = null;

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_ENCODED, FILTER_FLAG_STRIP_LOW);
    $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);

    $show_form = false;
    if (!strlen($name)) {
        $name_error = "Inget namn uppgivet";
        $show_form = true;
    }
    if ($age < 1 || $age > 120) {
        $age_error = "Inte en riktig ålder";
        $show_form = true;
    }
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $mail_error = "Inte en riktig mejladress";
        $show_form = true;
    }
}

header("Content-type: text/html; charset:utf-8;");
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avsnitt 14.1</title>
    <style>
        body {
            font-family: sans-serif;
        }

        label {
            position: relative;
            display: inline-block;
            width: 150px;
        }

        .error {
            color: red;
            position: absolute;
            left: 300px;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h1>Avsnitt 14.1: Formulär</h1>

    <?php
    if ($show_form) {
        echo <<<FORM
            <!--Skicka till sig själv-->
            <form action="{$_SERVER['PHP_SELF']}" method="post" novalidate>
                <p>
                    <label for="name">Förnamn:
                        <strong class="error">{$name_error}</strong>
                    </label>
                    <input name="name" id="name" value="{$name}" required />
                </p>
                <p>
                    <label for="age">Din ålder (hela år):<strong class="error">{$age_error}</strong></label>
                    <input name="age" id="age" value="{$age}" type="number" required />
                </p>
                <p>
                    <label for="mail">Din mejladress:<strong class="error">{$mail_error}</strong></label>
                    <input name="mail" id="mail" value="{$mail}" type="email" required />
                </p>
                <p>
                    <label for="raw">Visa \$_POST</label>
                    <input name="raw" id="raw" value="on" type="checkbox" />
                </p>
                <p>
                    <input type="submit" value="Skicka" />
                </p>
            </form>
            FORM;
    } else {
        echo <<<SVAR
        <p>Tack för ditt svar. Du har skickat dessa data:</p>
        <dl>
            <dt>Förnamn:</dt>
                <dd>{$name}</dd>
            <dt>Ålder:</dt>
                <dd>{$age}</dd>
            <dt>Mejladress</dt>
                <dd>{$mail}</dd>
        </dl>
        SVAR;
    }
    ?>
</body>

</html>