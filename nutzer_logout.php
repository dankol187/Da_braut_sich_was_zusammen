<?php
session_start();
session_unset();
session_destroy();
header("Location: nutzer_login.php"); // Weiterleitung zur Login-Seite
exit();
?>
