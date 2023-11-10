<?php
require_once '../controllers/UserController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

$userController = new UserController();

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine if the action is to sign up or to log in
    if (isset($data->action) && $data->action === 'signup') {
        $success = $userController->signUp($data->username, $data->password);
        $message = $success ? 'User created successfully' : 'User could not be created';
    } elseif (isset($data->action) && $data->action === 'login') {
        $success = $userController->logIn($data->username, $data->password);
        $message = $success ? 'Login successful' : 'Invalid username or password';
    } else {
        $success = false;
        $message = 'No action specified or action is invalid.';
    }

    echo json_encode(array('success' => $success, 'message' => $message));
} else {
    echo json_encode(array('message' => 'Only POST requests are allowed'));
}

