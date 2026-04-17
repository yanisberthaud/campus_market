<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Blue Devil Exchange</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="index.php">
                <span class="logo-bebas">Blue Devil</span>
                <span class="logo-bold">Exchange</span>
            </a>
        </div>

        <?php 
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
        ?>

        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="burger-label">
            <span></span><span></span><span></span>
        </label>

        <nav class="nav-container">
            <form action="browse.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search textbooks, gear..." class="search-input">
                <button type="submit" class="search-btn">Go</button>
            </form>
            
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="browse.php">Browse</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="list_item.php">List Item</a></li>
                    <li class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</li>
                    <li><a href="my_listings.php" class="my-ads-btn">My Ads</a></li>
                    <li><a href="logout.php" class="logout-link">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="login-btn">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>