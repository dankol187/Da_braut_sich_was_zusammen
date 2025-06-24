<?php
session_start();

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT Benutzername, Passwort FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Passwort'])) {
            $_SESSION['username'] = $username;
            echo "Login erfolgreich!";
            header("Location: login_erfolgreich.php");
            exit;
        } else {
            $error = "Falsches Passwort.";
        }
    } else {
        $error = "Benutzer wurde nicht gefunden.";
    }
}

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Minecraft Login</title>

<!-- Minecraft Schriftart einbinden -->
<link href="https://fonts.cdnfonts.com/css/minecraft-4" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Minecraft', sans-serif;
    background: url('https://i.imgur.com/AVz7S0J.png') repeat; /* Minecraft-Grasblock-Hintergrund */
    color: #fff;
}

.container {
    margin: 100px auto;
    max-width: 450px;
    padding: 30px;
    background: rgba(30, 30, 30, 0.95);
    border: 4px solid #3c3c3c;
    box-shadow: 0 0 0 4px #000, 0 8px 20px rgba(0, 0, 0, 0.5);
    text-align: center;
    border-radius: 0;
}

.container h1 {
    margin-bottom: 25px;
    font-size: 2em;
    color: #00ff00;
    text-shadow: 2px 2px #000;
}

.container form input {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 12px;
    font-family: 'Minecraft', sans-serif;
    font-size: 16px;
    background: #1a1a1a;
    color: #00ff00;
    border: 2px solid #3c3c3c;
    outline: none;
}

.container form input:focus {
    border-color: #00ff00;
    background: #2a2a2a;
}

.container form button {
    width: 100%;
    padding: 12px;
    background: #00aa00;
    color: #fff;
    font-size: 16px;
    border: 2px solid #3c3c3c;
    cursor: pointer;
    font-family: 'Minecraft', sans-serif;
    transition: background 0.2s;
}

.container form button:hover {
    background: #00cc00;
}

.container .error {
    color: #ff4444;
    background: #2a0000;
    border: 1px solid #ff0000;
    padding: 10px;
    margin-bottom: 15px;
    font-family: 'Minecraft', sans-serif;
}

.container .register-btn {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background: #333;
    color: #ccc;
    border: 2px solid #3c3c3c;
    cursor: pointer;
    font-family: 'Minecraft', sans-serif;
}

.container .register-btn:hover {
    background: #444;
    color: #fff;
}
</style>
</head>
<body>
    <div class="container">
        <h1>MineLogin</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="nutzer_login.php">
            <input type="text" name="username" placeholder="Benutzername" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Einloggen</button>
        </form>

        <form action="nutzer_registrierung.php" method="get">
            <button class="register-btn" type="submit">Registrieren</button>
        </form>
    </div>
</body>
</html>
