<?php
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:*");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    
class RatingController extends BaseController
{
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        
        if (strtoupper($requestMethod) == 'GET') {
            try {
                // Instantiate a RatingModel and call the getRatings method to fetch all ratings
                $ratingModel = new RatingModel();
                $ratings = $ratingModel->getRatings();
                $responseData =  json_encode($ratings);
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            echo $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            echo $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    
    public function viewAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            $ratingId = $_GET['id']; // Assuming the rating ID is passed as a query parameter
            if (empty($ratingId) || !is_numeric($ratingId)) {
                $strErrorDesc = 'Invalid rating ID';
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } else {
                try {
                    // Instantiate a RatingModel and call the getRating method to fetch a specific rating
                    $ratingModel = new RatingModel();
                    $rating = $ratingModel->getRating($ratingId);
                    if ($rating) {
                        $responseData = json_encode($rating);
                    } else {
                        $strErrorDesc = 'Rating not found';
                        $strErrorHeader = 'HTTP/1.1 404 Not Found';
                    }
                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            echo $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            echo $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function createAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
    
        if (strtoupper($requestMethod) == 'POST') {
            $postData = json_decode(file_get_contents('php://input'), true);
    
            // Instantiate a RatingModel
            $ratingModel = new RatingModel();
    
            // Check if a rating with the same song and artist already exists
            if ($ratingModel->ratingExists($postData['username'], $postData['song'], $postData['artist'])) {
                $strErrorDesc = 'You have already rated this song by this artist.';
                $strErrorHeader = 'HTTP/1.1 400 Bad Request';
            } else {
                try {
                    // Call the createRating method
                    $ratingModel->createRating($postData);
                    $responseData = [
                        'success' => true, // Set success to true
                        'message' => 'Rating Created Successfully',
                        'username' => $postData['username'],
                        'artist' => $postData['artist'],
                        'song' => $postData['song'],
                        'rating' => $postData['rating'],
                    ];
                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage();
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    
        if (!$strErrorDesc) {
            echo $this->sendOutput(
                json_encode($responseData),
                array('Content-Type: application/json', 'HTTP/1.1 201 Created')
            );
        } else {
            echo $this->sendOutput(
                json_encode(array('success' => false, 'error' => $strErrorDesc)), // Set success to false
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    
    public function updateAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'PUT') {
            $postData = json_decode(file_get_contents('php://input'), true);
            $ratingModel = new RatingModel();
            try {
                // Instantiate a RatingModel and call the updateRating method
                $ratingModel->updateRating($postData);
                $responseData = [
                    'success' => true, // Set success to true
                    'message' => 'Update successful',
                    'artist' => $postData['artist'],
                    'song' => $postData['song'],
                    'rating' => $postData['rating'],
                ];
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
    
        if (!$strErrorDesc) {
            echo $this->sendOutput(
                json_encode($responseData), 
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            echo $this->sendOutput(
                json_encode(array('success' => false, 'error' => $strErrorDesc)), // Set success to false
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    

    public function deleteAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) == 'DELETE') {
            $postData = json_decode(file_get_contents('php://input'), true);
            $ratingModel = new RatingModel();
            try {
                // Instantiate a RatingModel and call the deleteRating method
                $ratingModel->deleteRating($postData);
                $responseData = [
                    'success' => true,
                    'message' => 'Rating Deleted Successfully',
                ]; 
            } catch (Exception $e) {
                $strErrorDesc = $e->getMessage();
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            echo $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 204 No Content')
            );
        } else {
            echo $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    // Add more functions as necessary
}
?>
