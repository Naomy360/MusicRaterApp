<?php
class User {
    private $db;
    private $table = 'users_table';

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($username, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table . " SET username=:username, password=:password";

        $stmt = $this->db->prepare($query);

        // Bind values
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function verifyUser($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 0,1";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return password_verify($password, $row['password']);
        } else {
            return false;
        }
    }
}
