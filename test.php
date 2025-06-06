<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();


$sql = "SELECT * FROM Trank";  // Passe den Tabellennamen an
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ausgabe der Daten jeder Zeile
    while($row = $result->fetch_assoc()) {
        echo "TrankID: " . $row["TrankID"]. " - Name: " . $row["name"].  " - Art: " . $row["art"]. " - Stufe: " . $row["stufe"]. " - Beschreibung: " . $row["beschreibung"]." - Dauer: " . $row["dauer"]."<br>";
    }
} else {
    echo "0 Ergebnisse";
}

$db->disconnect();
?>
