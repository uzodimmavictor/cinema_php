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
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>🎬 CINEMA</h1>
            
            <nav>
                <ul>
                    <?php if ($is_logged_in): ?>
                        <li><a href="/home">🏠 Home</a></li>
                        <li><a href="/reservation">🎫 My Reservations</a></li>
                        <?php if ($is_admin): ?>
                            <li><a href="/admin">⚙️ Admin</a></li>
                        <?php endif; ?>
                        <li><a href="/logout" class="btn-secondary">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <li><a href="/register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
