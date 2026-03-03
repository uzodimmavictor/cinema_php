<?php

// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Serve static files (images, CSS, JS) when using PHP built-in server
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If the file exists in public directory and it's not a PHP file, serve it
if ($request_uri !== '/' && file_exists(__DIR__ . $request_uri) && !is_dir(__DIR__ . $request_uri)) {
    return false; // Let PHP built-in server handle static files
}

// Initialize database connection
require_once __DIR__ . '/../src/config/database.php';
$pdo = Database::getConnection();

// Include controllers
require_once __DIR__ . '/../src/controllers/loginController.php';
require_once __DIR__ . '/../src/controllers/registerController.php';
require_once __DIR__ . '/../src/controllers/bookingController.php';
require_once __DIR__ . '/../src/controllers/detailsControlleur.php';
require_once __DIR__ . '/../src/controllers/reservationController.php';

// Simple redirect function for PHP Development Server
function redirect($path) {
    header('Location: ' . $path);
    exit();
}

// Get the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Simple routing for testing
if ($request_uri === '/login' && $request_method === 'GET') {
    show_login_page($pdo);
} elseif ($request_uri === '/login' && $request_method === 'POST') {
    process_login($pdo);
} elseif ($request_uri === '/register' && $request_method === 'GET') {
    show_register_page();
} elseif ($request_uri === '/register' && $request_method === 'POST') {
    process_registration($pdo);
} elseif ($request_uri === '/logout') {
    process_logout();
} elseif ($request_uri === '/delete-account' && $request_method === 'POST') {
    process_delete_account($pdo);
} elseif ($request_uri === '/home') {
    require_once __DIR__ . '/../src/controllers/homeController.php';
    show_home_page($pdo);
} elseif ($request_uri === '/details' && $request_method === 'GET') {
    $filmId = $_GET['filmId'] ?? null;
    if ($filmId) {
        require_once __DIR__ . '/../src/config/database.php';
        require_once __DIR__ . '/../src/models/detailsModel.php';
        $detailsController = new detailsController();
        $detailsController->showFilmDetails($filmId);
    } else {
        echo "Error: Film ID is required.";
    }
} elseif ($request_uri === '/booking' && $request_method === 'GET') {
    $sceanceId = $_GET['sceanceId'] ?? null;
    if ($sceanceId) {
        $bookingController = new BookingController();
        $bookingController->showBookingPage($sceanceId);
    } else {
        echo "Error: Session ID is required.";
    }
} elseif ($request_uri === '/booking/select' && $request_method === 'POST') {
    $bookingController = new BookingController();
    $bookingController->selectSeats();
} elseif ($request_uri === '/reservation' && $request_method === 'POST') {
    $reservationController = new ReservationController();
    $reservationController->confirmReservation();
} elseif ($request_uri === '/reservation' && $request_method === 'GET') {
    $reservationController = new ReservationController();
    $reservationController->showUserReservations();
} elseif ($request_uri === '/') {
    header('Location: /login');
    exit();
} else {
    http_response_code(404);
    echo "404 - Page not found";
} 
