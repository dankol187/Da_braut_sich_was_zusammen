<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}

$username = $_SESSION['username'];

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = (int)$_POST['id'];
    $menge = (int)$_POST['Anzahl'];

    if ($menge <= 0) {
        $msg = "Bitte gib eine positive Menge an.";
    } else {
        // Prüfen, wie viele zurzeit da sind
        $stmt = $conn->prepare("SELECT Anzahl FROM hat WHERE hat_Benutzername = ? AND hat_ItemID = ?");
        $stmt->bind_param("si", $username, $itemId);
        $stmt->execute();
        $stmt->bind_result($aktuelleAnzahl);
        $stmt->fetch();
        $stmt->close();

        if (!isset($aktuelleAnzahl)) {
            $msg = "Dieses Item befindet sich nicht im Inventar.";
        } elseif ($menge >= $aktuelleAnzahl) {
            // Komplett entfernen
            $stmt = $conn->prepare("DELETE FROM hat WHERE hat_Benutzername = ? AND hat_ItemID = ?");
            $stmt->bind_param("si", $username, $itemId);
            if ($stmt->execute()) {
                $msg = "Das Item wurde komplett entfernt.";
            } else {
                $msg = "Fehler beim Löschen.";
            }
        } else {
            // Teilweise verringern
            $neueAnzahl = $aktuelleAnzahl - $menge;
            $stmt = $conn->prepare("UPDATE hat SET Anzahl = ? WHERE hat_Benutzername = ? AND hat_ItemID = ?");
            $stmt->bind_param("isi", $neueAnzahl, $username, $itemId);
            if ($stmt->execute()) {
                $msg = "Die Anzahl wurde verringert.";
            } else {
                $msg = "Fehler beim Aktualisieren.";
            }
        }
    }
}

// Für die Anzeige des Inventars
$userItems = $conn->prepare("SELECT hat.Anzahl, Item.name, Item.ID FROM hat JOIN Item ON hat.hat_ItemID = Item.ID WHERE hat.hat_Benutzername = ?");
$userItems->bind_param("s", $username);
$userItems->execute();
$userItemsResult = $userItems->get_result();

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Gegenstand löschen</title>
    <style>
        body {font-family: Arial,sans-serif; color: #4a5568; background: #edf2f7; margin: 0; padding: 20px;}
        .container {max-width: 500px; margin: 50px auto; padding: 30px; background: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px -1px #0000001a;}
        form {margin-bottom: 20px;}
        select, input {width: 100%; padding: 10px; margin-bottom: 15px; font-size: 1em; color: #4a5568; border: 1px solid #e2e8f0; border-radius: 6px;}
        button {padding: 10px 20px; background: #e53e3e; color: #ffffff; font-size: 1em; border: none; border-radius: 6px; cursor: pointer;}
        button:hover {background: #c53030;}
        .msg {margin-bottom: 20px; padding: 10px; color: #ffffff; background: #e53e3e; border-radius: 6px;}
        .secondary-btn {background: #3182ce; margin-left: 0; width: 100%; margin-bottom: 10px;}
        .secondary-btn:hover {background: #2c5282;}
        .itemform {margin-bottom: 12px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f7fafc;}
        .itemname {font-weight: bold;}
    </style>
</head>
<body>
    <div class="container">
        <h1>Gegenstand aus Inventar entfernen</h1>
        <?php if (isset($msg)): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <a href="suche.php"><button class="secondary-btn" type="button">Suche</button></a>

        <?php if ($userItemsResult->num_rows > 0): ?>
            <h2>Dein Inventar</h2>
            <?php while ($row = $userItemsResult->fetch_assoc()): ?>
                <form class="itemform" action="" method="POST">
                    <span class="itemname"><?= htmlspecialchars($row['name']) ?></span>: <?= (int)$row['Anzahl'] ?>
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <label for="Anzahl_<?= $row['ID'] ?>">Anzahl verringern:</label>
                    <input id="Anzahl_<?= $row['ID'] ?>" name="Anzahl" type="number" min="1" max="<?= (int)$row['Anzahl'] ?>" required>
                    <button type="submit">Löschen</button>
                </form>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Du hast keine Gegenstände im Inventar.</p>
        <?php endif; ?>
    </div>
</body>
</html>
