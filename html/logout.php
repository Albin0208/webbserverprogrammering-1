<?php
session_start();
$_SESSION = array(); //Tömmer alla sessionsvariabler
session_regenerate_id(true);
header("Location: admin.php");
