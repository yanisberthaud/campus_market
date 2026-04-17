<?php
include '../includes/header.php';

// Sécurité : Si l'utilisateur n'est pas connecté, il ne peut pas vendre
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="auth-container">
    <div class="auth-box" style="max-width: 600px;">
        <h2 class="auth-title">List an Item</h2>
        <p class="auth-subtitle">Post your gear for the Duke community</p>

        <form action="process_list_item.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Item Title</label>
                <input type="text" name="title" placeholder="e.g. Blue Devils Jersey" required>
            </div>

            <div class="name-row">
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" name="price" step="0.01" placeholder="0.00" required>
                </div>
                <div class="form-group">
                    <label>Condition</label>
                    <select name="item_condition" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                        <option value="new">New</option>
                        <option value="used">Used</option>
                        <option value="refurbished">Refurbished</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" style="width: 100%; padding: 10px; border-radius: 4px;" required>
                    <option value="1">Textbooks</option>
                    <option value="2">Electronics</option>
                    <option value="3">Dorm Decor</option>
                    <option value="4">School Supplies</option>
                    <option value="5">Clothing</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" style="width: 100%; border-radius: 4px; border: 1px solid #ddd; padding: 10px;" required></textarea>
            </div>

            <div class="form-group">
                <label>Product Photo</label>
                <input type="file" name="product_image" accept="image/*" required>
            </div>  

            <button type="submit" class="btn_login btn-full" style="background-color: var(--duke-blue); color: white !important; padding: 12px; margin-top: 10px;">Post Listing</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>