<?php

/**
 * Blogginlägg = Artiklar på webbplatsen Läxhjälpen
 *
 * @todo Ett sätt att byta författare (username)
 */
class Articles
{
    protected $slug, $title, $text, $username, $pubdate;

    // Dessa variabler kan läsas med __get() eller konverteras till array
    protected $accessibles = array('articlesID', 'slug', 'title', 'text', 'username', 'pubdate');

    /**
     * Primärnyckel i databasen, null = ännu inte sparad
     *
     * Kan läsas med __get()
     * @var int
     */
    protected $articlesID = null;

    /**
     * Håller reda på om all data är kontrollerad
     */
    protected $hasBeenValidated = false;

    /**
     * Håller reda på om all data är kontrollerad och godkänd
     */
    protected $isValid = false;

    /**
     * Felmeddelanden som kan uppstå vid validering av artikeldata
     * @var array
     */
    protected $errorMessages = array('title' => "", 'text' => "");

    /**
     * En ny slugg har skapats till befintligt inlägg
     * @var string
     */
    protected $newSlug = false;

    /**
     * Anslutning till databasen
     * @var PDO
     */
    protected $dbh;

    /**
     * Listar tillåten HTML-kod i brödtext
     */
    const TEXTHTML = <<<TAGSANDATTRIBUTES
        *[lang|dir]
        a[href|rel], abbr[title], em, i, b, strong, span[class], small,
        div[id], p[id], h3, h4, h5, h6,
        ul, ol[start], li, dl, dt, dd,
        table, th[scope], tr, td[colspan|rowspan], col, tbody, thead,
        tfoot, caption,
        code, pre, br, kbd, sub, sup,
        blockquote, ins, del, s, strike, q,
        img[src|alt],
        object[data|name|type], param[name|value],
TAGSANDATTRIBUTES;
    /* Stöds ej av HTMLPurifier normalt
        a[type], mark, time,
        figure, figcaption,
        audio[controls|loop|preload|src],
        video[controls|loop|preload|src],
        source[src|type], track[default|label|kind|src|srclang]

     */

