<?php

/**
 * Login Model
 * Handles all database operations related to user authentication
 */

/**
 * Get user by email address
 * @param PDO $pdo Database connection
 * @param string $email User email
 * @return array|false User data or false if not found
 */
function get_user_by_email($pdo, $email) {
    try {
        $stmt = $pdo->prepare("SELECT userId, userPassword, userEmail, isAdmin FROM user_ WHERE userEmail = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user by email: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user by ID
 * @param PDO $pdo Database connection
 * @param string $user_id User ID
 * @return array|false User data or false if not found
 */
function get_user_by_id($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT userId, userEmail, isAdmin FROM user_ WHERE userId = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user by ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Verify user credentials
 * @param PDO $pdo Database connection
 * @param string $email User email
 * @param string $password User password (plain text)
 * @return array|false User data if credentials valid, false otherwise
 */
function verify_user_credentials($pdo, $email, $password) {
    $user = get_user_by_email($pdo, $email);
    
    if (!$user) {
        return false;
    }
    
    // Check if password matches (using password_verify for hashed passwords)
    if (password_verify($password, $user['userPassword'])) {
        // Remove password from returned data
        unset($user['userPassword']);
        return $user;
    }
    
    return false;
}

/**
 * Check if user exists by email
 * @param PDO $pdo Database connection
 * @param string $email User email
 * @return bool True if exists, false otherwise
 */
function user_exists_by_email($pdo, $email) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user_ WHERE userEmail = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking user existence: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete user account
 * @param PDO $pdo Database connection
 * @param string $user_id User ID
 * @return array Result with success status and error message if any
 */
function delete_user_account($pdo, $user_id) {
    try {
        // First delete all reservations for this user
        $stmt = $pdo->prepare("DELETE FROM reservation WHERE userId = ?");
        $stmt->execute([$user_id]);
        
        // Then delete the user
        $stmt = $pdo->prepare("DELETE FROM user_ WHERE userId = ?");
        $result = $stmt->execute([$user_id]);
        
        return [
            'success' => $result,
            'error' => null
        ];
    } catch (PDOException $e) {
        error_log("Error deleting user account: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}
