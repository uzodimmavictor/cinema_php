<?php

class BookingController {
    private $model;

    public function __construct() {
        require_once __DIR__ . '/../models/bookingModel.php';
        $this->model = new BookingModel();
    }

    /**
     * Displays the booking page with available seats for a session
     * 
     * @param string $sceanceId Session ID
     */
    public function showBookingPage($sceanceId) {
        // Retrieve session information
        $sceance = $this->model->getSceanceById($sceanceId);
        
        if (!$sceance) {
            echo "Session not found.";
            return;
        }

        // Retrieve all seats with their status (available/reserved)
        $seats = $this->model->getAllSeatsWithStatus($sceance['roomId'], $sceanceId);
        
        if (empty($seats)) {
            echo "No seats found for this room.";
            return;
        }

        // Include the booking view
        include __DIR__ . '/../views/booking.php';
    }

    /**
     * Handles seat selection from the booking form
     * Processes selected seats and redirects to reservation confirmation
     */
    public function selectSeats() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit();
        }

        // Retrieve form data
        $sceanceId = $_POST['sceanceId'] ?? null;
        $selectedSeats = $_POST['seats'] ?? [];

        // Validate input
        if (!$sceanceId || empty($selectedSeats)) {
            echo "Error: Please select at least one seat.";
            return;
        }

        // Retrieve session information
        $sceance = $this->model->getSceanceById($sceanceId);
        if (!$sceance) {
            echo "Error: Session not found.";
            return;
        }

        $roomId = $sceance['roomId'];
        $availableSeats = [];
        $unavailableSeats = [];

        // Check availability for each selected seat
        foreach ($selectedSeats as $seatId) {
            if ($this->model->checkSeatAvailability($roomId, $seatId, $sceanceId)) {
                $availableSeats[] = $seatId;
            } else {
                $unavailableSeats[] = $seatId;
            }
        }

        // If some seats are unavailable, show error
        if (!empty($unavailableSeats)) {
            echo "Error: Some seats are no longer available: " . implode(', ', $unavailableSeats);
            return;
        }

        // All seats are available, redirect to reservation confirmation
        // Store selected seats in session for reservation page
        // Session already started in index.php
        $_SESSION['booking'] = [
            'sceanceId' => $sceanceId,
            'seats' => $availableSeats,
            'roomId' => $roomId
        ];

        redirect('/reservation');
    }
}