    /**
     * Konstruktorn som skapar en ny osparad artikel
     *
     * Instansens övriga funktioner måste validera data innan den sparas
     * @param string $title    Artikelns rubrik
     * @param string $text     Artikelns brödtext
     * @param string $username Artikelns författare
     * @param PDO    $PDO      Databasanslutning
     * @param int    $id       ID i databasen för existerande artikel
     *                         Används vid uppdatering
     */
    public function __construct($title, $text, $username, PDO $dbh, $id = null)
    {
        $this->title = $title;
        $this->text = $text;
        $this->username = $username;
        $this->dbh = $dbh;
        $this->articlesID = $id;
        // throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }

    /**
     * Getter för artikelns innehållsvariabler
     */
    public function __get($varname)
    {
        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
        if (in_array($varname, $this->accessibles, true)) {
            return $this->$varname;
        }
        throw new Exception("Trying to access undefined or protected property ($varname) of " . __CLASS__);
    }

    /**
     * Validerar indata
     * 
     * Rubrik och brödtext kollas mot reguljära uttryck
     * articlesID och username kollas aldrig, vi litar på databasens referensintegritet
     * @uses HTMLPurifier
     */
    public function validate()
    {
        require "../htmlpurifier/library/HTMLPurifier.auto.php";

        if ($this->hasBeenValidated) {
            return $this->isValid;
        }

        // Preliminär sanering
        $this->title = trim($this->title);
        $this->text = trim($this->text);

        // Flagga för korrekt data
        $this->isValid = true;

        // Valideringen sker med unicode-teckenklasser
        // Se: http://www.fileformat.info/info/unicode/category/index.htm
        // Tillåtna tecken
        // L  = bokstäver
        // N  = siffror
        // Zs = mellanslag
        // P  = Punktuering
        // S  = Symboler (valuta, matematik, etc)
        // M  = Mark - inkluderad accenter och liknande
        // \n är som vanligt nyrad
        // OBS! Manualens varning: Matching characters by Unicode property is not fast...
        $title_regexp = '/^[\p{L}\p{N}\p{Zs}\p{P}\p{S}\p{M}]+$/u';
        $text_regexp  = '/^[\p{L}\p{N}\p{Zs}\p{P}\p{S}\p{M}\n]+$/u';
        if (!preg_match($title_regexp, $this->title)) {
            $this->isValid = false;
            $this->errorMessages['title'] = "Otillåtna tecken i rubriken";
        } else {
            // Rena HTML-koden
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', 'em, i');
            $purifier = new HTMLPurifier($config);
            $this->title = $purifier->purify($this->title);

            // Kontrollera att längden är mellan 5 och 100 tecken
            if (mb_strlen($this->title, "utf-8") < 5) {
                $this->isValid = false;
                $this->errorMessages['title'] = "För kort rubrik (minimum 5 tecken)";
            } elseif (mb_strlen($this->title, "utf-8") > 100) {
                $this->isValid = false;
                $this->errorMessages['title'] = "För lång rubrik (maximum 100 tecken)";
            }
        }

        if (!preg_match($text_regexp, $this->text)) {
            $this->isValid = false;
            $this->errorMessages['text'] = "Otillåtna tecken i rubriken";
        } else {
            // Rena HTML-koden
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $config->set('HTML.Trusted', true);
            $config->set('HTML.Allowed', $this::TEXTHTML);
            $config->set('Output.Newline', '\n');
            $this->text = $purifier->purify($this->title);

            if (mb_strlen($this->text, "utf-8") < 100) {
                $this->isValid = false;
                $this->errorMessages['text'] = "För kort text (minimum 100 tecken)";
            } elseif (mb_strlen($this->text, "utf-8") > 10000) {
                $this->isValid = false;
                $this->errorMessages['text'] = "För lång text (maximum 10.000 tecken)";
            }
        }

        $this->hasBeenValidated = true;
        return $this->isValid;
        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }

    /**
     * Vilka fel rent specifikt fälten har
     * 
     * @return array Felmeddelanden per variabel
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Artikelns slugg
     * 
     * Om ingen finns, så skapas den ifrån artikelns rubrik
     * @param bool $force_new Anger att en ny slugg ska skapas även om en gammal finns
     * @return string
     */
    public function getSlug($force_new = false)
    {
        if (!$this->isValid) {
            throw new Exception("Attempting to generate slug from invalid data");
        }
        // Skapa inte ny slugg till befintlig artikel
        if ($this->articlesID && !$force_new) {
            $sql  = "SELECT slug FROM articles WHERE articlesID = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(":id", $this->articlesID);
            $stmt->execute();
            $this->slug = $stmt->fetchColumn();
            return $this->slug;
        }
        // Utgå från rubriken
        $slug = mb_strtolower($this->title, "utf-8");
        // Ta bort alla diakritiska tecken å -> a, û -> u, etc.
        // Men var snäll mot tyskar (ß -> ss) och danskar (æ -> ae och h -> oe)
        $slug = preg_replace('/\x{00df}/u', "ss", $slug);
        $slug = preg_replace('/\x{00e6}/u', "ae", $slug);
        $slug = preg_replace('/\x{0153}/u', "oe", $slug);
        $slug = Normalizer::normalize($slug, Normalizer::FORM_D);
        $slug = preg_replace('/\p{M}/u', "", $slug);
        // Ersätt mellanslag och underscore med bindestreck
        // Aldrig mer än ett åt gången
        $slug = preg_replace('/[_\p{Zs}]+/u', "-", $slug);
        // Ta bort allt utom bokstäver, bindestreck och siffror
        $slug = preg_replace('/[^\p{L}\p{N}-]+/u', "", $slug);
        // Ta bort inledande och avslutande bindestreck
        // Notera att strim här används med parameter
        $slug = trim($slug, "-");
        // Ta bort om det blir mer än ett bindestreck i följd
        $slug = preg_replace('/-{2,}/u', "-", $slug);

        // Har den skapade sluggen blivit för kort?
        if (mb_strlen($slug, "utf-8") < 5) {
            $this->isValid = false;
            $this->errorMessages['title'] = "Rubriken kan inte bli en slugg";
            return false;
        }

        // Kortar ner om längre än databasens maxlängd minus 5
        // = plats för att numrera 9999 dubletter
        // Detta händer bara om rubriken är lång
        if (mb_strlen($slug, "utf-8") > 45) {
            // Tar bort de övereskjutande bokstäverna och siffrorna
            // Men klipper aldrig mitt i ett ord
            $slug = preg_replace('/-+?([^-]+)?$/', '', substr($slug, 0, 46));
        }
        // Kolla om sluggen är unik
        $sql    = "SELECT slug FROM articles WHERE slug REGEXP :slug";
        $p_slug = "^{$slug}(-[0-9]*)?$";
        $stmt   = $this->dbh->prepare($sql);
        $stmt->bindParam(":slug", $p_slug);
        $stmt->execute();
        $doubles = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        if ($doubles) {
            if (count($doubles) == 1) {
                // Det finns bara en dublett
                $slug .= "-1";
            } else {
                // Hitta högsta räknetalet som tillfogas på slutet
                // Vi kan inte sortera efter hela texten
                $highest = 0;
                foreach ($doubles as $dbl) {
                    if ((int)substr(strrchr($dbl, "-"), 1) > $highest) {
                        $highest = $dbl;
                    }
                }
                if ($highest = 9999) {
                    $this->isValid = false;
                    $this->errorMessages['title'] = "Rubriken ej originell nog";
                    return false;
                }
                // Addera 1 och lägg på slutet av vår slug
                $slug .= "-" . ++$highest;
            }
        }
        $this->slug    = $slug;
        $this->newSlug = $force_new;
        return $slug;
    }

    /**
     * Spara i databasen
     *
     * @return mixed Primärnyckeln i databasen
     */
    public function save()
    {
        //Felaktiga inlägg ska inte sparas
        if (!$this->isValid || !$this->slug) {
            throw new Exception("Attempting to svae invalid article");
        }

        if (empty($this->articlesID)) {
            //Ny artikel som ska sparas med ett INSERT-kommando i $sql
            //Position 1
            $sql = <<<SQL
                INSERT INTO articles (articlesID, slug, title, text, username, pubdate)
                VALUES (NULL, :slug, :title, :text, :username, :pubdate)
                SQL;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(":slug", $this->slug);
            $stmt->bindParam(":title", $this->title);
            $stmt->bindParam(":text", $this->text);
            $stmt->bindParam(":username", $this->username);
            $stmt->execute();
            $this->articlesID = $this->dbh->lastInsertId();
        } else {
            //Befintlig artikel ska spara med ett UPDATE-kommando i $sql
            //Position 2
            try {
                $this->dbh->beginTransaction();

                $sql = <<<SQL
                            UPDATE articles SET
                                   title = :title,
                                   text = :text
                            WHERE articlesID = :articlesID
                            SQL;

                $stmt = $this->dbh->prepare($sql);
                $stmt->bindParam(":title", $this->title);
                $stmt->bindParam(":text", $this->text);
                $stmt->bindParam(":articlesID", $this->articlesID);
                $stmt->execute();
                if ($this->newSlug) {
                    $sql = "UPDATE articles SET slug = :slug WHERE articlesID = :articlesID";
                    $stmt = $this->dbh->prepare($sql);
                    $stmt->bindParam(":slug", $this->slug);
                    $stmt->bindParam(":articlesID", $this->articlesID);
                    $stmt->execute();
                }
                $this->dbh->commit();
            } catch (Exception $e) {
                //Något gick på tok - ångra
                $this->dbh->rollBack();

                //Ingen bra felhantering ännu...
                throw new Exception("Uppdatering av artikel misslyckad: " . $e->getMessage());
            }
        }

        //Vilken artikel var det som ändrades eller skapades
        return $this->articlesID;

        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }

    /**
     * Factory-metod som skapar ett artikelobjekt
     *
     * @param int $id  Primärnyckeln i databasen
     * @param PDO $dbh Databasanslutning
     * @param PDO $id_is_slug Sätts till true om vi letar efter en ertikel efter dess slugg
     * @return Instans av denna klass (articles)
     */
    public static function fetch($id, PDO $dbh, $id_is_slug = false)
    {
        //Vet inte om det är rätt hittade på något så det skulle funka
        $stmt = $dbh->prepare("SELECT * FROM articles WHERE articlesID = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $blogpost = $stmt->fetch();
        return $blogpost;
        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }

    /**
     *
     */
    public static function fetchList()
    {
        // FIXME
        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }

    /**
     * Skicka artikeldata som en enkel array
     */
    public function asArray()
    {
        foreach ($this->accessibles as $key) {
            $array[$key] = $this->$key;
        }
        return $array;
        throw new Exception(__METHOD__ . " not implemented yet in " . __CLASS__);
    }
}
