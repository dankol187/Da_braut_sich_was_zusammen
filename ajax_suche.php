<?php
require_once 'Database.php';
$db = new Database();
$conn = $db->connect();

header('Content-Type: application/json');

$suchbegriff = "";
if (isset($_GET['q'])) {
    $suchbegriff = $conn->real_escape_string($_GET['q']);
    $result = $conn->query("SELECT id, name FROM Item WHERE name LIKE '%$suchbegriff%' LIMIT 10");
    $out = [];
    while($row = $result->fetch_assoc()) {
        $out[] = $row;
    }
    echo json_encode($out);
}
$db->disconnect();
