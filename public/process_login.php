<?php
session_start();
require_once '../Config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    try {
        $db = Database::getInstance()->getConnection();

        // 1. On cherche l'utilisateur par son email uniquement
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. On vérifie si l'utilisateur existe ET si le mot de passe correspond au hash
        if ($user && password_verify($password, $user['password'])) {
            
            // ✅ SUCCÈS : On crée la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];

            // Redirection vers l'accueil
            header("Location: index.php?status=back");
            exit();
        } else {
            // ❌ ÉCHEC : Mauvais email ou mot de passe
            header("Location: login.php?error=invalid");
            exit();
        }

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}