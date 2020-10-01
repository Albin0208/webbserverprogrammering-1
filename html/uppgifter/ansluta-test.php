<?php
header("Content-type: text/plain; charset=utf-8");
$dbh = new PDO('mysql:host=localhost;dbname=laxhjalpen', 'phpuser', 'password');
if (!$dbh) {
    echo "Kontakt ej etablerad";
    print_r($dbh->errorInfo());
    exit;
}
echo "Kontakt etablerad. Hurra!";
