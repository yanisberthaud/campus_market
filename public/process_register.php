<?php
// On démarre la session pour pouvoir y stocker les infos de l'utilisateur tout de suite
session_start();
require_once '../Config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Récupération des données du formulaire
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 2. Validation de base
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        die("Please fill in all fields.");
    }

    // 3. Sécurité : Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db = Database::getInstance()->getConnection();

        $query = "INSERT INTO users (first_name, last_name, email, password, role) 
                  VALUES (:fname, :lname, :email, :password, 'student')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':fname', $first_name);
        $stmt->bindParam(':lname', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            $new_user_id = $db->lastInsertId();
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['role'] = 'student';

            header("Location: index.php?status=new");
            exit();
        }
    } catch (PDOException $e) {
        // Si l'email existe déjà (Code 23000)
        if ($e->getCode() == 23000) {
            // 🔄 On renvoie vers le formulaire avec un paramètre d'erreur
            header("Location: register.php?error=exists");
            exit();
        } else {
            die("Database Error: " . $e->getMessage());
        }
    }
} else {
    // Si on tente d'accéder au fichier sans passer par le formulaire
    header("Location: register.php");
    exit();
}