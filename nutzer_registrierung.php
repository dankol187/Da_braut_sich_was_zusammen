<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Passwort verschlüsseln

    // Überprüfen, ob der Benutzername bereits existiert
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->rowCount() > 0) {
        echo "Benutzername ist bereits vergeben.";
    } else {
        // Benutzer in die Datenbank einfügen
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);
        echo "Registrierung erfolgreich!";
        header("Location: index.php"); // Weiterleitung zur Startseite
            exit();
    }
}
?>

<form method="POST">
    Benutzername: <input type="text" name="username" required><br>
    E-Mail: <input type="email" name="email" required><br>
    Passwort: <input type="password" name="password" required><br>
    <button type="submit">Registrieren</button>
</form>

