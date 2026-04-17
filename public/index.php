<?php
session_start();
require_once '../Config/Database.php';

$dbInstance = Database::getInstance();
$pdo = $dbInstance->getConnection();

// On récupère les dernières annonces
$sql = "SELECT * FROM listings WHERE status = 'active' ORDER BY created_at DESC LIMIT 20";
$stmt = $pdo->query($sql);
$latest_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<main class="container">

    <?php if (isset($_GET['status'])): ?>
        <div class="welcome-banner">
            <?php if ($_GET['status'] === 'new'): ?>
                Welcome to the community, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! Your account is ready.
            <?php elseif ($_GET['status'] === 'back'): ?>
                👋 Welcome back, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! Happy to see you again.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <section class="recent-listings">
        <div class="browse-header">
            <h2 style="color: var(--duke-blue);">Recent Arrivals</h2>
            <a href="browse.php" class="view-all-link">View All →</a>
        </div>

        <div class="product-grid">
            <?php foreach ($latest_items as $item): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="uploads/<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php else: ?>
                            <span class="no-image">No image</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                        <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                        <a href="product_details.php?id=<?php echo $item['id']; ?>" class="view-details">View Item</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="hero-section main-hero">
        <h1 class="hero-title">The Blue Devil Exchange</h1>
        <p class="hero-subtitle"> 
            The official student-to-student marketplace for Duke University. 
            Buy and sell your textbooks, dorm decor, and game-day gear safely within the community. 
        </p>
        <div class="hero-actions">
            <a href="browse.php" class="btn_login hero-btn">Start Shopping</a>
            <a href="list_item.php" class="sell-link">Sell an Item</a>
        </div>
    </section>


</main>

<?php include '../includes/footer.php'; ?>