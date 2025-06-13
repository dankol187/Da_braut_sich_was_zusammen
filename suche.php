<?php
require_once 'Database.php';
// rest of the code remains the same
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
            margin: 0;
        }
        .container {
            margin: 100px auto;
            max-width: 500px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px #0000001a;
            text-align: center;
            position: relative;
        }
        form.logout {
            position: fixed;
            top: 0;
            right: 0;
            margin: 20px;
        }
        form.logout button {
            padding: 10px;
            background: #e53e3e;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
        }
        form.logout button:hover {
            background: #c53030;
        }
        form[action="suche.php"] button {
            padding: 10px 20px;
            background: #3182ce;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
        }
        form[action="suche.php"] button:hover {
            background: #2c5282;
        }
    </style>
</head>
<body>
    <form action="nutzer_logout.php" method="post" class="logout">
        <button type="submit">
            Abmelden
        </button>
    </form>

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
    </div>
</body>
</html>
