<?php
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:*");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Credentials: true");

class RatingModel extends Database
{
    public function getRatings()
    {
        // Need to implement the logic to retrieve all ratings
        $sql = "SELECT * FROM ratings_table";
        $ratings = $this->select($sql);
        return $ratings;
    }

    public function getRating($ratingId)
    {
        // Retrieve a specific rating by its ID
        $sql = "SELECT * FROM ratings_table WHERE id = ?";
        $rating = $this->select($sql, ["i", $ratingId]);

        if (count($rating) > 0) {
            return $rating[0];
        }

        return null;
    }

    public function createRating($postData)
    {
        // Create a new rating
        $sql = "INSERT INTO ratings_table (username, artist, song, rating) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('sssi', $postData['username'], $postData['artist'], $postData['song'], $postData['rating']);

        if ($stmt->execute()) {
            // Rating was successfully created
            $stmt->close();
        } else {
            // Handle the case where the user creation failed
            $stmt->close();
            throw new Exception("Rating creation failed");
        }
    }

    public function updateRating($postData)
    {
        // Update an existing rating
        $sql = "UPDATE ratings_table SET artist=?, song=?, rating=? WHERE id=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssii", $postData['artist'], $postData['song'], $postData['rating'], $postData['id']);

        if ($stmt->execute()) {
            // Rating was successfully updated
            $stmt->close();
        } else {
            // Handle the case where the rating update failed
            $stmt->close();
            throw new Exception("Rating update failed");
        }
    }

    public function deleteRating($postData)
    {
        // Delete an existing rating
        $sql = "DELETE FROM ratings_table WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $postData['id']);
    
        if ($stmt->execute()) {
            // Rating deleted successfully
            $stmt->close();
        } else {
            // Handle the case where the rating deletion failed
            $stmt->close();
            throw new Exception("Rating deletion failed");
        }
    }

    public function ratingExists($username, $song, $artist)
    {
        $sql = "SELECT COUNT(*) FROM ratings_table WHERE username = ? AND song = ? AND artist = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('sss', $username, $song, $artist);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0; // If count is greater than 0, a matching rating exists.
    }
}
?>
