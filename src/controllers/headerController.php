<?php

/**
 * Header Controller
 * Handles header rendering and navigation logic
 */

/**
 * Render page header
 * @param string $page_title Title of the page
 * @return void
 */
function render_header($page_title = 'Cinema Reservation') {
    require __DIR__ . '/../views/header.php';
}

/**
 * Render page footer
 * @return void
 */
function render_footer() {
    require __DIR__ . '/../views/footer.php';
}

/**
 * Get navigation items based on user role
 * @param bool $is_logged_in Whether user is logged in
 * @param bool $is_admin Whether user is admin
 * @return array Navigation items
 */
function get_navigation_items($is_logged_in, $is_admin = false) {
    $nav_items = [];
    
    if ($is_logged_in) {
        $nav_items[] = ['url' => '/home', 'label' => 'Home'];
        $nav_items[] = ['url' => '/reservation', 'label' => 'My Reservations'];
        
        if ($is_admin) {
            $nav_items[] = ['url' => '/admin', 'label' => 'Admin Panel'];
        }
    }
    
    return $nav_items;
}
