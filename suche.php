<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();
$suchbegriff = "";

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: nutzer_login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['username']);

if (isset($_GET['suchbegriff'])) {
    $suchbegriff = $conn->real_escape_string($_GET['suchbegriff']);
    $sql = "SELECT id, name FROM Item WHERE name LIKE '%$suchbegriff%'";
    $result = $conn->query($sql);
}
$db->disconnect();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Suche</title>
    <style>
        /* ... dein bestehendes CSS ... */
        .autocomplete-list {
            position: absolute;
            z-index: 10;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-item:hover, .autocomplete-item.active {
            background: #3182ce;
            color: #fff;
        }
        .search-form {
            position: relative;
        }
    </style>
</head>
<body>
    <form action="nutzer_logout.php" method="post" class="logout">
        <button type="submit">Abmelden</button>
    </form>

    <div class="container">
        <a href="Inventar_anschauen.php" class="inventar-btn">Inventar anschauen</a>
        <h1>Willkommen, <?= $username; ?></h1>

       <form method="get" action="suche.php" class="search-form" autocomplete="off">
            <input type="text" id="suchbegriff" name="suchbegriff" value="<?= htmlspecialchars($suchbegriff); ?>" placeholder="Suchbegriff eingeben" required>
            <div id="autocomplete-list" class="autocomplete-list" style="display:none;"></div>
            <button type="submit">Suchen</button>
        </form>
        
        <div class="results">
            <h2>Suchergebnisse:</h2>
            <?php if (isset($result)): ?>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="result-card">
                            <strong>Name:</strong> <?= htmlspecialchars($row['name']); ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Keine Ergebnisse gefunden.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
    // Autocomplete-Logik
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('suchbegriff');
        const list = document.getElementById('autocomplete-list');
        let activeIndex = -1;
        let items = [];

        input.addEventListener('input', function() {
            const val = this.value;
            if (val.length === 0) {
                list.style.display = "none";
                return;
            }
            fetch("ajax_suche.php?q=" + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    items = data;
                    if (items.length > 0) {
                        items.forEach((item, idx) => {
                            const div = document.createElement("div");
                            div.className = "autocomplete-item";
                            div.innerHTML = item.name;
                            div.addEventListener('mousedown', function(e) {
                                input.value = item.name;
                                list.style.display = "none";
                            });
                            list.appendChild(div);
                        });
                        list.style.display = "block";
                    } else {
                        list.style.display = "none";
                    }
                });
        });

        // Keyboard navigation
        input.addEventListener('keydown', function(e) {
            let currentItems = list.querySelectorAll('.autocomplete-item');
            if (!currentItems.length) return;

            if (e.key === "ArrowDown") {
                activeIndex = (activeIndex + 1) % currentItems.length;
                setActive(currentItems);
                e.preventDefault();
            } else if (e.key === "ArrowUp") {
                activeIndex = (activeIndex - 1 + currentItems.length) % currentItems.length;
                setActive(currentItems);
                e.preventDefault();
            } else if (e.key === "Enter") {
                if (activeIndex > -1 && currentItems[activeIndex]) {
                    currentItems[activeIndex].dispatchEvent(new Event('mousedown'));
                    e.preventDefault();
                }
            } else {
                activeIndex = -1;
            }
        });

        document.addEventListener('click', function(e) {
            if (!list.contains(e.target) && e.target !== input) {
                list.style.display = "none";
            }
        });

        function setActive(items) {
            items.forEach((el, i) => {
                el.classList.toggle('active', i === activeIndex);
            });
        }
    });
    </script>
</body>
</html>
