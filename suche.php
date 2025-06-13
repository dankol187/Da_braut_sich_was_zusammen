<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();
$suchbegriff = "";

session_start();
if (!isset($_SESSION['username'])) {
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
            margin: 0;
        }
        .container {
            margin: 100px auto;
            max-width: 600px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px #0000001a;
            position: relative;
        }
        form.logout {
            position: fixed;
            top: 10px;
            right: 10px;
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
        .results {
            margin-top: 20px;
            text-align: left;
        }
        .result-card {
            padding: 15px;
            margin-bottom: 10px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .result-card strong {
            color: #2d3748;
        }
        .inventar-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #38a169;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    text-decoration: none;
    margin-bottom: 20px;
    transition: background 0.2s;
}
.inventar-btn:hover {
    background: #2f855a;
}
.search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.search-form input[type="text"] {
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    font-size: 1em;
    flex: 1;
    outline: none;
    transition: border-color 0.2s;
}
.search-form input[type="text"]:focus {
    border-color: #3182ce;
}
.search-form button {
    padding: 10px 20px;
    background: #3182ce;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.2s;
}
.search-form button:hover {
    background: #2c5282;
}
    </style>
</head>
<body>
    <form action="nutzer_logout.php" method="post" class="logout">
        <button type="submit">Abmelden</button>
    </form>

    <div class="container">
        <a href="Inventar_anschauen.php" class="inventar-btn">Inventar anschauen</a>
        <h1>Willkommen, <?= $username; ?></h1>

       <form method="get" action="suche.php" class="search-form">
    <input type="text" name="suchbegriff" value="<?= htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben" required>
    <button type="submit">Suchen</button>
</form>
        
        <div class="results">
            <h2>Suchergebnisse:</h2>
            <?php if (isset($result)): ?>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="result-card">
                           <!-- <strong>ID:</strong> <?= $row['id']; ?> <br> -->
                            <strong>Name:</strong> <?= htmlspecialchars($row['name']); ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Keine Ergebnisse gefunden.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
