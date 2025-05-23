<?php
require_once 'Database.php';
$db->connect();
// Fehlerüberprüfung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Suchbegriff aus dem Formular holen und gegen SQL-Injection absichern
$suchbegriff = $conn->real_escape_string($_GET['suchbegriff']);

// SQL-Abfrage für Trank
$sql = "SELECT * FROM Trank WHERE name LIKE '%$suchbegriff%'";
$result = $conn->query($sql);

// SQL-Abfrage für Item
$sql = "SELECT * FROM Item WHERE name LIKE '%$suchbegriff%'";
$result = $conn->query($sql);

// Ergebnisse anzeigen
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} else {
    echo "Keine Ergebnisse gefunden.";
}

$db->disconnect();
?>
