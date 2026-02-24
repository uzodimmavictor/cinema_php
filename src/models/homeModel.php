<?php

/**
 * Home Model
 * Handles database operations for home page
 */

/**
 * Get all available movies
 * @param PDO $pdo Database connection
 * @return array List of movies or empty array
 */
function get_all_movies($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT filmId, filmTitle, filmAuthor, filmDetail, filmCategory FROM film ORDER BY filmTitle ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching movies: " . $e->getMessage());
        return [];
    }
}

/**
 * Get movie by ID
 * @param PDO $pdo Database connection
 * @param string $film_id Film ID
 * @return array|false Movie data or false if not found
 */
function get_movie_by_id($pdo, $film_id) {
    try {
        $stmt = $pdo->prepare("SELECT filmId, filmTitle, filmAuthor, filmDetail, filmCategory FROM film WHERE filmId = ?");
        $stmt->execute([$film_id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching movie by ID: " . $e->getMessage());
        return false;
    }
}

/**
 * Get movies by category
 * @param PDO $pdo Database connection
 * @param string $category Film category
 * @return array List of movies or empty array
 */
function get_movies_by_category($pdo, $category) {
    try {
        $stmt = $pdo->prepare("SELECT filmId, filmTitle, filmAuthor, filmDetail, filmCategory FROM film WHERE filmCategory = ? ORDER BY filmTitle ASC");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching movies by category: " . $e->getMessage());
        return [];
    }
}

/**
 * Search movies by title
 * @param PDO $pdo Database connection
 * @param string $search_term Search term
 * @return array List of movies or empty array
 */
function search_movies($pdo, $search_term) {
    try {
        $search_pattern = "%{$search_term}%";
        $stmt = $pdo->prepare("SELECT filmId, filmTitle, filmAuthor, filmDetail, filmCategory FROM film WHERE filmTitle LIKE ? OR filmAuthor LIKE ? ORDER BY filmTitle ASC");
        $stmt->execute([$search_pattern, $search_pattern]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error searching movies: " . $e->getMessage());
        return [];
    }
}
