<?php

class ReservationController {
    private $model;

    public function __construct() {
        require_once __DIR__ . '/../models/reservationModel.php';
        $this->model = new ReservationModel();
    }

    /**
     * Confirms a reservation by saving all selected seats to the database
     * Retrieves booking data from session and creates reservations
     */
    public function confirmReservation() {
        // Session already started in index.php

        // Check if booking data exists in session
        if (!isset($_SESSION['booking'])) {
            echo "Error: No booking session found.";
            return;
        }

        // Get booking data
        $booking = $_SESSION['booking'];
        $sceanceId = $booking['sceanceId'] ?? null;
        $seats = $booking['seats'] ?? [];
        $roomId = $booking['roomId'] ?? null;

        // Get user ID from session (assuming user is logged in)
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId || !$sceanceId || empty($seats)) {
            echo "Error: Invalid booking data.";
            return;
        }

        // Create reservation for each selected seat
        $reservationSuccessful = true;
        foreach ($seats as $seatId) {
            if (!$this->model->createReservation($userId, $roomId, $seatId, $sceanceId)) {
                $reservationSuccessful = false;
                break;
            }
        }

        if ($reservationSuccessful) {
            // Get reservation details for display
            $reservationDetails = $this->model->getReservationBySceanceId($sceanceId);
            
            // Clear booking from session
            unset($_SESSION['booking']);

            // Include the reservation view
            include __DIR__ . '/../views/reservation.php';
        } else {
            echo "Error: Failed to create one or more reservations.";
        }
    }

    /**
     * Displays all reservations for the logged-in user
     * If there's pending booking data, process it first
     */
    public function showUserReservations() {
        // Session already started in index.php

        // Check if user is logged in
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo "Error: User not logged in.";
            return;
        }

        // Check if there's a pending booking to confirm
        if (isset($_SESSION['booking'])) {
            // Process the booking first
            $this->processPendingBooking($userId);
            return;
        }

        // Get user's reservations
        $reservations = $this->model->getUserReservations($userId);

        // Include the reservation view
        include __DIR__ . '/../views/reservation.php';
    }

    /**
     * Process pending booking from session
     */
    private function processPendingBooking($userId) {
        $booking = $_SESSION['booking'];
        $sceanceId = $booking['sceanceId'] ?? null;
        $seats = $booking['seats'] ?? [];
        $roomId = $booking['roomId'] ?? null;

        if (!$sceanceId || empty($seats)) {
            echo "Error: Invalid booking data.";
            unset($_SESSION['booking']);
            return;
        }

        // Create reservation for each selected seat
        $reservationSuccessful = true;
        foreach ($seats as $seatId) {
            if (!$this->model->createReservation($userId, $roomId, $seatId, $sceanceId)) {
                $reservationSuccessful = false;
                break;
            }
        }

        if ($reservationSuccessful) {
            // Get reservation details for display
            $reservationDetails = $this->model->getReservationBySceanceId($sceanceId);
            
            // Clear booking from session
            unset($_SESSION['booking']);

            // Include the reservation view with confirmation
            include __DIR__ . '/../views/reservation.php';
        } else {
            echo "Error: Failed to create one or more reservations.";
            unset($_SESSION['booking']);
        }
    }
}
