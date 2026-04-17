<?php 

require_once '../Config/Database.php';

try { 
    $database = Database::getInstance();
    $db = $database->getConnection();

    echo "<h1> Connexion réussie !</h1>";

    // On récupère les catégories
    $query = "SELECT * FROM categories";
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo "<h3>Liste des catégories dans la base :</h3>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row['name'] . "</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

?>