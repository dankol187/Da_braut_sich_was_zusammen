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

$stmt = $conn->prepare("SELECT * FROM Item WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

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
        .details { padding: 18px; background: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;}
        h1 { margin-bottom: 20px;}
        .detail-row { margin-bottom: 10px;}
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <a href="suche.php" class="back-btn">Zurück zur Suche</a>
        <h1>Item Details</h1>
        <?php if ($item): ?>
            <div class="details">
                <div class="detail-row"><span class="label">ID:</span> <?= htmlspecialchars($item['id']) ?></div>
                <div class="detail-row"><span class="label">Name:</span> <?= htmlspecialchars($item['name']) ?></div>
                <?php if (!empty($item['beschreibung'])): ?>
                    <div class="detail-row"><span class="label">Beschreibung:</span> <?= nl2br(htmlspecialchars($item['beschreibung'])) ?></div>
                <?php endif; ?>
                <!-- Falls du weitere Spalten hast, ergänze sie hier! -->
            </div>
        <?php else: ?>
            <p>Kein Item mit dieser ID gefunden.</p>
        <?php endif; ?>
    </div>
</body>
</html>
