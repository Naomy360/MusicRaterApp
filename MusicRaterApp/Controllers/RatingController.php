<?php
require_once '../Models/RatingModel.php';

class RatingController {
    private $model;

    public function __construct() {
        $this->model = new RatingModel();
    }

    public function createRating($username, $artist, $song, $rating) {
        if ($this->model->ratingExists($username, $artist, $song)) {
            return ['success' => false, 'message' => 'You have already rated this song'];
        }
        // Insert rating logic remains the same
        return ['success' => true, 'message' => 'Rating added successfully', 'data' => $this->model->addRating($username, $artist, $song, $rating)];
    }
    
    

    public function getRatings() {
        return $this->model->getAllRatings();
    }
    public function updateRating($id, $artist, $song, $rating) {
        return $this->model->updateRating($id, $artist, $song, $rating);
    }
    

    public function deleteRating($id) {
        return $this->model->deleteRating($id);
    }
}


