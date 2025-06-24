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
<title>Minecraft Login</title>
<!-- Minecraft-Pixel-Font (alternativ einbinden, wenn möglich) -->
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
<style>
body {
    /* Minecraft Grasblock-Textur als Hintergrund */
    background: url("https://static.planetminecraft.com/files/image/minecraft/texture-pack/2021/550/14618895-grass-block-top_m.jpg") repeat;
    color: #222;
    font-family: 'Press Start 2P', 'Minecraftia', 'Arial', sans-serif;
    letter-spacing: 1px;
}
.container {
    margin: 100px auto;
    max-width: 420px;
    padding: 30px 30px 20px 30px;
    /* Minecraft Cobblestone-Block als Textur */
    background: url('https://static.planetminecraft.com/files/resource_media/screenshot/1211/cobblestone_6505418.jpg') repeat;
    border-radius: 16px;
    box-shadow: 0 0 32px 8px #0009, 0 4px 0 6px #6b6b6b;
    text-align: center;
    border: 6px solid #3d652e;
    position: relative;
}
.container:before {
    /* Pixelart Minecraft-Schwert als Dekoration oben */
    content: '';
    display: block;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: -64px;
    width: 64px;
    height: 64px;
    background: url('https://static.wikia.nocookie.net/minecraft_gamepedia/images/4/4e/Diamond_Sword_JE3_BE3.png') no-repeat center/contain;
}
.container h1 {
    margin-bottom: 32px;
    color: #51b13c;
    text-shadow: 2px 2px #222, 0 0 8px #96e26d;
    font-size: 1.6em;
    letter-spacing: 2px;
    background: #222a;
    padding: 10px 0;
    border-radius: 8px;
    border: 3px solid #7aa24a;
    box-shadow: 0 0 8px #8ccf61;
}
.container form input {
    display: block;
    width: 100%;
    margin-bottom: 18px;
    padding: 13px 12px;
    border: 2px solid #7aa24a;
    background: #d2f7af url('https://static.planetminecraft.com/files/resource_media/screenshot/1211/planks_oak_6505418.jpg') repeat;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1em;
    color: #222;
    letter-spacing: 1px;
    box-shadow: 0 2px #3d652e;
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
}
.container form input:focus {
    border: 2px solid #1fa02e;
    box-shadow: 0 0 8px #51b13c;
}
.container form button {
    width: 100%;
    padding: 15px 0;
    background: #51b13c url('https://static.wikia.nocookie.net/minecraft_gamepedia/images/1/11/Creeper_Face.png') no-repeat 96% center/32px 32px;
    color: #fff;
    border: 3px solid #1fa02e;
    border-radius: 8px;
    font-size: 1.1em;
    font-family: inherit;
    letter-spacing: 2px;
    cursor: pointer;
    margin-bottom: 6px;
    box-shadow: 0 2px #3d652e;
    text-shadow: 1px 1px #222;
    transition: background 0.2s, box-shadow 0.2s;
    position: relative;
}
.container form button:active {
    background: #3a8026;
    box-shadow: 0 0 8px #1fa02e;
}
.container .error {
    color: #e53e3e;
    margin-bottom: 24px;
    background: #fff3;
    border: 2px dashed #e53e3e;
    border-radius: 7px;
    padding: 12px;
    font-size: 0.95em;
    letter-spacing: 1px;
    box-shadow: 0 0 8px #e53e3e44;
}
.register-btn {
    width: 100%;
    padding: 13px 0;
    background: #8b5a2b url('https://static.planetminecraft.com/files/resource_media/screenshot/1211/log_oak_6505418.jpg') repeat;
    color: #fff;
    border: 3px solid #ad6c2b;
    border-radius: 8px;
    font-size: 1.1em;
    font-family: inherit;
    letter-spacing: 2px;
    cursor: pointer;
    margin-top: 10px;
    box-shadow: 0 2px #3e2d19;
    text-shadow: 1px 1px #222;
    transition: background 0.2s, box-shadow 0.2s;
    position: relative;
}
.register-btn:active {
    background: #eac16c;
    color: #222;
    box-shadow: 0 0 8px #ad6c2b;
}
/* Minecraft-typischer Rahmen */
.container, .container form input, .container form button, .register-btn {
    image-rendering: pixelated;
}
::-webkit-input-placeholder { color: #5d7d4a; opacity: 1; }
::-moz-placeholder { color: #5d7d4a; opacity: 1; }
:-ms-input-placeholder { color: #5d7d4a; opacity: 1; }
::placeholder { color: #5d7d4a; opacity: 1; }
</style>
</head>
<body>
    <div class="container">
        <h1>⛏️ Minecraft Login</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="nutzer_login.php">
            <input type="text" name="username" placeholder="Spielername" required>
            <input type="password" name="password" placeholder="Passwort (Diamant-geschützt!)" required>
            <button type="submit">Login <span style="vertical-align: middle;">&#x1F47D;</span></button>
        </form>

        <div style="margin-top: 20px;">
            <form action="nutzer_registrierung.php" method="get">
                <button type="submit" class="register-btn">
                    <img src="https://static.wikia.nocookie.net/minecraft_gamepedia/images/6/6c/Oak_Planks_JE3_BE3.png" alt="" style="width:18px;vertical-align:middle;margin-right:8px;">Registrieren
                </button>
            </form>
        </div>
    </div>
</body>
</html>
