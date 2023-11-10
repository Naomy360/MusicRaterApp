<?php
require_once '../models/User.php';
require_once '../config/database.php';

class UserController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();
        $this->userModel = new User($db);
    }

    public function signUp($username, $password) {
        return $this->userModel->createUser($username, $password);
    }

    public function logIn($username, $password) {
        return $this->userModel->verifyUser($username, $password);
    }
}
