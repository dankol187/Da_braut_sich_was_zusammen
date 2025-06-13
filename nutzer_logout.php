<?php
session_start();
session_unset();
session_destroy();
header("Location: logout_erfolgreich.php"); // Weiterleitung zur Login-Seite
exit();
?>
