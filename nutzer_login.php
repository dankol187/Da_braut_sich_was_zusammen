<?php
session_start(); // Session starten

require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Benutzer anhand des Benutzernamens suchen
    $stmt = $conn->prepare("SELECT Benutzername, Passwort FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Wenn Benutzer existiert
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Passwort überprüfen
        if (password_verify($password, $row['Passwort'])) {
            // Erfolgreich eingeloggt
            $_SESSION['username'] = $username;
            echo "Login erfolgreich!";
            header("Location: login_erfolgreich.php"); // Weiterleitung nach Login
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

<!-- Minecraft-ähnliche Schrift mit Groß/Klein -->
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">

<!-- Klicksound -->
<audio id="clickSound" preload="auto">
  <source src="https://cdn.pixabay.com/audio/2022/03/15/audio_9ee2f53d82.mp3" type="audio/mpeg">
</audio>

<style>
body {
    margin: 0;
    font-family: 'VT323', monospace;
    background-image: url('https://i.imgur.com/Vz5bYTI.png'); /* Minecraft-Grastextur */
    background-size: 64px;
    background-repeat: repeat;
    color: white;
}

.container {
    max-width: 400px;
    margin: 100px auto;
    background: rgba(20, 20, 20, 0.9);
    padding: 20px;
    border: 3px solid #00ff00;
    text-align: center;
}

h1 {
    font-size: 32px;
    color: #00ff00;
    text-shadow: 2px 2px #000;
}

input, button {
    font-family: 'VT323', monospace;
    font-size: 20px;
    padding: 10px;
    width: 100%;
    margin: 10px 0;
    background: #111;
    color: #0f0;
    border: 2px solid #333;
}

button:hover {
    background: #0f0;
    color: #000;
    cursor: pointer;
}
</style>
</head>
<body>

<div class="container">
    <h1>Anmeldung</h1>
    <input type="text" placeholder="Benutzername">
    <input type="password" placeholder="Passwort">
    <button onclick="playSound()">Einloggen</button>
</div>

<script>
function playSound() {
    const audio = document.getElementById("clickSound");
    audio.currentTime = 0;
    audio.play();
}
</script>

</body>
</html>
