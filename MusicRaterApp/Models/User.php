<?php

// Define the User class
class User {
    // Database connection and table name
    private $db;
    private $table = 'users_table';

    // Constructor with database connection injection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to create a new user
    public function createUser($username, $password) {
        // Hash the password 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // query to insert a new user
        $query = "INSERT INTO " . $this->table . " SET username=:username, password=:password";

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Bind values to placeholders
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);

        // Execute the query and return success status
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Method to verify user credentials during login
    public function verifyUser($username, $password) {
        // Query to retrieve user by username
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 0,1";

        // Prepare the query
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        // Check if user with the provided username exists
        if ($stmt->rowCount() > 0) {
            // Fetch user data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verify the provided password against the hashed password in the database
            return password_verify($password, $row['password']);
        } else {
            // If no user found with the provided username, return false
            return false;
        }
    }
}
