<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Suche in der Datenbank</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        .suche-box { max-width: 400px; margin: auto; }
        input[type="text"] { width: 70%; padding: 8px; }
        input[type="submit"] { padding: 8px 16px; }
    </style>
</head>
<body>
    <div class="suche-box">
        <h2>Suche in der Datenbank</h2>
        <form action="suche.php" method="get">
            <input type="text" name="suche" placeholder="Suchbegriff eingeben" required>
            <input type="submit" value="Suchen">
        </form>
    </div>
</body>
</html>
