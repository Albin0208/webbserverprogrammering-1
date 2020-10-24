<?php
echo <<<HTML
  <h1>Inloggning lyckades för {$_SESSION['username']}</h1>
  <p><a href="editblogpost.php">Skriv på bloggen</a></p>
  <p><a href="logout.php">Logga ut</a></p>
HTML;
