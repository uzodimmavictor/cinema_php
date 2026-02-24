<?php
/**
 * Header View
 * Reusable header for all pages with navigation and user info
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user information
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
$user_email = $_SESSION['user_email'] ?? '';
$is_admin = $_SESSION['is_admin'] ?? false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Cinema Reservation'; ?></title>
</head>
<body>
    <header>
        <h1>Cinema Reservation System</h1>
        
        <nav>
            <ul>
                <?php if ($is_logged_in): ?>
                    <li><a href="/home">Home</a></li>
                    <li><a href="/reservation">My Reservations</a></li>
                    <?php if ($is_admin): ?>
                        <li><a href="/admin">Admin Panel</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div>
            <?php if ($is_logged_in): ?>
                <p>Logged in as: <strong><?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a> | <a href="/register">Register</a>
            <?php endif; ?>
        </div>
    </header>
    
    <hr>
    
    <main>
