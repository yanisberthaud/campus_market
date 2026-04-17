<?php
session_start();
require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {

    $dbInstance = Database::getInstance();
    $pdo = $dbInstance->getConnection();
    
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $item_condition = $_POST['item_condition'];


    $image_name = null; // Par défaut, pas d'image

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $upload_dir = 'uploads/';
        
        $file_extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $image_name;

        // On déplace le fichier du dossier temporaire vers ton dossier /uploads
        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_path)) {
            die("Erreur lors du téléchargement de l'image physiquement sur le serveur.");
        }
    }

    try {
        $sql = "INSERT INTO listings (user_id, title, description, price, category_id, item_condition, status, image_url) 
                VALUES (:user_id, :title, :description, :price, :category_id, :item_condition, 'active', :image_url)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':category_id' => $category_id,
            ':item_condition' => $item_condition,
            ':image_url' => $image_name 
        ]);

        header("Location: browse.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'ajout : " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}