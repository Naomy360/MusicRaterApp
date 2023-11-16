<?php

// Include the User model and Database configuration files
require_once '../models/User.php';
require_once '../config/database.php';

class UserController {
    // User model instance
    private $userModel;

    // initialize the user model
    public function __construct() {
        // Create a new Database instance and establish a connection
        $database = new Database();
        $db = $database->connect();

        // Instantiate the User model with the database connection
        $this->userModel = new User($db);
    }

    // Handle user signup
    public function signUp($username, $password) {
        // Call the createUser method of the User model to create a new user
        return $this->userModel->createUser($username, $password);
    }

    // Handle user login
    public function logIn($username, $password) {
        // Call the verifyUser method of the User model to verify user credentials
        return $this->userModel->verifyUser($username, $password);
    }
}
