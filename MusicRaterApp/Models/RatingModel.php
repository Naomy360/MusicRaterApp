<?php
require_once '../Config/Database.php';

class RatingModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function addRating($username, $artist, $song, $rating) {
        $stmt = $this->db->prepare("INSERT INTO ratings_table (username, artist, song, rating) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $artist, $song, $rating]);
        return $stmt->rowCount();
    }
    public function ratingExists($username, $artist, $song) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ratings_table WHERE username = ? AND artist = ? AND song = ?");
        $stmt->execute([$username, $artist, $song]);
        return $stmt->fetchColumn() > 0;
    }

    public function getAllRatings() {
        $stmt = $this->db->query("SELECT * FROM ratings_table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRating($id, $artist, $song, $rating) {
        $stmt = $this->db->prepare("UPDATE ratings_table SET artist = ?, song = ?, rating = ? WHERE id = ?");
        $stmt->execute([$artist, $song, $rating, $id]);
        return $stmt->rowCount();
    }
    

    public function deleteRating($id) {
        $stmt = $this->db->prepare("DELETE FROM ratings_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}



