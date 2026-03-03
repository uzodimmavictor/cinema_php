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

<div class="welcome-message">
    <h2>Welcome, <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?>! 👋</h2>
</div>

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

<section>
    <h3>🎥 Available Movies</h3>
    
    <?php if (!empty($movies) && count($movies) > 0): ?>
        <div class="movies-grid">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card">
                    <?php if (!empty($movie['filmPoster'])): ?>
                        <img src="/pictures/<?php echo htmlspecialchars($movie['filmPoster'], ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($movie['filmTitle'], ENT_QUOTES, 'UTF-8'); ?>" 
                             class="movie-poster">
                    <?php else: ?>
                        <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 3rem;">🎬</div>
                    <?php endif; ?>
                    <div class="movie-info">
                        <h4 class="movie-title"><?php echo htmlspecialchars($movie['filmTitle'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <div class="movie-meta">
                            <span><strong>Director:</strong> <?php echo htmlspecialchars($movie['filmAuthor'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><strong>Genre:</strong> <?php echo htmlspecialchars($movie['filmCategory'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if (!empty($movie['filmTime'])): ?>
                                <span><strong>⏱️</strong> <?php echo htmlspecialchars($movie['filmTime'], ENT_QUOTES, 'UTF-8'); ?> min</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (isset($movie['availableShowtimes']) && $movie['availableShowtimes'] > 0): ?>
                            <div style="background: rgba(229, 9, 20, 0.2); padding: 10px; border-radius: 5px; margin: 15px 0; border-left: 3px solid var(--primary-color);">
                                <strong style="color: var(--accent-gold);">🎫 <?php echo $movie['availableShowtimes']; ?> showtimes available</strong>
                                <?php if (!empty($movie['nextShowtime'])): ?>
                                    <br><span style="font-size: 0.9rem; color: var(--text-secondary);">Next: <?php echo date('M d, H:i', strtotime($movie['nextShowtime'])); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div style="background: rgba(100, 100, 100, 0.2); padding: 10px; border-radius: 5px; margin: 15px 0;">
                                <span style="color: var(--text-secondary);">⏳ No showtimes scheduled</span>
                            </div>
                        <?php endif; ?>
                        
                        <p class="movie-description"><?php echo htmlspecialchars($movie['filmDetail'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <a href="/details?filmId=<?php echo urlencode($movie['filmId']); ?>" class="btn btn-full">📋 View Details & Book</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #b3b3b3; margin: 60px 0;">No movies available at the moment.</p>
    <?php endif; ?>
</section>

<section class="account-section">
    <h3>⚙️ Account Settings</h3>
    <div class="account-info">
        <div class="info-item">
            <strong>User ID</strong>
            <span><?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <div class="info-item">
            <strong>Email</strong>
            <span><?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <div class="info-item">
            <strong>Account Type</strong>
            <span><?php echo $is_admin ? '👑 Admin' : '👤 Regular User'; ?></span>
        </div>
    </div>
    
    <div class="danger-zone">
        <h4>🗑️ Delete Account</h4>
        <p>Warning: This action cannot be undone. All your reservations will be deleted.</p>
        <form action="/delete-account" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            <button type="submit" class="btn" style="margin-top: 15px;">Delete My Account</button>
        </form>
    </div>
</section>

<?php
// Include footer
require_once __DIR__ . '/footer.php';
?>
