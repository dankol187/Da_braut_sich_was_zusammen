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
<title>Login</title>
<!-- Minecraft-inspirierte, aber moderne Schriftart -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">
<style>
body {
    margin: 0;
    min-height: 100vh;
    font-family: 'Montserrat', Arial, sans-serif;
    background:
        linear-gradient(rgba(34,40,49,0.82), rgba(34,40,49,0.96)),
        url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') center center/cover no-repeat;
    color: #e2e2e2;
}
.login-container {
    max-width: 370px;
    margin: 90px auto 0 auto;
    background: rgba(36,54,38,0.97) url('https://i.imgur.com/qTDKS4U.png') repeat;
    border: 3.5px solid #6c944d;
    border-radius: 16px;
    box-shadow: 0 8px 32px 4px #222b;
    padding: 36px 32px 28px 32px;
    position: relative;
}
.login-container::before {
    content: "";
    display: block;
    position: absolute;
    left: 50%;
    top: -64px;
    transform: translateX(-50%);
    width: 72px;
    height: 72px;
    border-radius: 12px;
    background: #222 url('https://i.imgur.com/9QX1b9C.png') center/cover no-repeat;
    border: 3.5px solid #6c944d;
    box-shadow: 0 2px 14px #222c;
}
.login-title {
    font-size: 1.4em;
    color: #d8eec9;
    text-shadow: 0 2px 8px #6c944d33;
    font-weight: 700;
    letter-spacing: 2px;
    margin-bottom: 24px;
}
.login-container form input {
    width: 100%;
    margin-bottom: 18px;
    padding: 12px 12px;
    border: 2px solid #8eab7d;
    background: #e6efdd url('https://i.imgur.com/6i1GM6T.png') repeat;
    border-radius: 7px;
    font-family: inherit;
    font-size: 1em;
    color: #24362a;
    letter-spacing: 1px;
    box-shadow: 0 1.5px #6c944d;
    outline: none;
    transition: border 0.25s, box-shadow 0.25s;
}
.login-container form input:focus {
    border: 2.2px solid #6c944d;
    box-shadow: 0 0 8px #6c944d90;
    background: #f1faee url('https://i.imgur.com/6i1GM6T.png') repeat;
}
.login-container form button {
    width: 100%;
    padding: 14px 0;
    background: linear-gradient(90deg, #567c3b 0%, #8ec07c 100%);
    color: #f3fff2;
    border: none;
    border-radius: 8px;
    font-size: 1.09em;
    font-family: inherit;
    letter-spacing: 1px;
    font-weight: 600;
    box-shadow: 0 2px #3d652e;
    cursor: pointer;
    margin-bottom: 8px;
    transition: background 0.18s, box-shadow 0.18s;
}
.login-container form button:hover, .login-container form button:focus {
    background: linear-gradient(90deg, #8ec07c 0%, #567c3b 100%);
    box-shadow: 0 0 9px #8ec07c88;
}
.error {
    color: #ef4b4b;
    margin-bottom: 18px;
    background: #ffededbb;
    border: 2px solid #d46a6a;
    border-radius: 6px;
    padding: 10px;
    font-size: 0.98em;
    text-align: left;
    box-shadow: 0 0 8px #e53e3e22;
}
.register-block {
    margin-top: 23px;
    text-align: center;
}
.register-block button {
    width: 100%;
    padding: 12px 0;
    background: #2d3e25 url('https://i.imgur.com/uhH1rI2.png') repeat;
    color: #f3fff2;
    border: 2px solid #789d5d;
    border-radius: 8px;
    font-size: 1.06em;
    font-family: inherit;
    letter-spacing: 1px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.18s, border 0.18s;
}
.register-block button:hover, .register-block button:focus {
    background: #8ec07c url('https://i.imgur.com/uhH1rI2.png') repeat;
    border: 2px solid #6c944d;
    color: #24362a;
}
@media (max-width: 500px) {
    .login-container {
        padding: 17px 6vw 22px 6vw;
        margin-top: 32px;
    }
    .login-container::before { width: 54px; height: 54px; top: -38px; }
}
</style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">Login</div>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="nutzer_login.php">
            <input type="text" name="username" placeholder="Benutzername" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Anmelden</button>
        </form>
        <div class="register-block">
            <form action="nutzer_registrierung.php" method="get">
                <button type="submit">Noch keinen Account? Jetzt registrieren</button>
            </form>
        </div>
    </div>
</body>
</html>
