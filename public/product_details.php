<?php
include '../includes/header.php';
require_once '../Config/Database.php';

// 1. On récupère l'ID de l'article dans l'URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$item = null; // On initialise la variable pour éviter les erreurs plus bas

if ($item_id > 0) {
    $dbInstance = Database::getInstance();
    $pdo = $dbInstance->getConnection();

    // 2. LA REQUÊTE SQL (Correction : first_name et last_name)
    $sql = "SELECT l.*, u.first_name, u.last_name 
            FROM listings l 
            JOIN users u ON l.user_id = u.id 
            WHERE l.id = :id AND l.status = 'active'";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $item_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}

// 3. Si l'article n'existe pas ou n'est pas actif
if (!$item) {
    echo "<div class='container' style='padding:50px; text-align:center;'>
            <h2>Item not found</h2>
            <p>The listing might have been removed or sold.</p>
            <a href='browse.php'>Back to Marketplace</a>
          </div>";
    include '../includes/footer.php';
    exit();
}
?>

<div class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    <a href="browse.php" style="text-decoration: none; color: var(--duke-blue); font-weight: bold;">← Back to Marketplace</a>

    <div style="display: flex; flex-wrap: wrap; gap: 40px; margin-top: 20px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <div style="flex: 1; min-width: 300px;">
            <?php if (!empty($item['image_url'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($item['image_url']); ?>" 
                     style="width: 100%; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <?php else: ?>
                <div style="width: 100%; height: 300px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                    No Image Available
                </div>
            <?php endif; ?>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <h1 style="color: var(--duke-blue); margin-top: 0;"><?php echo htmlspecialchars($item['title']); ?></h1>
            <p style="font-size: 1.5rem; font-weight: bold; color: #2ecc71;">$<?php echo number_format($item['price'], 2); ?></p>
            
            <div style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 4px;">
                <p><strong>Condition:</strong> <?php echo ucfirst(htmlspecialchars($item['item_condition'])); ?></p>
                <p><strong>Seller:</strong> <?php echo htmlspecialchars($item['first_name'] . ' ' . $item['last_name']); ?></p>
                <p><strong>Listed on:</strong> <?php echo date('M d, Y', strtotime($item['created_at'])); ?></p>
            </div>

            <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px;">Description</h3>
            <p style="line-height: 1.6; color: #444;">
                <?php echo nl2br(htmlspecialchars($item['description'])); ?>
            </p>

            <button class="btn_login" style="width: 100%; margin-top: 20px; padding: 15px; font-size: 1.1rem; cursor: pointer;">
                Contact Seller
            </button>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>