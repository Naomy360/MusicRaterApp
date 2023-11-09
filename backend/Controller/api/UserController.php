<?php
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:*");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    
class UserController extends BaseController
{
    /** 
* "/user/list" Endpoint - Get list of users 
*/
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();
        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();
                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }
                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        // send output 
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
            $userModel = new UserModel();
    
            // Attempt to create the user
            try {
                $userModel->createUser($postData);
    
                // User was successfully created
                $responseData = [
                    'success' => true,
                    'message' => 'User Created Successfully',
                ];
            } catch (Exception $e) {
                // Handle the case where user creation failed
                $strErrorDesc = $e->getMessage().'Username already exists';
                $strErrorHeader = 'HTTP/1.1 401 Unauthorized';
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
            echo $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function loginAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            $postData = json_decode(file_get_contents('php://input'), true);
            $userModel = new UserModel();
                try {
                    // Instantiate a UserModel and call the login method to authenticate the user
                    $user = $userModel->loginUser($postData);

                    if ($user) {
                        // Authentication successful
                        $_SESSION['username'] = $user['username']; // Store user's username in the session

                        $responseData = [
                            'success' => true,
                            'message' => 'Login successful',
                            'username' => $_SESSION['username'],
                        ];
                        
                        // Set the HTTP response status to 200 (OK)
                        http_response_code(200);
                        
                        // Convert the response to JSON and send it
                        echo json_encode($responseData);
                    } else {
                        $strErrorDesc = 'Invalid username or password';
                        $strErrorHeader = 'HTTP/1.1 401 Unauthorized';
                    }
                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage() . ' Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            echo $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    // Add more functions as necessary
}

