<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}
$name = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Anmeldung bestätigt</title>
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
        h2 {
            color: #2d3748;
            margin-bottom: 18px;
        }
        .daten {
            list-style: none;
            padding: 0;
        }
        .daten li {
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        .button, .logout-btn {
            width: 100%;
            padding: 10px;
            background: #3182ce;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
        }
        .button:hover, .logout-btn:hover {
            background: #2b6cb0;
        }
        .logout-form {
            position: absolute;
            top: 18px;
            right: 18px;
        }
    </style>
</head>
<body>
    <form class="logout-form" action="nutzer_logout.php" method="post">
        <button class="logout-btn" type="submit">Abmelden</button>
    </form>
    <div class="container">
        <h2>Anmeldung erfolgreich!</h2>
        <p>Vielen Dank für deine Anmeldung.</p>
        <h3>Deine Daten:</h3>
        <ul class="daten">
            <li><strong>Name:</strong> <?= $name ?></li>
        </ul>
        <form action="suche.php" method="get">
            <button class="button" type="submit">Zurück zur Suche</button>
        </form>
    </div>
</body>
</html>
