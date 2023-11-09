<?php
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:*");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    
require "Model/Database.php";
class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT * FROM users_table ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }

    public function createUser($userData)
    {
        $sql = "INSERT INTO users_table (username, password) VALUES (?, ?)";
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            // Handle the case where the statement preparation failed
            throw new Exception("Statement preparation failed");
        }
        
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ss', $userData['username'], $hashedPassword);

        if ($stmt->execute()) {
            // User was successfully created
            $stmt->close();
        } else {
            // Handle the case where the user creation failed
            $stmt->close();
            throw new Exception("User creation failed");
        }
    }

    public function loginUser($postData)
    {
        // Check if both username and password are provided
        if (empty($postData['username']) || empty($postData['password'])) {
            $strErrorDesc = 'Username and password are required';
            $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            return null;
        }
    
        // SQL query to retrieve a user with the given username
        $sql = "SELECT * FROM users_table WHERE username = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('s', $postData['username']);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if a user with the given username was found in the database
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            // Verify the password against the hashed password from the database
            if (password_verify($postData['password'], $user['password'])) {
                return $user;
            }
        }
    
        return null; // User not found or invalid password
    }
    
}
?>