<?php
require "../includes/password.php";

$password = "lösen";
echo password_hash($password, PASSWORD_DEFAULT) . "<br>";
$password = "password";
echo password_hash($password, PASSWORD_DEFAULT) . "<br>";
$password = "lösenord";
echo password_hash($password, PASSWORD_DEFAULT) . "<br>";
