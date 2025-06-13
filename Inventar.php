<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Falls der Nutzer gar kein Login hat:
    header("Location: nutzer_login.php");
    exit;
}

$username = $_SESSION['username'];

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = (int)$_POST['item_id']; // Item_ID muss vorhandenen Eintrag referenzieren
    $menge = (int)$_POST['Anzahl'];

    if ($menge <= 0) {
        $msg = "Bitte gib eine gültige Menge an.";
    } else {
        $stmt = $conn->prepare("REPLACE INTO hat (hat_Benutzername, hat_ItemID, Anzahl) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $username, $itemId, $menge);
        if ($stmt->execute()) {
            $msg = "Der Eintrag wurde erfolgreich hinzugefügt.";
        } else {
            $msg = "Daten konnten leider nicht gespeichert werden.";
        }
    }
}

// Alle vorhandenen Items aus der Datenbank holen für das Select-Dropdown
$items = $conn->query("SELECT ID, name FROM Item");

$db->disconnect();

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gegenstand auswählen</title>
    <style>
        body {font-family: Arial,sans-serif; color: #4a5568; background: #edf2f7; margin: 0;padding: 20px;}
        .container {max-width: 500px; margin: 50px auto;padding: 30px;background: #ffffff;border-radius: 10px;box-shadow: 0 4px 6px -1px #0000001a;}
        form {margin-bottom: 20px;}
        select, input {width: 100%;padding: 10px;margin-bottom: 15px;font-size: 1em;color: #4a5568;border: 1px solid #e2e8f0;border-radius: 6px;}
        button {padding: 10px 20px;background: #3182ce;color: #ffffff;font-size: 1em;border: none;border-radius: 6px;cursor: pointer;}
        button:hover {background: #2c5282;}
        .msg {margin-bottom: 20px;padding: 10px;color: #ffffff;background: #38a169;border-radius: 6px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Gegenstand auswählen</h1>
        <?php if (isset($msg)): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="id">Gegenstand:</label>
            <select name="id" id="id" required>
                <option value="">-- bitte auswählen --</option>
                <?php while ($item = $items->fetch_assoc()) : ?>
                    <option value="<?= $item['id']; ?>"> <?= htmlspecialchars($item['name']); ?> </option>
                <?php endwhile; ?>
            </select>

            <label for="Anzahl">Anzahl:</label>
            <input id="Anzahl" name="Anzahl" type="number" min="1" required>

            <button type="submit">Hinzufügen</button>
        </form>

        <a href="suche.php"><button>Zurück</button></a>
    </div>
</body>
</html>
