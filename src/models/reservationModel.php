<?php

class ReservationModel {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = Database::getConnection();
    }

    /**
     * Creates a new reservation in the database
     * @return bool True if reservation created successfully, False otherwise
     */
    public function createReservation($userId, $roomId, $seatId, $sceanceId) {
        try {
            $sql = "INSERT INTO reservation (userId, roomId, seatId, sceanceId) 
                    VALUES (:userId, :roomId, :seatId, :sceanceId)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':seatId', $seatId, PDO::PARAM_INT);
            $stmt->bindParam(':sceanceId', $sceanceId);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error creating reservation: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieves all reservations for a specific user
     * @return array List of user's reservations with session and film details
     */
    public function getUserReservations($userId) {
        try {
            $sql = "SELECT r.*, f.filmTitle, f.filmPoster, s.sceanceDate, rm.roomId, rm.roomCharacteristic
                    FROM reservation r
                    JOIN sceance s ON r.sceanceId = s.sceanceId
                    JOIN film f ON s.filmId = f.filmId
                    JOIN room rm ON r.roomId = rm.roomId
                    WHERE r.userId = :userId
                    ORDER BY s.sceanceDate DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving reservations: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Gets reservation details by session ID
     * @return array|null Reservation details or null
     */
    public function getReservationBySceanceId($sceanceId) {
        try {
            $sql = "SELECT s.*, f.filmTitle, f.filmPoster, rm.roomNumberOfSeats, rm.roomCharacteristic
                    FROM sceance s
                    JOIN film f ON s.filmId = f.filmId
                    JOIN room rm ON s.roomId = rm.roomId
                    WHERE s.sceanceId = :sceanceId";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sceanceId', $sceanceId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving reservation details: " . $e->getMessage();
            return null;
        }
    }
}
