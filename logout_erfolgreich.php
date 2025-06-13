<?php
session_start(); // Sitzung starten
session_destroy(); // Alle Sitzungsvariablen zerstören

?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Logout erfolgreich</title>
<style>
body {font-family: Arial, sans-serif;}
.container {margin: 50px auto; max-width: 400px; padding: 20px; border: 1px solid #dadada; border-radius: 8px; background: #f9f9f9;}
.button {margin-top: 20px;}
</style>
</head>
<body>
<div class="container">
<h2>Logout erfolgreich!</h2>
<p>Du wurdest ausgeloggt.</p>
<form action="nutzer_login.php" method="get">
<button class="button" type="submit">Zurück zur Anmeldung</button>
</form>
<form action="nutzer_registrierung.php" method="get">
<button class="button" type="submit">Zurück zur Registrierung</button>
</form>
</div>
</body>
</html>
