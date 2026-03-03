<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title><?php echo htmlspecialchars($film['filmTitle']); ?> - Cinema</title>
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/home" style="color: var(--primary-color); text-decoration: none;">🎬 CINEMA</a></h1>
            <nav>
                <ul>
                    <li><a href="/home">🏠 Back to Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="details-container">
            <div class="details-poster">
                <?php if (!empty($film['filmPoster'])): ?>
                    <img src="/pictures/<?php echo htmlspecialchars($film['filmPoster']); ?>" alt="<?php echo htmlspecialchars($film['filmTitle']); ?>">
                <?php else: ?>
                    <div style="width: 100%; height: 600px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 5rem;">🎬</div>
                <?php endif; ?>
            </div>
            
            <div class="details-info">
                <h1><?php echo htmlspecialchars($film['filmTitle']); ?></h1>
                
                <div class="details-meta">
                    <div class="details-meta-item">
                        <strong>Genre</strong>
                        <span><?php echo htmlspecialchars($film['filmCategory']); ?></span>
                    </div>
                    <div class="details-meta-item">
                        <strong>Duration</strong>
                        <span><?php echo htmlspecialchars($film['filmTime']); ?> min</span>
                    </div>
                    <div class="details-meta-item">
                        <strong>Director</strong>
                        <span><?php echo htmlspecialchars($film['filmAuthor']); ?></span>
                    </div>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--accent-gold); margin-bottom: 15px;">Synopsis</h3>
                    <p style="color: var(--text-secondary); line-height: 1.8;"><?php echo htmlspecialchars($film['filmDetail']); ?></p>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 60px;">
            <h2 style="color: var(--text-primary); margin-bottom: 30px;">🎫 Available Showtimes</h2>
            <?php if (!empty($sceances)): ?>
                <div class="seances-grid">
                    <?php foreach ($sceances as $sceance): ?>
                        <a href="/booking?sceanceId=<?php echo htmlspecialchars($sceance['sceanceId']); ?>" class="seance-card" style="text-decoration: none;">
                            <div class="seance-date">
                                📅 <?php echo date('M d, Y', strtotime($sceance['sceanceDate'])); ?>
                            </div>
                            <div class="seance-date" style="font-size: 1.5rem; color: var(--primary-color);">
                                🕐 <?php echo date('H:i', strtotime($sceance['sceanceDate'])); ?>
                            </div>
                            <div class="seance-room">
                                🎭 Room <?php echo htmlspecialchars($sceance['roomId']); ?>
                            </div>
                            <div class="btn btn-full" style="margin-top: 15px;">Book Now</div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 60px 0;">No showtimes available for this film.</p>
            <?php endif; ?>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Cinema Reservation System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
