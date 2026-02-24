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
        session_start();

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
        $userId = $_SESSION['userId'] ?? null;

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
     */
    public function showUserReservations() {
        session_start();

        // Check if user is logged in
        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            echo "Error: User not logged in.";
            return;
        }

        // Get user's reservations
        $reservations = $this->model->getUserReservations($userId);

        // Include the reservation view
        include __DIR__ . '/../views/reservation.php';
    }
}
