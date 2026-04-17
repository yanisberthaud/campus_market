<?php
session_start();
require_once '../Config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $dbInstance = Database::getInstance();
    $pdo = $dbInstance->getConnection();

    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    // 1. On prépare la requête de base
    $sql = "UPDATE listings SET title = :title, category_id = :category_id, price = :price, description = :description WHERE id = :id AND user_id = :user_id";
    $params = [
        'title' => $title,
        'category_id' => $category_id,
        'price' => $price,
        'description' => $description,
        'id' => $id,
        'user_id' => $user_id
    ];

    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($params)) {
        header('Location: my_listings.php?status=updated');
    } else {
        echo "Error updating listing.";
    }
}