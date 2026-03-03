<?php
/**
 * Login View
 * Displays login form for user authentication
 */

// Start session to access error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get error/success messages from session
$error_message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['success_message'] ?? '';
$errors = $_SESSION['errors'] ?? [];

// Clear messages after displaying
unset($_SESSION['error_message'], $_SESSION['success_message'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cinema Reservation</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1 style="text-align: center; color: var(--primary-color); margin-bottom: 30px;">🎬 Cinema Login</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <p><strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <p><strong>✓ Success:</strong> <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <p><strong>Validation Errors:</strong></p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" id="email" name="email" required placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="remember_me" name="remember_me" value="1" style="width: auto;">
                <label for="remember_me" style="margin: 0;">Remember me</label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-full">Login</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 30px; color: var(--text-secondary);">
            Don't have an account? <a href="/register" style="color: var(--primary-color); text-decoration: none;">Register here</a>
        </p>
    </div>
</body>
</html>
