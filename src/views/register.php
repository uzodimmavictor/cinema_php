<?php
/**
 * Register View
 * Displays registration form for new users
 */

// Start session to access error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get error/success messages from session
$error_message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['success_message'] ?? '';
$errors = $_SESSION['errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? ['user_id' => '', 'email' => ''];

// Clear messages after displaying
unset($_SESSION['error_message'], $_SESSION['success_message'], $_SESSION['errors'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cinema Reservation</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1 style="text-align: center; color: var(--primary-color); margin-bottom: 30px;">🎬 Create Account</h1>
        
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
        
        <form action="/register" method="POST">
            <div class="form-group">
                <label for="user_id">👤 User ID:</label>
                <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($form_data['user_id'], ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Choose a unique username">
            </div>
            
            <div class="form-group">
                <label for="email">📧 Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8'); ?>" required placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">🔒 Password:</label>
                <input type="password" id="password" name="password" required placeholder="Minimum 6 characters">
                <small style="color: var(--text-secondary); display: block; margin-top: 5px;">Minimum 6 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">🔒 Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter your password">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-full">Create Account</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 30px; color: var(--text-secondary);">
            Already have an account? <a href="/login" style="color: var(--primary-color); text-decoration: none;">Login here</a>
        </p>
    </div>
</body>
</html>
