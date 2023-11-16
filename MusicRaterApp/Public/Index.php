<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary controllers
require_once '../Controllers/UserController.php';
require_once '../Controllers/RatingController.php'; // Include the RatingController

// Set headers for cross-origin resource sharing (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE"); // Allow additional methods
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

// Instantiate User and Rating controllers
$userController = new UserController();
$ratingController = new RatingController(); // Instantiate the RatingController

// Decode JSON data from the request
$data = json_decode(file_get_contents("php://input"));

// Handle different HTTP methods
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is specified for POST requests
    if (isset($data->action)) {
        // Handle different actions for POST requests
        switch ($data->action) {
            case 'signup':
                $success = $userController->signUp($data->username, $data->password);
                $message = $success ? 'User created successfully' : 'User could not be created';
                break;
            case 'login':
                $success = $userController->logIn($data->username, $data->password);
                $message = $success ? 'Login successful' : 'Invalid username or password';
                break;
            case 'createRating':
                $result = $ratingController->createRating($data->username, $data->artist, $data->song, $data->rating);
                $success = $result['success'];
                $message = $result['message'];
                break;
            default:
                $success = false;
                $message = 'No action specified or action is invalid.';
                break;
        }
        // Respond with JSON indicating success and a message
        echo json_encode(['success' => $success, 'message' => $message]);
    } else {
        // Respond with JSON if action is not specified
        echo json_encode(['message' => 'Action not specified']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Respond with JSON containing ratings for GET requests
    echo json_encode($ratingController->getRatings());
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle updating a rating for PUT requests
    if (isset($data->id) && isset($data->artist) && isset($data->song) && isset($data->rating)) {
        $result = $ratingController->updateRating($data->id, $data->artist, $data->song, $data->rating);
        // Respond with JSON indicating success and a message
        echo json_encode(['success' => $result > 0, 'message' => $result > 0 ? 'Rating updated successfully' : 'Failed to update rating']);
    } else {
        // Respond with JSON if data for updating rating is missing
        echo json_encode(['message' => 'Missing data for updating rating']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle deleting a rating for DELETE requests
    if (isset($data->id)) {
        $result = $ratingController->deleteRating($data->id);
        // Respond with JSON indicating success and a message
        echo json_encode(['success' => $result > 0, 'message' => $result > 0 ? 'Rating deleted successfully' : 'Failed to delete rating']);
    } else {
        // Respond with JSON if ID for deleting rating is missing
        echo json_encode(['message' => 'Missing ID for deleting rating']);
    }
} else {
    // Respond with JSON if the HTTP method is not allowed
    echo json_encode(['message' => 'Method not allowed']);
}
?>
