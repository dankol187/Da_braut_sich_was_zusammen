<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Abmeldung erfolgreich</title>
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
    margin-bottom: 20px;
}
.button {
    width: 100%;
    padding: 10px;
    background: #3182ce;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    font-size: 1em;
    cursor: pointer;
    margin-top: 15px;
}
.button:hover {
    background: #2b6cb0;
}
</style>
</head>
<body>
<div class="container">
    <h2>Abmeldung erfolgreich!</h2>
    <p>Du wurdest abgemeldet.</p>
    <form action="nutzer_login.php" method="get">
        <button class="button" type="submit">Zurück zur Anmeldung</button>
    </form>
    <form action="nutzer_registrierung.php" method="get">
        <button class="button" type="submit">Zur Registrierung</button>
    </form>
</div>
</body>
</html>
