<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../Config/Database.php';

// 1. Vérification de connexion
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: browse.php');
    exit();
}

$dbInstance = Database::getInstance();
$pdo = $dbInstance->getConnection();

// 2. SUPÉRIEUR SÉCURITÉ : On vérifie que l'item appartient bien à l'user connecté
$stmt = $pdo->prepare("DELETE FROM listings WHERE id = :id AND user_id = :user_id");
$success = $stmt->execute([
    'id' => $_GET['id'],
    'user_id' => $_SESSION['user_id']
]);

// 3. Redirection
header('Location: my_listings.php?msg=deleted');
exit();