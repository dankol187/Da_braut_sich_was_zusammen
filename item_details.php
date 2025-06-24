<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Ungültige Item-ID.";
    exit;
}

$id = (int)$_GET['id'];

// Hole das Item
$stmt = $conn->prepare("SELECT * FROM Item WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

$trank = null;
if ($item && $item['Klasse'] === 'Trank') {
    // Hole die Trank-Werte, falls das Item ein Trank ist
    $stmtTrank = $conn->prepare("SELECT * FROM Trank WHERE ItemID = ?");
    $stmtTrank->bind_param("i", $id);
    $stmtTrank->execute();
    $resultTrank = $stmtTrank->get_result();
    $trank = $resultTrank->fetch_assoc();
}

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Item Details</title>
    <style>
        body { font-family: Arial, sans-serif; background: #edf2f7; color: #4a5568; margin: 0; padding: 20px;}
        .container { max-width: 500px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 4px 6px -1px #0000001a;}
        .back-btn { display: inline-block; margin-bottom: 15px; padding: 10px 20px; background: #3182ce; color: #fff; border-radius: 6px; text-decoration: none; }
        .back-btn:hover { background: #2c5282; }
        .logout-btn { float: right; margin-top: -10px; padding: 8px 16px; background: #e53e3e; color: #fff; border-radius: 6px; text-decoration: none;}
        .logout-btn:hover { background: #c53030; }
        .details { padding: 18px; background: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;}
        h1 { margin-bottom: 20px;}
        .detail-row { margin-bottom: 10px;}
        .label { font-weight: bold; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <div class="clearfix">
            <a href="suche.php" class="back-btn">Zurück zur Suche</a>
            <a href="logout.php" class="logout-btn">Abmelden</a>
        </div>
        <h1>Item Details</h1>
        <?php if ($item): ?>
            <?php if ($item['Klasse'] === 'Trank' && $trank): ?>
                <div class="details">
                    <div class="detail-row"><span class="label">TrankID:</span> <?= htmlspecialchars($trank['TrankID']) ?></div>
                    <div class="detail-row"><span class="label">Art:</span> <?= htmlspecialchars($trank['Art']) ?></div>
                    <div class="detail-row"><span class="label">Stufe:</span> <?= htmlspecialchars($trank['Stufe']) ?></div>
                    <div class="detail-row"><span class="label">Name:</span> <?= htmlspecialchars($trank['Name']) ?></div>
                    <div class="detail-row"><span class="label">Dauer:</span> <?= htmlspecialchars($trank['Dauer']) ?></div>
                    <?php if (!empty($trank['Beschreibung'])): ?>
                        <div class="detail-row"><span class="label">Beschreibung:</span> <?= nl2br(htmlspecialchars($trank['Beschreibung'])) ?></div>
                    <?php endif; ?>
                    <!-- Item.ID für Entwickler, nicht sichtbar für Nutzer -->
                    <!-- <div class="detail-row"><span class="label">Item.ID:</span> <?= htmlspecialchars($item['ID']) ?></div> -->
                </div>
            <?php else: ?>
                <div class="details">
                    <div class="detail-row"><span class="label">ID:</span> <?= htmlspecialchars($item['ID']) ?></div>
                    <div class="detail-row"><span class="label">Name:</span> <?= htmlspecialchars($item['Name']) ?></div>
                    <?php if (!empty($item['beschreibung'])): ?>
                        <div class="detail-row"><span class="label">Beschreibung:</span> <?= nl2br(htmlspecialchars($item['beschreibung'])) ?></div>
                    <?php endif; ?>
                    <!-- Weitere Spalten hier ergänzen -->
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Kein Item mit dieser ID gefunden.</p>
        <?php endif; ?>
    </div>
</body>
</html>
