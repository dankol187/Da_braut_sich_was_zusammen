<?php
include('db.php');
require_once 'Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Überprüfen, ob der Benutzername existiert
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Passwort überprüfen
        if (password_verify($password, $user['password'])) {
            // Anmelden und Session setzen
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // Weiterleitung zur Startseite
            exit();
        } else {
            echo "Falsches Passwort.";
        }
    } else {
        echo "Benutzername nicht gefunden.";
    }
}
?>

<form method="POST">
    Benutzername: <input type="text" name="username" required><br>
    Passwort: <input type="password" name="password" required><br>
    <button type="submit">Einloggen</button>
</form>
