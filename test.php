<?php
require_once 'Database.php';
$db = new Database();
$db->connect();

$sql = "SELECT * FROM Item"; // Passe den Tabellennamen an
$result = $db->query($sql);

if ($result) {
    foreach ($result as $row) {
        print_r($row);
        echo "<br>";
    }
} else {
    echo "Fehler bei der Abfrage!";
}

$db->disconnect();
?>
