<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Benutzer ist nicht angemeldet, zur Anmeldung weiterleiten
    header("Location: nutzer_login.php");
    exit;
}
$name = htmlspecialchars($_SESSION['username']);
$email = htmlspecialchars($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung bestätigt</title>
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
        .container h2 {
            margin-bottom: 20px;
            color: #2d3748;
        }
        .container ul {
            list-style-type: none;
            padding: 0;
        }
        .container ul li {
            margin-bottom: 10px;
        }
        .container .button {
            margin-top: 20px;
            padding: 10px;
            background: #3182ce;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
        }
        .container .button:hover {
            background: #2b6cb0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registrierung erfolgreich!</h2>
        <p>Vielen Dank für deine Registrierung.</p>
        <h3>Deine Daten:</h3>
        <ul>
            <li><strong>Name:</strong> <?= $name; ?></li>
            <li><strong>E-Mail:</strong> <?= $email; ?></li>
        </ul>
        <form action="suche.php" method="get">
            <button class="button" type="submit">Zurück zur Suche</button>
        </form>
    </div>
</body>
</html>
