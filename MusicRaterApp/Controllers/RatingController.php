<?php

// Include the RatingModel
require_once '../Models/RatingModel.php';

class RatingController {
    // RatingModel instance
    private $model;

    // initialize the RatingModel
    public function __construct() {
        // Instantiate the RatingModel
        $this->model = new RatingModel();
    }

    // Handle the creation of a new rating
    public function createRating($username, $artist, $song, $rating) {
        // Check if the user has already rated the song
        if ($this->model->ratingExists($username, $artist, $song)) {
            return ['success' => false, 'message' => 'You have already rated this song'];
        }

        // Call the addRating method of the RatingModel to add a new rating
        return ['success' => true, 'message' => 'Rating added successfully', 'data' => $this->model->addRating($username, $artist, $song, $rating)];
    }

    // retrieve all ratings
    public function getRatings() {
        // Call the getAllRatings method of the RatingModel
        return $this->model->getAllRatings();
    }

    // update an existing rating
    public function updateRating($id, $artist, $song, $rating) {
        // Call the updateRating method of the RatingModel
        return $this->model->updateRating($id, $artist, $song, $rating);
    }

    // Delete an existing rating
    public function deleteRating($id) {
        // Call the deleteRating method of the RatingModel
        return $this->model->deleteRating($id);
    }
}
