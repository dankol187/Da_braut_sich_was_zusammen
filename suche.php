<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

// Fehlerüberprüfung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

if (isset($_GET['suchbegriff'])) {
    $suchbegriff = $conn->real_escape_string($_GET['suchbegriff']);
    
    $sql = "SELECT id, name FROM Item WHERE name LIKE '%$suchbegriff%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row["name"]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Keine Ergebnisse gefunden.";
    }
}

    

$db->disconnect();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Suche</title>
</head>
<body>
    <form method="get" action="suche.php">
        <input type="text" name="suchbegriff" value="<?php echo htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben">
        <button type="submit">Suchen</button>
    </form>
<h2>Suchergebnisse:</h2>
    <?php if (isset($_GET['suchbegriff'])): ?>
            <?php if (count($result) > 0): ?>
            <ul>
                <?php foreach ($result as $row): ?>
                    <li>ID: <?php echo $row['id']; ?> - Name: <?php echo htmlspecialchars($row['name']); ?></li>
    <?php endforeach; ?>        
                </ul>
        <?php else: ?>
            <p>Keine Ergebnisse gefunden.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
