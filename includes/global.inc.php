<?php
// $CURLOCALE = setlocale( FIXME );
// if (!strlen($CURLOCALE)) {
//     user_error('Locale inte inställd!', E_USER_WARNING);
// }


/**
 * Funktion för att ansluta till en databas
 *
 * Funktionen förutsätter att några konstanter redan har satts:
 * LAXHJ_DB_DBNAME som är namnet på databasen att ansluta till.
 * LAXHJ_DB_USERNAME som är det användarnamn PHP använder för att ansluta.
 * LAXHJ_DB_PASSWORD som är lösenordet.
 * LAXHJ_TZ som är den tidszon som applikationen ska fungera inom.
 * Funktionen kan anropas flera gånger, utan att någon ny uppkoppling sker.
 * Funktionen gör grundläggande inställningar kring UTF-8 och svensk tidszon
 * för databasförbindelsen.
 *
 * OBS! Funktionen saknar ännu vettig felhantering!
 *
 * @return PDO Databaskopplingen som ett PDO-objekt
 */
function get_dbh()
{
    // Uppkopplingen återanvänds
    static $dbh;
    if (is_null($dbh)) {
        // Kontroll av "parametrar"
        $dsn    = "mysql:host=localhost;dbname=" . LAXHJ_DB_DBNAME . ";charset=utf8";

        // Lite grundläggande inställningar
        $attributes = array(
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_ASSOC
        );
        try {
            $dbh = new PDO($dsn, LAXHJ_DB_USERNAME, LAXHJ_DB_PASSWORD, $attributes);
            if (empty($dbh)) {
                throw new Exception("PDO kunde inte instansieras, uppkopplingen misslyckad");
            }
            //TODO Ta reda på varöfr timezone inte fungerar
            // $ts_sql = "SET time_zone = '" . LAXHJ_TZ . "'";
            // $svar = $dbh->query($ts_sql);

            // Lite inställningar för MySQL,
            // se https://kb.askmonty.org/en/sql_mode/
            // Tolerera INGA fel under utveckling, bli generösare under drift.
            // OBS! Inget mellanrum mellan kommatecknen på raden nedan!
            $mode_sql = "SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE'";
            $svar     = $dbh->query($mode_sql);

            // Idiotsäkra förbindelsen så att vi inte råkar
            // radera eller uppdatera alla poster av misstag
            // eller hämts på tok för mycket data
            //TODO Ta reda på varför nedanstående producerar ett error
            // $safe_sql = "SET sql_safe_updates=1, sql_select_limit=1000, sql_max_join_size=1000000";
            // $svar = $dbh->query($safe_sql);
        } catch (Exception $e) {
            echo "<pre>";
            var_dump($e);
            echo "<hr />";
            var_dump($dbh->errorInfo());
            echo "<hr />";
            exit;
        }
    }
    return $dbh;
}

/**
 * Funktion som hämtar alla kommentarer som hör ihop med ett enskilt blogginlägg
 * 
 * @param int $articlesID Blogginläggets ID i databasen
 * @param PDO $dbh        Databasanslutningen att använda
 * @return Array          Kommentarerna
 */
function fetch_blog_comments($articlesID, $dbh)
{
    $sql = "SELECT * FROM comments WHERE articlesID = :aid ORDER BY ctime ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":aid", $articlesID);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //Tar inte förgivet att FETCH_ASSOC angivits

    //Provisorisk säkring mot XSS, lägg märke till &-tecknet
    foreach ($comments as &$cmt) {
        $cmt['name'] = htmlspecialchars($cmt['name'], ENT_QUOTES);
        $cmt['text'] = htmlspecialchars($cmt['text'], ENT_QUOTES);
    }
    return $comments;
}
