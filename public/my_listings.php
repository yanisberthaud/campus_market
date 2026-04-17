<?php
session_start();
require_once '../Config/Database.php';

// Sécurité : Si l'utilisateur n'est pas connecté, on le renvoie à la page de login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/header.php';

$dbInstance = Database::getInstance();
$pdo = $dbInstance->getConnection();

// On récupère uniquement les annonces de l'utilisateur en session
$stmt = $pdo->prepare("SELECT * FROM listings WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$my_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    <h1 style="color: var(--duke-blue); margin-bottom: 30px;">Manage My Listings</h1>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            Item deleted successfully!
        </div>
    <?php endif; ?>

    <?php if (empty($my_items)): ?>
        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
            <p>You haven't listed any items yet.</p>
            <a href="list_item.php" class="btn_login" style="text-decoration: none; padding: 10px 20px; display: inline-block;">Post your first ad</a>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: var(--duke-blue); color: white; text-align: left;">
                    <th style="padding: 15px;">Item</th>
                    <th style="padding: 15px;">Price</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Actions</th>
                </tr>
            </thead> 
            <tbody>
                <?php foreach ($my_items as $item): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                            <img src="uploads/<?php echo htmlspecialchars($item['image_url'] ?: 'default.jpg'); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                        </td>
                        <td style="padding: 15px;">$<?php echo number_format($item['price'], 2); ?></td>
                        <td style="padding: 15px; font-size: 0.9rem; color: #666;">
                            <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                        </td>
                        <td style="padding: 15px;">
                            <a href="edit_listings.php?id=<?php echo $item['id']; ?>" 
                            class="btn-edit" 
                            style="flex: 1; color : #ffa908; text-align: center; padding: 8px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                            Edit
                            </a>

                            <a href="delete_listing.php?id=<?php echo $item['id']; ?>" 
                            class="btn-delete" 
                            onclick="return confirm('Are you sure you want to delete this item?');"
                            style="flex: 1; text-align: center; color: #e74c3c; padding: 8px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                            Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>