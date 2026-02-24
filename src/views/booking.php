<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Booking - <?php echo htmlspecialchars($sceance['filmTitle']); ?></title>
    <style>
        .booking-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .session-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .session-info h2 {
            margin: 0 0 10px 0;
        }
        .screen {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin: 30px auto;
            width: 80%;
            border-radius: 10px 10px 0 0;
        }
        .seats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
            gap: 10px;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .seat {
            width: 40px;
            height: 40px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.3s;
        }
        .seat.available {
            background: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }
        .seat.available:hover {
            background: #45a049;
            transform: scale(1.1);
        }
        .seat.reserved {
            background: #f44336;
            border-color: #f44336;
            color: white;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .seat.selected {
            background: #2196F3;
            border-color: #2196F3;
            color: white;
            transform: scale(1.1);
        }
        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .legend-box {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            border: 2px solid #ccc;
        }
        .legend-box.available {
            background: #4CAF50;
            border-color: #4CAF50;
        }
        .legend-box.reserved {
            background: #f44336;
            border-color: #f44336;
        }
        .legend-box.selected {
            background: #2196F3;
            border-color: #2196F3;
        }
        .booking-actions {
            text-align: center;
            margin: 30px 0;
        }
        .btn-confirm {
            background: #4CAF50;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-confirm:hover {
            background: #45a049;
        }
        .btn-confirm:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .selected-count {
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <!-- Session Information -->
        <div class="session-info">
            <h2><?php echo htmlspecialchars($sceance['filmTitle']); ?></h2>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($sceance['sceanceDate']); ?></p>
            <p><strong>Room:</strong> <?php echo htmlspecialchars($sceance['roomId']); ?> - <?php echo htmlspecialchars($sceance['roomCharacteristic']); ?></p>
            <p><strong>Total Seats:</strong> <?php echo htmlspecialchars($sceance['roomNumberOfSeats']); ?></p>
        </div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-box available"></div>
                <span>Available</span>
            </div>
            <div class="legend-item">
                <div class="legend-box reserved"></div>
                <span>Reserved</span>
            </div>
            <div class="legend-item">
                <div class="legend-box selected"></div>
                <span>Selected</span>
            </div>
        </div>

        <!-- Screen -->
        <div class="screen">SCREEN</div>

        <!-- Booking Form -->
        <form method="POST" action="/booking/select" id="bookingForm">
            <input type="hidden" name="sceanceId" value="<?php echo htmlspecialchars($sceance['sceanceId']); ?>">
            
            <!-- Seats Grid -->
            <div class="seats-grid">
                <?php foreach ($seats as $seat): ?>
                    <?php if ($seat['status'] === 'available'): ?>
                        <div class="seat available" 
                             data-seat-id="<?php echo htmlspecialchars($seat['seatId']); ?>"
                             onclick="toggleSeat(this, <?php echo htmlspecialchars($seat['seatId']); ?>)">
                            <?php echo htmlspecialchars($seat['seatRow']) . htmlspecialchars($seat['seatColumn']); ?>
                        </div>
                    <?php else: ?>
                        <div class="seat reserved">
                            <?php echo htmlspecialchars($seat['seatRow']) . htmlspecialchars($seat['seatColumn']); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Selected seats count -->
            <div class="selected-count">
                Selected seats: <span id="selectedCount">0</span>
            </div>

            <!-- Booking Actions -->
            <div class="booking-actions">
                <button type="submit" class="btn-confirm" id="confirmBtn" disabled>Confirm Booking</button>
            </div>
        </form>
    </div>

    <script>
        let selectedSeats = [];

        function toggleSeat(element, seatId) {
            if (element.classList.contains('reserved')) {
                return; // Cannot select reserved seats
            }

            const index = selectedSeats.indexOf(seatId);
            
            if (index > -1) {
                // Deselect
                selectedSeats.splice(index, 1);
                element.classList.remove('selected');
                element.classList.add('available');
            } else {
                // Select
                selectedSeats.push(seatId);
                element.classList.remove('available');
                element.classList.add('selected');
            }

            updateSelectedCount();
            updateHiddenInputs();
        }

        function updateSelectedCount() {
            document.getElementById('selectedCount').textContent = selectedSeats.length;
            document.getElementById('confirmBtn').disabled = selectedSeats.length === 0;
        }

        function updateHiddenInputs() {
            // Remove existing hidden inputs
            const existingInputs = document.querySelectorAll('input[name="seats[]"]');
            existingInputs.forEach(input => input.remove());

            // Add new hidden inputs for selected seats
            const form = document.getElementById('bookingForm');
            selectedSeats.forEach(seatId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'seats[]';
                input.value = seatId;
                form.appendChild(input);
            });
        }
    </script>
</body>
</html>
