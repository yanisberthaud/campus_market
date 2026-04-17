<?php
session_start();

// 1. On vide toutes les variables de session
$_SESSION = array();

// 2. On détruit physiquement la session sur le serveur
session_destroy();

// 3. On redirige vers l'accueil (qui affichera maintenant "Sign In")
header("Location: index.php");
exit();
?>