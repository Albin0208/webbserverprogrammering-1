<?php
header("Content-type: text/plain; charset=utf-8");
$dbh = new PDO('mysql:host=localhost;dbname=laxhjalpen; charset=utf8', 'phpuser', 'password');
if (!$dbh) {
    echo "Kontakt ej etablerad";
    print_r($dbh->errorInfo());
    exit;
}
echo "Kontakt etablerad. Hurra!";

$sql = "SELECT * FROM articles ORDER BY pubdate DESC";
$stmt = $dbh->prepare($sql);
$stmt->execute();
while ($articles = $stmt->fetch()) {
    var_dump($articles);
}
