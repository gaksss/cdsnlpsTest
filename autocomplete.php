<?php
require_once('config.php');

header('Content-Type: application/json');

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT artist, title FROM catalogue WHERE artist LIKE :search OR title LIKE :search LIMIT 10");
    $stmt->execute(['search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} else {
    echo json_encode([]);
}
