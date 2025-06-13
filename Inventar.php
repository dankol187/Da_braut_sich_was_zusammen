<?php
// Datenbankverbindung herstellen
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();
}

// Angenommen, der Nutzer ist bereits eingeloggt und seine ID ist bekannt.
// In einer echten Anwendung würdest du $_SESSION verwenden.
$nutzer_id = 1; // Beispielwert, ersetze dies durch die aktuelle Nutzer-ID

// Items aus der Datenbank laden
$items = [];
$result = $conn->query("SELECT id, name FROM item ORDER BY name
");
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Wenn das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($items as $item) {
        $item_id = $item['id'];
        $anzahl = isset($_POST['anzahl_'.$item_id]) ? (int)$_POST['anzahl_'.$item_id] : 0;
        if ($anzahl > 0) {
            // Bestehenden Eintrag aktualisieren oder neuen hinzufügen
            $stmt = $conn->prepare(
                "INSERT INTO hat (Benutzername, ItemID, Anzahl) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE anzahl = VALUES(anzahl)"
            );
            $stmt->bind_param("iii", $nutzer_id, $item_id, $anzahl);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<p>Deine Auswahl wurde gespeichert!</p>";
    // Optional: Weiterleitung oder Anzeige der aktuellen Auswahl
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Items auswählen</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h1>Items auswählen</h1>
    <form method="post">
        <table>
            <tr>
                <th>Item</th>
                <th>Anzahl</th>
            </tr>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['bezeichnung']); ?></td>
                    <td>
                        <input type="number" name="anzahl_<?php echo $item['id']; ?>" value="0" min="0" step="1">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button type="submit">Speichern</button>
    </form>
</body>
</html>
