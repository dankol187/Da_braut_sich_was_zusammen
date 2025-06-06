<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, zur Anmeldung weiterleiten
    header("Location: nutzer_login.php");
    exit;
}
// Benutzername steht jetzt in $_SESSION['username']
echo "Willkommen, " . htmlspecialchars($_SESSION['username']);
$name = htmlspecialchars($_SESSION['username']) ;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmeldung bestätigt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { margin: 50px auto; max-width: 400px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9;}
        .button { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Anmeldung erfolgreich!</h2>
        <p>Vielen Dank für deine Anmeldung.</p>
        <h3>Deine Daten:</h3>
        <ul>
            <li><strong>Name:</strong> <?php echo $name; ?></li>
        </ul>
        <form action="suche.php" method="get">
            <button class="button" type="submit">Zurück zur Suche</button>
        </form>
    </div>
</body>
</html>
