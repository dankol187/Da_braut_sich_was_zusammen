<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();


$sql = "SELECT TrankID, name FROM Trank";  // Passe den Tabellennamen an
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ausgabe der Daten jeder Zeile
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
    }
} else {
    echo "0 Ergebnisse";
}

$db->disconnect();
?>
