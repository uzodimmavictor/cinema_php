<?php

/**
 * Register Controller
 * Handles user registration and validation
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/../models/registerModel.php';
require_once __DIR__ . '/../models/loginModel.php';

/**
 * Validate password strength
 * @param string $password Password to validate
 * @return bool True if valid, false otherwise
 */
function validate_password($password) {
    return strlen($password) >= 6;
}

/**
 * Validate registration input data
 * @param string $user_id User ID
 * @param string $email User email
 * @param string $password User password
 * @param string $confirm_password Password confirmation
 * @param PDO $pdo Database connection
 * @return array Array with 'valid' bool and 'errors' array
 */
function validate_registration_input($user_id, $email, $password, $confirm_password, $pdo) {
    $errors = [];
    
    // Check if user ID is empty
    if (empty($user_id)) {
        $errors[] = "User ID is required";
    }
    
    // Check if email is empty
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    } elseif (user_exists_by_email($pdo, $email)) {
        $errors[] = "Email already exists";
    }
    
    // Check if password is empty
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (!validate_password($password)) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if user ID already exists
    if (user_id_exists($pdo, $user_id)) {
        $errors[] = "User ID already exists";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Handle user registration request
 * @param PDO $pdo Database connection
 * @return array Response with success status and message
 */
function handle_registration($pdo) {
    // Get and sanitize input
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validate input
    $validation = validate_registration_input($user_id, $email, $password, $confirm_password, $pdo);
    if (!$validation['valid']) {
        return [
            'success' => false,
            'errors' => $validation['errors']
        ];
    }
    
    // Create user
    $result = create_user($pdo, $user_id, $email, $password);
    
    if (!$result['success']) {
        return [
            'success' => false,
            'message' => 'Registration failed: ' . ($result['error'] ?? 'Unknown error')
        ];
    }
    
    return [
        'success' => true,
        'message' => 'Registration successful! You can now login.',
        'redirect' => '/login'
    ];
}

/**
 * Show registration page
 * @return void
 */
function show_register_page() {
    // Redirect to home if already logged in
    require_once __DIR__ . '/loginController.php';
    if (is_user_logged_in()) {
        redirect('/home');
    }
    
    require_once __DIR__ . '/../views/register.php';
}

/**
 * Process registration form submission
 * @param PDO $pdo Database connection
 * @return void
 */
function process_registration($pdo) {
    $response = handle_registration($pdo);
    
    if ($response['success']) {
        $_SESSION['success_message'] = $response['message'];
        redirect('/login');
    } else {
        // Store errors in session
        if (isset($response['errors'])) {
            $_SESSION['errors'] = $response['errors'];
        }
        if (isset($response['message'])) {
            $_SESSION['error_message'] = $response['message'];
        }
        
        // Preserve form data
        $_SESSION['form_data'] = [
            'user_id' => $_POST['user_id'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        redirect('/register');
    }
}
