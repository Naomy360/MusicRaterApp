<?php
require_once '../Config/Database.php';

class RatingModel {
    // Database connection
    private $db;
    public function __construct() {
        // Create a new Database instance and establish a connection
        $database = new Database();
        $this->db = $database->connect();
    }

    // add a new rating to the database
    public function addRating($username, $artist, $song, $rating) {
        // Prepare and execute the SQL query to insert a new rating
        $stmt = $this->db->prepare("INSERT INTO ratings_table (username, artist, song, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $artist, $song, $rating]);
        return $stmt->rowCount(); // Return the number of affected rows
    }

    // Check if a rating with the given parameters already exists
    public function ratingExists($username, $artist, $song) {
        // Prepare and execute the SQL query to check if the rating exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ratings_table WHERE username = ? AND artist = ? AND song = ?");
        $stmt->execute([$username, $artist, $song]);
        return $stmt->fetchColumn() > 0; // Return true if count is greater than 0
    }

    // Get all ratings from the database
    public function getAllRatings() {
        // Execute a query to select all ratings
        $stmt = $this->db->query("SELECT * FROM ratings_table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the result as an associative array
    }

    // Update a rating in the database
    public function updateRating($id, $artist, $song, $rating) {
        // Prepare and execute the SQL query to update a rating
        $stmt = $this->db->prepare("UPDATE ratings_table SET artist = ?, song = ?, rating = ? WHERE id = ?");
        $stmt->execute([$artist, $song, $rating, $id]);
        return $stmt->rowCount(); // Return the number of affected rows
    }

    // Delete a rating from the database
    public function deleteRating($id) {
        // Prepare and execute the SQL query to delete a rating
        $stmt = $this->db->prepare("DELETE FROM ratings_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount(); // Return the number of affected rows
    }
}
