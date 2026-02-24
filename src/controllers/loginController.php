<?php

/**
 * Login Controller
 * Handles user authentication and session management
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/../models/loginModel.php';

/**
 * Initialize user session after successful login
 * @param array $user_data User information from database
 * @param bool $remember_me Whether to set remember me cookie
 * @return void
 */
function init_user_session($user_data, $remember_me = false) {
    $_SESSION['user_id'] = $user_data['userId'];
    $_SESSION['user_email'] = $user_data['userEmail'];
    $_SESSION['is_admin'] = $user_data['isAdmin'];
    $_SESSION['is_logged_in'] = true;
    
    // Set remember me cookie if requested
    if ($remember_me) {
        set_remember_me_cookie($user_data['userId']);
    }
}

/**
 * Set remember me cookie
 * @param string $user_id User ID
 * @return void
 */
function set_remember_me_cookie($user_id) {
    $token = bin2hex(random_bytes(32));
    $cookie_value = $user_id . ':' . $token;
    
    // Cookie expires in 30 days
    $expire = time() + (30 * 24 * 60 * 60);
    setcookie('remember_me', $cookie_value, $expire, '/', '', false, true);
}

/**
 * Check and process remember me cookie
 * @param PDO $pdo Database connection
 * @return bool True if cookie is valid and user logged in, false otherwise
 */
function check_remember_me_cookie($pdo) {
    if (!isset($_COOKIE['remember_me'])) {
        return false;
    }
    
    $cookie_parts = explode(':', $_COOKIE['remember_me']);
    if (count($cookie_parts) !== 2) {
        return false;
    }
    
    $user_id = $cookie_parts[0];
    
    // Get user from database
    $user = get_user_by_id($pdo, $user_id);
    
    if (!$user) {
        // Invalid user, clear cookie
        clear_remember_me_cookie();
        return false;
    }
    
    // Initialize session with user data
    init_user_session($user, false);
    
    return true;
}

/**
 * Clear remember me cookie
 * @return void
 */
function clear_remember_me_cookie() {
    if (isset($_COOKIE['remember_me'])) {
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
    }
}

/**
 * Check if user is currently logged in
 * @return bool True if logged in, false otherwise
 */
function is_user_logged_in() {
    return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
}

/**
 * Get current logged in user ID
 * @return string|null User ID or null if not logged in
 */
function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if current user is admin
 * @return bool True if admin, false otherwise
 */
function is_current_user_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

/**
 * Destroy user session (logout)
 * @return void
 */
function destroy_user_session() {
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Clear remember me cookie
    clear_remember_me_cookie();
    
    session_destroy();
}

/**
 * Redirect to a specific page
 * @param string $path Path to redirect to
 * @return void
 */
function redirect_to($path) {
    header("Location: $path");
    exit();
}

/**
 * Sanitize user input
 * @param string $input Raw input string
 * @return string Sanitized string
 */
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Validate email format
 * @param string $email Email address to validate
 * @return bool True if valid, false otherwise
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate login input data
 * @param string $email User email
 * @param string $password User password
 * @return array Array with 'valid' bool and 'errors' array
 */
function validate_login_input($email, $password) {
    $errors = [];
    
    // Check if email is empty
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!validate_email($email)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if password is empty
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Get POST input safely
 * @param string $key POST key
 * @return string Sanitized POST value or empty string
 */
function get_post_input($key) {
    return isset($_POST[$key]) ? sanitize_input($_POST[$key]) : '';
}

/**
 * Handle user login request
 * @param PDO $pdo Database connection
 * @return array Response with success status and message
 */
function handle_login($pdo) {
    // Check if already logged in
    if (is_user_logged_in()) {
        return [
            'success' => false,
            'message' => 'Already logged in',
            'redirect' => '/home'
        ];
    }
    
    // Get and sanitize input
    $email = get_post_input('email');
    $password = get_post_input('password');
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] == '1';
    
    // Validate input
    $validation = validate_login_input($email, $password);
    if (!$validation['valid']) {
        return [
            'success' => false,
            'errors' => $validation['errors']
        ];
    }
    
    // Verify credentials
    $user = verify_user_credentials($pdo, $email, $password);
    
    if (!$user) {
        return [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }
    
    // Initialize session with remember me option
    init_user_session($user, $remember_me);
    
    return [
        'success' => true,
        'message' => 'Login successful',
        'redirect' => '/home',
        'user' => $user
    ];
}

/**
 * Handle logout request
 * @return array Response with success status
 */
function handle_logout() {
    if (!is_user_logged_in()) {
        return [
            'success' => false,
            'message' => 'Not logged in'
        ];
    }
    
    destroy_user_session();
    
    return [
        'success' => true,
        'message' => 'Logout successful',
        'redirect' => '/login'
    ];
}

/**
 * Show login page
 * @param PDO $pdo Database connection
 * @return void
 */
function show_login_page($pdo = null) {
    // Check remember me cookie first if pdo is available
    if ($pdo && !is_user_logged_in() && check_remember_me_cookie($pdo)) {
        redirect_to('/home');
    }
    
    // Redirect to home if already logged in
    if (is_user_logged_in()) {
        redirect_to('/home');
    }
    
    require_once __DIR__ . '/../views/login.php';
}

/**
 * Process login form submission
 * @param PDO $pdo Database connection
 * @return void
 */
function process_login($pdo) {
    $response = handle_login($pdo);
    
    if ($response['success']) {
        $_SESSION['success_message'] = $response['message'];
        redirect_to($response['redirect']);
    } else {
        // Store errors in session
        if (isset($response['errors'])) {
            $_SESSION['errors'] = $response['errors'];
        }
        if (isset($response['message'])) {
            $_SESSION['error_message'] = $response['message'];
        }
        redirect_to('/login');
    }
}

/**
 * Process logout request
 * @return void
 */
function process_logout() {
    $response = handle_logout();
    
    if ($response['success']) {
        $_SESSION['success_message'] = $response['message'];
    }
    
    redirect_to($response['redirect']);
}

/**
 * Handle delete account request
 * @param PDO $pdo Database connection
 * @return array Response with success status and message
 */
function handle_delete_account($pdo) {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return [
            'success' => false,
            'message' => 'You must be logged in to delete your account',
            'redirect' => '/login'
        ];
    }
    
    $user_id = get_current_user_id();
    
    // Delete user account
    $result = delete_user_account($pdo, $user_id);
    
    if (!$result['success']) {
        return [
            'success' => false,
            'message' => 'Failed to delete account: ' . ($result['error'] ?? 'Unknown error')
        ];
    }
    
    // Destroy session after successful deletion
    destroy_user_session();
    
    return [
        'success' => true,
        'message' => 'Your account has been successfully deleted',
        'redirect' => '/login'
    ];
}

/**
 * Process delete account request
 * @param PDO $pdo Database connection
 * @return void
 */
function process_delete_account($pdo) {
    $response = handle_delete_account($pdo);
    
    if ($response['success']) {
        $_SESSION['success_message'] = $response['message'];
    } else {
        $_SESSION['error_message'] = $response['message'];
    }
    
    redirect_to($response['redirect']);
}
