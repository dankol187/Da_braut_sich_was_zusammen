<?php
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Konstruktor zur Initialisierung der Datenbankparameter
    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    // Funktion zum Herstellen der Verbindung
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $this->conn->connect_error);
        }
        echo "Verbindung erfolgreich hergestellt!<br>";
    }

    // Funktion zum Abrufen von Daten
    public function getData($table) {
        $sql = "SELECT * FROM " . $table;
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
            }
        } else {
            echo "Keine Daten gefunden.<br>";
        }
    }

    // Funktion zum Trennen der Verbindung
    public function disconnect() {
        $this->conn->close();
        echo "Verbindung getrennt.<br>";
    }
}

// Nutzung der Klasse
$db = new Database("localhost", "root", "", "meine_datenbank");
$db->connect();
$db->getData("meine_tabelle");
$db->disconnect();
?>
