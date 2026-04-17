<?php
include '../includes/header.php';
require_once '../Config/Database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$dbInstance = Database::getInstance();
$pdo = $dbInstance->getConnection();

// On récupère l'ID de l'article depuis l'URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: my_listings.php');
    exit();
}

// Sécurité : On vérifie que l'article existe ET appartient bien à l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "<div class='container'><p>Access denied or item not found.</p></div>";
    exit();
}

// Liste des catégories pour le menu déroulant
$categories = [
    1 => 'Textbooks',
    2 => 'Electronics',
    3 => 'Dorm Decor',
    4 => 'School Supplies',
    5 => 'Clothing'
];
?>

<div class="container" style="padding: 40px; max-width: 800px; margin: 0 auto;">
    <h1 style="color: var(--duke-blue);">Edit your listing</h1>
    
    <form action="process_edit.php" method="POST" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Category</label>
            <select name="category_id" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <?php foreach ($categories as $catId => $catName): ?>
                    <option value="<?php echo $catId; ?>" <?php echo ($item['category_id'] == $catId) ? 'selected' : ''; ?>>
                        <?php echo $catName; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?php echo $item['price']; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
            <textarea name="description" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;"><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Current Image</label>
            <?php if ($item['image_url']): ?>
                <img src="uploads/<?php echo $item['image_url']; ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; display: block; margin-bottom: 10px;">
            <?php endif; ?>
            <label style="display: block; font-size: 0.9rem; color: #666;">Change image (leave empty to keep current):</label>
            <input type="file" name="image" style="margin-top: 5px;">
        </div>

        <button type="submit" class="btn_login" style="width: 100%; padding: 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 1.1rem;">
            Update Listing
        </button>
        
        <a href="my_listings.php" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>