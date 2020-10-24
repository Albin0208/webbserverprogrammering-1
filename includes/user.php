<?php

/**
 * Användare i systemet
 */

class users
{

  /**
   * Kontrollera om ett lösenord stämmer
   *
   * @param  string $username Användarnamn
   * @param  string $password Lösenord att kontrollera
   * @param  PDO $dbh databasanslutning
   * @param  string $errorinfo Felmeddelande obs! By reference
   * @return bool Anger om lösenordet stämmer eller inte
   */
  public static function verify_login($username, $password, PDO $dbh, &$errorinfo)
  {
    $sql = "SELECT password FROM users WHERE username = :username";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $hash = $stmt->fetchColumn();

    if (empty($hash)) {
      $errorinfo = "Felaktigt användarnamn";
      return false;
    }
    if (!password_verify($password, $hash)) {
      $errorinfo = "Felaktigt lösenord";
      return false;
    }
    return true;
  }
}
