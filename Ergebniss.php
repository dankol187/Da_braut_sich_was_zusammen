<?php
require_once 'Database.php';

// Verbindung zur Datenbank herstellen
$db = new Database();
$conn = $db->connect();

$results = [];
$query = '';
if (isset($_GET['search'])) {
    $query = trim($_GET['search']);
    if ($query !== '') {
        // In Item suchen
        $stmt1 = $conn->prepare("SELECT * FROM Item WHERE name LIKE ?");
        $stmt1->execute(['%' . $query . '%']);
        $items = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // In Trank suchen
        $stmt2 = $conn->prepare("SELECT * FROM Trank WHERE name LIKE ?");
        $stmt2->execute(['%' . $query . '%']);
        $tranks = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // Ergebnisse zusammenführen
        $results = array_merge($items, $tranks);
    }
}
?>

<?php
    echo "

    <h1>Datenbank Suche</h1>
    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($query); ?>" placeholder="Suchbegriff eingeben" required>
        <button type="submit">Suchen</button>

    <?php if (isset($_GET['search'])): ?>
        <h2>Suchergebnisse für "<?php echo htmlspecialchars($query); ?>"</h2>
        <?php if (empty($results)): ?>
            <p>Keine Einträge gefunden.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($results as $row): ?>
                    <li>
                        <?php
                        // Zeige am besten Name und ggf. eine ID/Typ an
                        echo htmlspecialchars($row['name']);
                        if (isset($row['id'])) echo " (ID: " . intval($row['id']) . ")";
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
?>
