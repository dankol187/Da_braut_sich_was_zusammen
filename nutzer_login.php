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
<title>Anmeldung</title>

<!-- Minecraft-ähnliche Schriftart mit Groß-/Kleinschreibung -->
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

<!-- Minecraft Klicksound -->
<audio id="clickSound" preload="auto">
  <source src="https://cdn.pixabay.com/audio/2022/03/15/audio_9ee2f53d82.mp3" type="audio/mpeg">
</audio>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'VT323', monospace;
    background: url('https://i.imgur.com/Vz5bYTI.png') repeat;
    background-size: 64px;
    color: #ffffff;
}

.container {
    margin: 100px auto;
    max-width: 450px;
    padding: 30px;
    background: rgba(20, 20, 20, 0.95);
    border: 4px solid #3c3c3c;
    box-shadow: 0 0 0 4px #000, 0 8px 20px rgba(0, 0, 0, 0.5);
    text-align: center;
}

.container h1 {
    margin-bottom: 25px;
    font-size: 36px;
    color: #00ff00;
    text-shadow: 2px 2px #000;
    letter-spacing: 1px;
}

.container form input {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 12px;
    font-size: 20px;
    background: #1a1a1a;
    color: #00ff00;
    border: 2px solid #3c3c3c;
    outline: none;
}

.container form input:focus {
    border-color: #00ff00;
    background: #2a2a2a;
}

.container form button,
.container .register-btn {
    width: 100%;
    padding: 12px;
    background: #00aa00;
    color: #ffffff;
    font-size: 20px;
    border: 2px solid #3c3c3c;
    cursor: pointer;
    transition: background 0.2s;
}

.container form button:hover,
.container .register-btn:hover {
    background: #00cc00;
}

.container .register-btn {
    margin-top: 20px;
    background: #444;
    color: #ccc;
}

.container .register-btn:hover {
    background: #555;
    color: #fff;
}

.container .error {
    color: #ff4444;
    background: #2a0000;
    border: 1px solid #ff0000;
    padding: 10px;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<script>
function playSound() {
    const sound = document.getElementById("clickSound");
    if (sound) {
        sound.currentTime = 0;
        sound.play().catch(err => console.warn("Soundfehler:", err));
    }
}
</script>

<div class="container">
    <h1>Anmeldung</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="nutzer_login.php" onsubmit="playSound()">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit" onclick="playSound()">Einloggen</button>
    </form>

    <form action="nutzer_registrierung.php" method="get">
        <button class="register-btn" type="submit" onclick="playSound()">Registrieren</button>
    </form>
</div>
</body>
</html>
