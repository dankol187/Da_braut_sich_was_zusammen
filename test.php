<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();


$sql = "SELECT * FROM Trank";  // Passe den Tabellennamen an
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ausgabe der Daten jeder Zeile
    while($row = $result->fetch_assoc()) {
        echo "TrankID: " . $row["TrankID"]. " - Name: " . $row["Name"].  " - Art: " . $row["Art"]. " - Stufe: " . $row["Stufe"]. " - Beschreibung: " . $row["Beschreibung"]." - Dauer: " . $row["Dauer"]."<br>";
    }
} else {
    echo "0 Ergebnisse";
}

$db->disconnect();
?>
