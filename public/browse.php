<?php
include '../includes/header.php';
require_once '../Config/Database.php';

$dbInstance = Database::getInstance();
$pdo = $dbInstance->getConnection();

// 1. Paramètres de base
$items_per_page = 12; // Nombre d'articles par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $items_per_page;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// 2. CONSTRUCTION DE LA REQUÊTE DE COMPTAGE (Pour savoir combien il y a de pages au total)
$count_query = "SELECT COUNT(*) FROM listings WHERE status = 'active'";
$params = [];

if (!empty($search)) {
    $count_query .= " AND (title LIKE :search OR description LIKE :search)";
    $params['search'] = '%' . $search . '%';
}
if (!empty($category)) {
    $count_query .= " AND category_id = :category";
    $params['category'] = $category;
}

$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute($params);
$total_items = $stmt_count->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// 3. REQUÊTE PRINCIPALE AVEC LIMIT ET OFFSET
$query = "SELECT * FROM listings WHERE status = 'active'";
// ... (On garde tes blocs IF pour search et category comme avant) ...
if (!empty($search)) { $query .= " AND (title LIKE :search OR description LIKE :search)"; }
if (!empty($category)) { $query .= " AND category_id = :category"; }

// Gestion du tri (ton switch précédent)
switch ($sort) {
    case 'oldest': $order = " ASC"; break;
    case 'price_asc': $order = " ASC"; break; // Note: ici il faudrait trier par price
    case 'price_desc': $order = " DESC"; break;
    default: $order = " DESC"; break;
}
// Ajuste ton switch pour qu'il définisse la colonne ET le sens
$query .= " ORDER BY created_at $order"; 

// AJOUT DE LA LIMITATION
$query .= " LIMIT $items_per_page OFFSET $offset";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: var(--duke-blue); margin: 0;">Browse Marketplace</h1>

        <div class="category-filter" style="margin-bottom: 30px; display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="browse.php" 
            style="text-decoration: none; padding: 8px 16px; border-radius: 20px; background: <?php echo empty($category) ? 'var(--duke-blue)' : '#eee'; ?>; color: <?php echo empty($category) ? 'white' : '#333'; ?>; font-weight: bold;">
            All Items
            </a>
            
            <?php
            // Liste de tes catégories (à adapter selon ta base de données)
            $categories = [
                1 => 'Textbooks',
                2 => 'Electronics',
                3 => 'Dorm Decor',
                4 => 'School Supplies',
                5 => 'Clothing'
            ];

            foreach ($categories as $id => $name): ?>
                <a href="browse.php?category=<?php echo $id; ?><?php echo !empty($search) ? '&search='.urlencode($search) : ''; ?>" 
                style="text-decoration: none; padding: 8px 16px; border-radius: 20px; background: <?php echo ($category == $id) ? 'var(--duke-blue)' : '#eee'; ?>; color: <?php echo ($category == $id) ? 'white' : '#333'; ?>; transition: 0.3s;">
                <?php echo $name; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="sort-filter" style="margin-bottom: 20px; text-align: right;">
            <form action="browse.php" method="GET" style="display: inline-block;">
                <?php if(!empty($category)): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <?php endif; ?>
                <?php if(!empty($search)): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>

                <label for="sort" style="font-weight: bold; margin-right: 10px;">Sort by:</label>
                <select name="sort" id="sort" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                    <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                    <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </form>
        </div>
        
        <?php if (!empty($search)): ?>
            <div style="background: #e2e8f0; padding: 10px 20px; border-radius: 20px; display: flex; align-items: center; gap: 10px;">
                <span>Results for: <strong>"<?php echo htmlspecialchars($search); ?>"</strong></span>
                <a href="browse.php" style="text-decoration: none; color: #ff0000; font-weight: bold; font-size: 1.2rem;">&times;</a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px; border: 1px dashed #ccc;">
            <p style="font-size: 1.2rem; color: #666;">No items found matching your criteria.</p>
            <a href="browse.php" style="color: var(--duke-blue); font-weight: bold;">View all listings</a>
        </div>
    
    <?php else: ?>
        <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            <?php foreach ($items as $item): ?>
                <div class="product-card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: white; transition: transform 0.2s; display: flex; flex-direction: column;">
                    
                    <div class="product-image" style="height: 200px; background-color: #f4f4f4; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span style="color: #999;">No Image Yet</span>
                        <?php endif; ?>
                    </div>

                    <div class="product-info" style="padding: 15px; flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 style="margin: 0 0 10px 0; font-size: 1.1rem; color: var(--duke-blue);">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h3>
                        
                        <p style="color: #666; font-size: 0.85rem; height: 40px; overflow: hidden; margin-bottom: 15px;">
                            <?php echo htmlspecialchars(substr($item['description'], 0, 80)) . '...'; ?>
                        </p>

                        <div style="margin-top: auto;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <span style="font-weight: bold; color: #2ecc71; font-size: 1.2rem;">
                                    $<?php echo number_format($item['price'], 2); ?>
                                </span>
                                <span style="font-size: 0.75rem; background: #eee; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">
                                    <?php echo htmlspecialchars($item['item_condition']); ?>
                                </span>
                            </div>
                            <a href="product_details.php?id=<?php echo $item['id']; ?>" class="btn_login" style="display: block; text-align: center; text-decoration: none; padding: 10px; border-radius: 4px;">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($total_pages > 1): ?>
<div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 40px; margin-bottom: 40px;">
    
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" 
           style="padding: 10px 15px; background: #eee; text-decoration: none; border-radius: 4px; color: #333;">« Previous</a>
    <?php endif; ?>

    <span style="padding: 10px 15px; font-weight: bold;">
        Page <?php echo $page; ?> of <?php echo $total_pages; ?>
    </span>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" 
           style="padding: 10px 15px; background: var(--duke-blue); text-decoration: none; border-radius: 4px; color: white;">Next »</a>
    <?php endif; ?>

</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>