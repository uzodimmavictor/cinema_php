<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Reservations</title>
    <style>
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        .confirmation-section {
            background: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .confirmation-message {
            color: #2e7d32;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .recap {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .recap-item {
            margin: 10px 0;
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .recap-item:last-child {
            border-bottom: none;
        }
        .recap-label {
            font-weight: bold;
            color: #333;
        }
        .recap-value {
            color: #666;
            margin-left: 10px;
        }
        .reservations-section {
            margin-top: 40px;
        }
        .reservation-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            gap: 20px;
        }
        .reservation-image {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .reservation-info {
            flex: 1;
        }
        .reservation-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .reservation-details {
            color: #666;
            font-size: 14px;
            line-height: 1.8;
        }
        .no-reservations {
            text-align: center;
            padding: 40px;
            color: #999;
            font-size: 16px;
        }
        .btn-home {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-home:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($reservationDetails) && !empty($reservationDetails)): ?>
            <!-- Confirmation Section -->
            <div class="confirmation-section">
                <div class="confirmation-message">
                    ✓ Reservation Confirmed!
                </div>
                <p>Your reservation has been successfully registered in the system.</p>
            </div>

            <!-- Recap Section -->
            <h2>Reservation Summary</h2>
            <div class="recap">
                <div class="recap-item">
                    <span class="recap-label">Film:</span>
                    <span class="recap-value"><?php echo htmlspecialchars($reservationDetails['filmTitle']); ?></span>
                </div>
                <div class="recap-item">
                    <span class="recap-label">Date & Time:</span>
                    <span class="recap-value"><?php echo htmlspecialchars($reservationDetails['sceanceDate']); ?></span>
                </div>
                <div class="recap-item">
                    <span class="recap-label">Room:</span>
                    <span class="recap-value">Room <?php echo htmlspecialchars($reservationDetails['roomId']); ?> - <?php echo htmlspecialchars($reservationDetails['roomCharacteristic']); ?></span>
                </div>
                <div class="recap-item">
                    <span class="recap-label">Total Seats Reserved:</span>
                    <span class="recap-value"><?php echo count($_SESSION['booking']['seats'] ?? []); ?></span>
                </div>
            </div>

            <a href="/" class="btn-home">Return to Home</a>

        <?php else: ?>
            <!-- User Reservations History Section -->
            <h1>My Reservations</h1>

            <?php if (!empty($reservations)): ?>
                <div class="reservations-section">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-card">
                            <img src="/pictures/<?php echo htmlspecialchars($reservation['filmPoster']); ?>" 
                                 alt="<?php echo htmlspecialchars($reservation['filmTitle']); ?>" 
                                 class="reservation-image">
                            <div class="reservation-info">
                                <div class="reservation-title"><?php echo htmlspecialchars($reservation['filmTitle']); ?></div>
                                <div class="reservation-details">
                                    <p><strong>Date & Time:</strong> <?php echo htmlspecialchars($reservation['sceanceDate']); ?></p>
                                    <p><strong>Room:</strong> <?php echo htmlspecialchars($reservation['roomId']); ?> - <?php echo htmlspecialchars($reservation['roomCharacteristic']); ?></p>
                                    <p><strong>Seat:</strong> Row <?php echo htmlspecialchars($reservation['seatId']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-reservations">
                    <p>You have no reservations yet.</p>
                    <a href="/" class="btn-home">Browse Films</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
