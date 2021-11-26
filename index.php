<?php 
    require_once("src/Movies.php");
    require_once("src/Users.php");

    $movies = new Movies();
    $users = new users();

    $url = strtok($_SERVER['REQUEST_URI'], "?");    // GET parameters are removed
    // If there is a trailing slash, it is removed, so that it is not taken into account by the explode function
    if (substr($url, strlen($url) - 1) == '/') {
        $url = substr($url, 0, strlen($url) - 1);
    }
    // Everything up to the folder where this file exists is removed.
    // This allows the API to be deployed to any directory in the server
    $url = substr($url, strpos($url, basename(__DIR__)));

    $urlPieces = explode('/', urldecode($url));

    header('Content-Type: application/json');
    header('Accept-version: v1');

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    switch($requestMethod){
        case "GET":
            if(count($urlPieces) == 2){         
                // Get movie by id (<current_dir>/movies/{id})
                echo json_encode($movies->getMovie($urlPieces[1]));
            }
            elseif (isset($_GET['name'])) {       
                // Search movie by name
                echo json_encode($movies->searchMovies($_GET['name']));
            } 
            else {                    
                // Get all movies                        
                echo json_encode($movies->getAllMovies());
            }
            break;
        case "PUT":
            $movieData = (array) json_decode(file_get_contents('php://input'), TRUE);
            echo json_encode($movies->updateMovie($movieData['movieId'], $movieData['title'], $movieData['overview'], $movieData['released'], $movieData['runtime']));
            break;
        case "POST":
            if(isset($_POST['title']) && isset($_POST['overview']) && isset($_POST['released']) && isset($_POST['runtime'])){
                $movies->addMovie($_POST['title'], $_POST['overview'], $_POST['released'], $_POST['runtime']);
                header("Location: frontpage.php");
            }
            elseif($urlPieces[1] == "login"){
                if(isset($_POST['username']) && isset($_POST['password'])){
                    $response = json_encode($users->login($_POST['username'], $_POST['password']));
                    if($response == "true"){
                        session_start();
                        $_SESSION["username"] = $_POST['username'];
                        header("Location: frontpage.php");
                    }
                }
            }
            break;
        case "DELETE":
            if(count($urlPieces) == 2){       
                echo json_encode($movies->deleteMovie($urlPieces[1]));
            }
            break;
        }
