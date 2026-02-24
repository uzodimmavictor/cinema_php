<?php

class BookingModel {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = Database::getConnection();
    }

    /**
     * Retrieves session information by its ID
     * 
     * @param string $sceanceId Session ID
     * @return array|null Session information (film, date, room)
     */
    public function getSceanceById($sceanceId) {
        try {
            $sql = "SELECT s.*, f.filmTitle, f.filmPoster, r.roomNumberOfSeats, r.roomCharacteristic 
                    FROM sceance s 
                    JOIN film f ON s.filmId = f.filmId 
                    JOIN room r ON s.roomId = r.roomId 
                    WHERE s.sceanceId = :sceanceId";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sceanceId', $sceanceId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving session: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Retrieves all available seats for a session
     * (seats in the room that are not already reserved)
     * 
     * @param int $roomId Room ID
     * @param string $sceanceId Session ID
     * @return array List of available seats
     */
    public function getAvailableSeats($roomId, $sceanceId) {
        try {
            $sql = "SELECT s.* 
                    FROM seat s 
                    WHERE s.roomId = :roomId 
                    AND NOT EXISTS (
                        SELECT 1 FROM reservation r 
                        WHERE r.roomId = s.roomId 
                        AND r.seatId = s.seatId 
                        AND r.sceanceId = :sceanceId
                    )
                    ORDER BY s.seatRow, s.seatColumn";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':sceanceId', $sceanceId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving available seats: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Checks if a seat is available (not reserved) for a session
     * 
     * @param int $roomId Room ID
     * @param int $seatId Seat ID
     * @param string $sceanceId Session ID
     * @return bool True if the seat is available, False if reserved
     */
    public function checkSeatAvailability($roomId, $seatId, $sceanceId) {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM reservation 
                    WHERE roomId = :roomId 
                    AND seatId = :seatId 
                    AND sceanceId = :sceanceId";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':seatId', $seatId, PDO::PARAM_INT);
            $stmt->bindParam(':sceanceId', $sceanceId);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] == 0; // True if no reservation = seat available
        } catch (PDOException $e) {
            echo "Error checking seat availability: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieves all seats in a room with their status (available/reserved)
     * 
     * @param int $roomId Room ID
     * @param string $sceanceId Session ID
     * @return array List of seats with status
     */
    public function getAllSeatsWithStatus($roomId, $sceanceId) {
        try {
            $sql = "SELECT s.*, 
                    CASE 
                        WHEN r.seatId IS NULL THEN 'available' 
                        ELSE 'reserved' 
                    END as status
                    FROM seat s
                    LEFT JOIN reservation r ON s.roomId = r.roomId 
                        AND s.seatId = r.seatId 
                        AND r.sceanceId = :sceanceId
                    WHERE s.roomId = :roomId
                    ORDER BY s.seatRow, s.seatColumn";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
            $stmt->bindParam(':sceanceId', $sceanceId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving seats: " . $e->getMessage();
            return [];
        }
    }
}
