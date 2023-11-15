<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Controllers/UserController.php';
require_once '../Controllers/RatingController.php'; // Include the RatingController

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE"); // Allow additional methods
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

$userController = new UserController();
$ratingController = new RatingController(); // Instantiate the RatingController

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data->action)) {
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
        echo json_encode(['success' => $success, 'message' => $message]);
    } else {
        echo json_encode(['message' => 'Action not specified']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($ratingController->getRatings());
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (isset($data->id) && isset($data->artist) && isset($data->song) && isset($data->rating)) {
        $result = $ratingController->updateRating($data->id, $data->artist, $data->song, $data->rating);
        echo json_encode(['success' => $result > 0, 'message' => $result > 0 ? 'Rating updated successfully' : 'Failed to update rating']);
    } else {
        echo json_encode(['message' => 'Missing data for updating rating']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($data->id)) {
        $result = $ratingController->deleteRating($data->id);
        echo json_encode(['success' => $result > 0, 'message' => $result > 0 ? 'Rating deleted successfully' : 'Failed to delete rating']);
    } else {
        echo json_encode(['message' => 'Missing ID for deleting rating']);
    }
} else {
    echo json_encode(['message' => 'Method not allowed']);
}
