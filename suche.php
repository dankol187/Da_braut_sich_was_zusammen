<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();
$suchbegriff ="";

session_start();
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, zur Anmeldung weiterleiten
    header("Location: nutzer_login.php");
    exit;
}
echo "Willkommen, " . htmlspecialchars($_SESSION['username']);

if (isset($_GET['suchbegriff'])) {
    $suchbegriff = $conn->real_escape_string($_GET['suchbegriff']);
    
    $sql = "SELECT id, name FROM Item WHERE name LIKE '%$suchbegriff%'";
    $result = $conn->query($sql);
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
            <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>ID: <?php echo $row['id']; ?> - Name: <?php echo htmlspecialchars($row['name']); ?></li>
    <?php endwhile; ?>        
                </ul>
        <?php else: ?>
            <p>Keine Ergebnisse gefunden.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
