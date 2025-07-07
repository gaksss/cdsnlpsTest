<?php
$host = 'localhost';
$db = 'cdsnlps';
$user = 'root'; // ou autre utilisateur
$pass = '';     // ou ton mot de passe
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
