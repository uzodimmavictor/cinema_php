<?php
/**
 * Home View
 * Displays home page for logged in users with available movies
 */

// Start session to access user information
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set page title
$page_title = 'Home - Cinema Reservation';

// Include header
require_once __DIR__ . '/header.php';

// Get user information
$user_id = $_SESSION['user_id'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';
$is_admin = $_SESSION['is_admin'] ?? false;

// Get error/success messages from session
$error_message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['success_message'] ?? '';

// Clear messages after displaying
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<h2>Welcome, <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?>!</h2>

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

<section>
    <h3>Available Movies</h3>
    
    <?php if (!empty($movies) && count($movies) > 0): ?>
        <ul>
            <?php foreach ($movies as $movie): ?>
                <li>
                    <?php if (!empty($movie['filmPoster'])): ?>
                        <img src="/pictures/<?php echo htmlspecialchars($movie['filmPoster'], ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($movie['filmTitle'], ENT_QUOTES, 'UTF-8'); ?>" 
                             style="max-width: 200px; height: auto;">
                    <?php endif; ?>
                    <h4><?php echo htmlspecialchars($movie['filmTitle'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($movie['filmAuthor'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($movie['filmCategory'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><?php echo htmlspecialchars($movie['filmDetail'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <a href="/details?filmId=<?php echo urlencode($movie['filmId']); ?>">View Details & Book</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No movies available at the moment.</p>
    <?php endif; ?>
</section>

<hr>

<section>
    <h3>Account Settings</h3>
    <p><strong>User ID:</strong> <?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Account Type:</strong> <?php echo $is_admin ? 'Admin' : 'Regular User'; ?></p>
    
    <div>
        <h4>Delete Account</h4>
        <p>Warning: This action cannot be undone. All your reservations will be deleted.</p>
        <form action="/delete-account" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <button type="submit">Delete My Account</button>
        </form>
    </div>
</section>

<?php
// Include footer
require_once __DIR__ . '/footer.php';
?>
