<?php
// Include the site header
include '../includes/header.php'
?>

<main class="auth-container">
    <div class="auth-box">
        <h2 class="auth-title"> Sign In </h2>
        <p class="auth-subtitle"> Welcome back to the Blue Devil community ! </p>

        <form action="process_login.php" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="email">Duke Email</label>
                <input type="email" name="email" id="email" required placeholder="name@duke.edu">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>

        <p class="auth-footer">New here? <a href="register.php">Create an account</a></p>
    </div>
</main>

<?php include '../includes/footer.php'; ?>