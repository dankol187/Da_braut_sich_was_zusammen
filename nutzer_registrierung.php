<?php
require_once 'Database.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Passwort verschlüsseln

    // Überprüfen, ob der Benutzername bereits existiert
    $stmt = $conn->prepare("SELECT * FROM Nutzer WHERE Benutzername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->rowCount() > 0) {
        echo "Benutzername ist bereits vergeben.";
    } else {
        // Benutzer in die Datenbank einfügen
        $stmt = $conn->prepare("INSERT INTO Nutzer (Benutzername, E-Mail, Passwort) VALUES (:username, :email, :password)");
        $stmt->execute(['Benutzername' => $username, 'E-Mail' => $email, 'Passwort' => $password]);
        echo "Registrierung erfolgreich!";
        header("Location: index.php"); // Weiterleitung zur Startseite
            exit();
    }
}
    $db->disconnect();
?>

<form method="POST">
    Benutzername: <input type="text" name="username" required><br>
    E-Mail: <input type="email" name="email" required><br>
    Passwort: <input type="password" name="password" required><br>
    <button type="submit">Registrieren</button>
</form>

