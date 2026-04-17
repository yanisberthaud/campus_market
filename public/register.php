<?php 
// Include the header.
include '../includes/header.php';
?>

<main class="auth-container">
    <div class="auth-box">
        <h2 class="auth-title"> Join the Community </h2>
        <p class="auth-subtitle"> Create your Blue Devil Exchange account </p>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
            <p style="color: red; text-align: center;">This email is already registered!</p>
        <?php endif; ?>

        <form action="process_register.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" required placeholder="e.g. Zion">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" required placeholder="e.g. Williamson">
            </div>

            <div class="form-group">
                <label for="email"> Duke Email </label>
                <input type="email" name="email" id="email" required placeholder="name@duke.edu">
            </div>

            <div class="form-group">
                <label for="password"> Password </label>
                <input type="password" name="password" id="password" required placeholder="Min. 8 characters">
            </div>

            <button type="submit" class="btn btn-primary btn-full"> Create Account </button>
        </form>

        <p class="auth-footer"> Already have a account ? <a href="login.php"> Sign In </a></p>
    </div>
</main>

<?php
// Include the footer
include '../includes/footer.php'
?>