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
    $anzahl = (int)$_POST['Anzahl'];

    // Prüfen, wie viele der User aktuell hat
    $stmt = $conn->prepare("SELECT Anzahl FROM hat WHERE hat_Benutzername = ? AND hat_ItemID = ?");
    $stmt->bind_param("si", $username, $itemId);
    $stmt->execute();
    $stmt->bind_result($aktuelleAnzahl);
    $stmt->fetch();
    $stmt->close();

    if (!isset($aktuelleAnzahl)) {
        $msg = "Dieses Item befindet sich nicht in deinem Inventar.";
    } elseif ($anzahl <= 0) {
        $msg = "Bitte gib eine positive Anzahl an.";
    } elseif ($anzahl >= $aktuelleAnzahl) {
        // vollständiges Löschen
        $stmt = $conn->prepare("DELETE FROM hat WHERE hat_Benutzername = ? AND hat_ItemID = ?");
        $stmt->bind_param("si", $username, $itemId);
        if ($stmt->execute()) {
            $msg = "Das Item wurde komplett aus deinem Inventar entfernt.";
        } else {
            $msg = "Fehler beim Löschen.";
        }
    } else {
        // Teilweise löschen (Anzahl vermindern)
        $neueAnzahl = $aktuelleAnzahl - $anzahl;
        $stmt = $conn->prepare("UPDATE hat SET Anzahl = ? WHERE hat_Benutzername = ? AND hat_ItemID = ?");
        $stmt->bind_param("isi", $neueAnzahl, $username, $itemId);
        if ($stmt->execute()) {
            $msg = "Anzahl wurde reduziert.";
        } else {
            $msg = "Fehler beim Aktualisieren.";
        }
    }
}

// Hole alle Gegenstände des Users für das Formular
$userItems = $conn->prepare(
    "SELECT hat.ItemID, hat.Anzahl, Item.name FROM hat JOIN Item ON hat.hat_ItemID = Item.ID WHERE hat.hat_Benutzername = ?"
);
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
        body {font-family: Arial,sans-serif; color: #4a5568; background: #edf2f7; margin: 0;padding: 20px;}
        .container {max-width: 500px; margin: 50px auto;padding: 30px;background: #ffffff;border-radius: 10px;box-shadow: 0 4px 6px -1px #0000001a;}
        form {margin-bottom: 20px;}
        select, input {width: 100%;padding: 10px;margin-bottom: 15px;font-size: 1em;color: #4a5568;border: 1px solid #e2e8f0;border-radius: 6px;}
        button {padding: 10px 20px;background: #e53e3e;color: #ffffff;font-size: 1em;border: none;border-radius: 6px;cursor: pointer;}
        button:hover {background: #c53030;}
        .msg {margin-bottom: 20px;padding: 10px;color: #ffffff;background: #e53e3e;border-radius: 6px;}
    </style>
</head>
<body>
<div class="container">
    <h1>Gegenstand aus Inventar löschen</h1>
    <?php if (isset($msg)): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($userItemsResult->num_rows > 0): ?>
        <form action="" method="POST">
            <label for="id">Gegenstand:</label>
            <select name="id" id="id" required>
                <option value="">-- bitte auswählen --</option>
                <?php while ($item = $userItemsResult->fetch_assoc()) : ?>
                    <option value="<?= $item['ItemID']; ?>">
                        <?= htmlspecialchars($item['name']); ?> (aktuell: <?= (int)$item['Anzahl']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="Anzahl">Anzahl, die entfernt werden soll:</label>
            <input id="Anzahl" name="Anzahl" type="number" min="1" required>
            <button type="submit">Löschen</button>
        </form>
    <?php else: ?>
        <p>Du hast keine Gegenstände im Inventar.</p>
    <?php endif; ?>
    <a href="Inventar_hinzufügen.php"><button style="background:#3182ce;margin-top:10px;">Zurück zum Hinzufügen</button></a>
</div>
</body>
</html>
