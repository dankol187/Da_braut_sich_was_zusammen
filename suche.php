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
$username = htmlspecialchars($_SESSION['username']);

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            color: #4a5568;
        }
        .container {
            margin: 100px auto;
            max-width: 500px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px #0000001a;
            text-align: center;
        }
        .container form {
            margin-bottom: 20px;
        }
        .container ul {
            list-style-type: none;
            padding: 0;
        }
        .container ul li {
            margin-bottom: 10px;
            padding: 10px;
            background: #edf2f7;
            border-radius: 6px;
        }
        .container button {
            padding: 10px;
            background: #3182ce;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
        }
        .container button:hover {
            background: #2b6cb0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Willkommen, <?= $username; ?> </h1>

        <form method="get" action="suche.php">
            <input type="text" name="suchbegriff" value="<?= htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben" required>
            <button type="submit">Suchen</button>
        </form>

        <h2>Suchergebnisse:</h2>

        <?php if (isset($result)): ?>
            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li>ID: <?= $row['id']; ?> - Name: <?= htmlspecialchars($row['name']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Keine Ergebnisse gefunden.</p>
            <?php endif; ?>
        <?php endif; ?>

        <form action="nutzer_logout.php" method="post">
            <button type="submit" style="margin-top: 20px; background: #e53e3e;">
                Abmelden
            </button>
        </form>
    </div>
</body>
</html>
