<?php
session_start();
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Überprüfen, ob der Benutzername bereits existiert
    $stmt = $conn->prepare("SELECT * FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Benutzername ist bereits vergeben.";
    } else {
        // Benutzer in die Datenbank einfügen
        $stmt = $conn->prepare("INSERT INTO Nutzer (Benutzername, EMail, Passwort) VALUES (?,?,?)");
        $stmt->bind_param("sss",$username, $email, $password);
        $stmt->execute();
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        header("Location: registrierung_erfolgreich.php"); // Weiterleitung
        exit();
    }
}

$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Registrierung</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #edf2f7;
    color: #4a5568;
}
.container {
    margin: 100px auto;
    max-width: 400px;
    padding: 30px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px -1px #0000001a;
    text-align: center;
}
.container h1 {
    margin-bottom: 20px;
    color: #2d3748;
}
.container form input {
    display: block;
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #cbd5e0;
    border-radius: 6px;
}
.container form button {
    width: 100%;
    padding: 10px;
    background: #3182ce;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
}
.container form button:hover {
    background: #2b6cb0;
}
.container .error {
    color: #e53e3e;
    margin-bottom: 15px;
}
</style>
</head>
<body>
    <div class="container">
        <h1>Registrierung</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Benutzername" required>
            <input type="email" name="email" placeholder="E-Mail" required>
            <input type="password" name="password" placeholder="Passwort" required>
            <button type="submit">Registrieren</button>
        </form>

        <div style="margin-top: 20px;">
            <form action="nutzer_login.php" method="get">
                <button type="submit" style="width: 100%; padding: 10px; background: #edf2f7; color: #4a5568; border: 1px solid #cbd5e0; border-radius: 6px; cursor: pointer;">
                    Zum Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>

