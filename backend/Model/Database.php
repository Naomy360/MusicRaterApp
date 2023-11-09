<?php
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:*");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Credentials: true");

class Database
{
    protected $connection = null;

    public function __construct()
    {
        try {
            //Database connection
            $this->connection = new mysqli('localhost', 'root', '', 'music_db');

            if ($this->connection->connect_error) {
                throw new Exception("Database connection failed: " . $this->connection->connect_error);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function select($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            if ($stmt) {
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $result;
            } else {
                throw new Exception("Query execution failed: " . $query);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function insert($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            if ($stmt) {
                return $stmt->insert_id;
            } else {
                throw new Exception("Insertion failed: " . $query);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            if (!$stmt) {
                throw new Exception("Update failed: " . $query);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($query = "", $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);
            if (!$stmt) {
                throw new Exception("Deletion failed: " . $query);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to create a prepared statement: " . $query);
            }
            if ($params) {
                $stmt->bind_param($params[0], ...array_slice($params, 1));
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function closeConnection()
    {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }
}
?>