<?php
require_once('config.php');

header('Content-Type: application/json');

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT artist, title, label, pressing FROM catalogue WHERE artist LIKE :search LIKE :search OR title LIKE :search OR label LIKE :search OR pressing LIKE :search LIMIT 10");
    $stmt->execute(['search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} else {
    echo json_encode([]);
}
