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
</head>
<body>
    <h1>Register</h1>
    
    <?php if (!empty($error_message)): ?>
        <div>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success_message)): ?>
        <div>
            <p><strong>Success:</strong> <?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div>
            <p><strong>Validation Errors:</strong></p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="/register" method="POST">
        <div>
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($form_data['user_id'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <small>Minimum 6 characters</small>
        </div>
        
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    
    <p>Already have an account? <a href="/login">Login here</a></p>
</body>
</html>
