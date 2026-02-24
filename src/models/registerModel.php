<?php

/**
 * Register Model
 * Handles all database operations related to user registration
 */

/**
 * Create new user in database
 * @param PDO $pdo Database connection
 * @param string $user_id User ID
 * @param string $email User email
 * @param string $password User password (will be hashed)
 * @param bool $is_admin Admin status (default false)
 * @return array Result with success status and error message if any
 */
function create_user($pdo, $user_id, $email, $password, $is_admin = false) {
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $admin_value = $is_admin ? 1 : 0;
        
        $stmt = $pdo->prepare("INSERT INTO user_ (userId, userEmail, userPassword, isAdmin) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$user_id, $email, $hashed_password, $admin_value]);
        
        return [
            'success' => $result,
            'error' => null
        ];
    } catch (PDOException $e) {
        error_log("Error creating user: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Check if user ID already exists
 * @param PDO $pdo Database connection
 * @param string $user_id User ID to check
 * @return bool True if exists, false otherwise
 */
function user_id_exists($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_ WHERE userId = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking user ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Generate unique user ID
 * @param PDO $pdo Database connection
 * @return string Generated user ID
 */
function generate_user_id($pdo) {
    do {
        $user_id = 'USER_' . uniqid();
    } while (user_id_exists($pdo, $user_id));
    
    return $user_id;
}
