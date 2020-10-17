<div role="main">
  <h2>Logga in som admin</h2>
  <form action="admin.php" method="POST">
    <fieldset>
      <legend>Inloggningsuppgifter</legend>
      <?php echo $login_errormsg; ?>
      <p>
        <label for="username" class="widelbl">Användarnamn:</label>
        <input type="text" value="<?php echo $login_username; ?>" name="username" id="username" maxlength="20" required>
      </p>
      <p>
        <label for="password" class="widelbl">Lösenord:</label>
        <input type="password" value="" name="password" id="password" required>
      </p>
      <p>
        <input type="submit" value="Logga in">
      </p>
    </fieldset>
  </form>
</div>