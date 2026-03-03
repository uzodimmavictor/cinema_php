<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>My Reservations - Cinema</title>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/home" style="color: var(--primary-color); text-decoration: none;">🎬 CINEMA</a></h1>
            <nav>
                <ul>
                    <li><a href="/home">🏠 Home</a></li>
                    <li><a href="/reservation">🎫 My Reservations</a></li>
                    <li><a href="/logout" class="btn-secondary">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if (isset($reservationDetails) && !empty($reservationDetails)): ?>
            <!-- Confirmation Section -->
            <div class="alert alert-success" style="margin-top: 40px; padding: 30px;">
                <h2 style="margin: 0 0 15px 0; color: #2ecc71;">✓ Reservation Confirmed!</h2>
                <p style="font-size: 1.1rem;">Your reservation has been successfully registered in the system.</p>
            </div>

            <!-- Recap Section -->
            <div style="background: var(--card-bg); padding: 30px; border-radius: var(--border-radius); margin: 30px 0;">
                <h3 style="color: var(--accent-gold); margin-bottom: 25px;">📋 Reservation Summary</h3>
                <div style="display: grid; gap: 20px;">
                    <div class="info-item">
                        <strong>🎬 Film</strong>
                        <span style="display: block; margin-top: 8px; font-size: 1.2rem; color: var(--text-primary);">
                            <?php echo htmlspecialchars($reservationDetails['filmTitle']); ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>📅 Date & Time</strong>
                        <span style="display: block; margin-top: 8px; font-size: 1.2rem; color: var(--text-primary);">
                            <?php echo date('l, F j, Y - H:i', strtotime($reservationDetails['sceanceDate'])); ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>🎭 Room</strong>
                        <span style="display: block; margin-top: 8px; font-size: 1.2rem; color: var(--text-primary);">
                            Room <?php echo htmlspecialchars($reservationDetails['roomId']); ?> - <?php echo htmlspecialchars($reservationDetails['roomCharacteristic']); ?>
                        </span>
                    </div>
                    <div class="info-item">
                        <strong>💺 Total Seats Reserved</strong>
                        <span style="display: block; margin-top: 8px; font-size: 1.2rem; color: var(--primary-color);">
                            <?php echo count($_SESSION['booking']['seats'] ?? [1]); ?> seat(s)
                        </span>
                    </div>
                </div>
                
                <div style="margin-top: 30px; text-align: center;">
                    <a href="/home" class="btn" style="margin-right: 15px;">🏠 Return to Home</a>
                    <a href="/reservation" class="btn btn-secondary">📋 View All Reservations</a>
                </div>
            </div>

        <?php else: ?>
            <!-- User Reservations History Section -->
            <h2 style="margin: 40px 0 30px;">🎫 My Reservations</h2>

            <?php if (!empty($reservations)): ?>
                <div style="display: grid; gap: 20px; margin-bottom: 40px;">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="movie-card" style="display: grid; grid-template-columns: 200px 1fr; height: auto;">
                            <?php if (!empty($reservation['filmPoster'])): ?>
                                <img src="/pictures/<?php echo htmlspecialchars($reservation['filmPoster']); ?>" 
                                     alt="<?php echo htmlspecialchars($reservation['filmTitle']); ?>" 
                                     style="width: 200px; height: 300px; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 200px; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 3rem;">🎬</div>
                            <?php endif; ?>
                            <div class="movie-info" style="padding: 30px;">
                                <h3 class="movie-title" style="margin-bottom: 20px;"><?php echo htmlspecialchars($reservation['filmTitle']); ?></h3>
                                <div style="display: grid; gap: 15px;">
                                    <div>
                                        <strong style="color: var(--accent-gold);">📅 Date & Time:</strong>
                                        <span style="display: block; margin-top: 5px; font-size: 1.1rem;">
                                            <?php echo date('l, F j, Y - H:i', strtotime($reservation['sceanceDate'])); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <strong style="color: var(--accent-gold);">🎭 Room:</strong>
                                        <span style="display: block; margin-top: 5px;">
                                            Room <?php echo htmlspecialchars($reservation['roomId']); ?> - <?php echo htmlspecialchars($reservation['roomCharacteristic']); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <strong style="color: var(--accent-gold);">💺 Seat:</strong>
                                        <span style="display: block; margin-top: 5px;">
                                            Seat #<?php echo htmlspecialchars($reservation['seatId']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 80px 20px; background: var(--card-bg); border-radius: var(--border-radius);">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🎬</div>
                    <h3 style="color: var(--text-secondary); margin-bottom: 20px;">You have no reservations yet.</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 30px;">Start by browsing our available films!</p>
                    <a href="/home" class="btn">🎥 Browse Films</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Cinema Reservation System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
