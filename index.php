<?php
/**
 * SD Exam 2021 - Music Webshop
 * Refer to README.md for API documentation
 * 
 * @author  Oliver Dehnfjeld
 * @version 1.0, December 2021
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../views/login.php');
}

require_once("src/Track.php");
require_once("src/User.php");
require_once("src/Artist.php");
require_once("src/Album.php");
require_once("src/MediaType.php");
require_once("src/Genre.php");
require_once("src/Invoice.php");
require_once("src/InvoiceLine.php");

$track = new Track();
$user = new User();
$artist = new Artist();
$album = new Album();
$mediaType = new MediaType();
$genre = new Genre();
$invoice = new Invoice();
$invoiceLine = new InvoiceLine();

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

switch ($requestMethod) {
    case "GET":
        if (count($urlPieces) == 3) {
            if ($urlPieces[1] == "tracks") {
                // Get track by id (<current_dir>/music/tracks/{id})
                echo json_encode($track->getTrack($urlPieces[2]));
            } elseif ($urlPieces[1] == "artists") {
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($artist->getArtist($urlPieces[2]));
            } elseif ($urlPieces[1] == "albums") {
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($album->getAlbum($urlPieces[2]));
            } elseif ($urlPieces[1] == "users" && $urlPieces[2] == "me") {
                // Get the logged in user info
                echo json_encode($user->getMe($_SESSION['email']));
            }
        } elseif (count($urlPieces) == 2 && !isset($_GET['name']) && !isset($_GET['albumId'])) {
            if ($urlPieces[1] == "tracks") {
                // Get all tracks                        
                echo json_encode($track->getAllTracks());
            } elseif ($urlPieces[1] == "albums") {
                // Get all albums                        
                echo json_encode($album->getAllAlbums());
            } elseif ($urlPieces[1] == "mediaTypes") {
                // Get all mediaTypes                        
                echo json_encode($mediaType->getAllMediaTypes());
            } elseif ($urlPieces[1] == "genres") {
                // Get all genres                        
                echo json_encode($genre->getAllGenres());
            } elseif ($urlPieces[1] == "artists") {
                // Get all genres                        
                echo json_encode($artist->getAllArtists());
            } elseif ($urlPieces[1] == "admin") {
                // Check if user is admin
                if (isset($_SESSION["isAdmin"])) {
                    echo json_encode($_SESSION["isAdmin"]);
                } else {
                    echo json_encode(false);
                }
            } elseif ($urlPieces[1] == "logout") {
                // Log out
                session_destroy();
                header("Location: ./views/login.php");
            }
        } elseif (isset($_GET['name'])) {
            if ($urlPieces[1] == "tracks") {
                // Search track by name
                echo json_encode($track->searchTracks($_GET['name']));
            } elseif ($urlPieces[1] == "albums") {
                // Search album by name
                echo json_encode($album->searchAlbums($_GET['name']));
            } elseif ($urlPieces[1] == "artists") {
                // Search artist by name
                echo json_encode($artist->searchArtists($_GET['name']));
            }
        } elseif (isset($_GET['albumId'])) {
            if ($urlPieces[1] == "tracks") {
                //get tracks by album Id
                echo json_encode($track->getTracksByAlbumId($_GET['albumId']));
            }
        } else {
            http_response_code(404);
        }
        break;
    case "PUT":
        $putData = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (count($urlPieces) == 2) {
            if ($urlPieces[1] == "tracks" && $_SESSION["isAdmin"] == "true") {
                // Update Track
                echo json_encode($track->updateTrack($putData['trackId'], $putData['name'], $putData['albumId'], $putData['mediaTypeId'], $putData['genreId'], $putData['composer'], $putData['milliseconds'], $putData['bytes'], $putData['unitPrice']));
            } elseif ($urlPieces[1] == "users" && isset($putData['password'])) {
                // Update user password
                echo json_encode($user->updatePassword($putData['customerId'], $putData['password']));
            } elseif ($urlPieces[1] == "users" && isset($putData['customerId']) && isset($putData['firstName']) && isset($putData['lastName']) && isset($putData['email'])) {
                // Update user info
                echo json_encode($user->updateUser($putData['customerId'], $putData['firstName'], $putData['lastName'], $putData['company'], $putData['address'], $putData['city'], $putData['state'], $putData['country'], $putData['postalCode'], $putData['phone'], $putData['fax'], $putData['email']));
            } elseif ($urlPieces[1] == "albums" && $_SESSION["isAdmin"] == "true") {
                // Update album
                echo json_encode($album->updateAlbum($putData['albumId'], $putData['name'], $putData['artistId']));
            } elseif ($urlPieces[1] == "artists" && $_SESSION["isAdmin"] == "true") {
                // Update artist
                echo json_encode($artist->updateArtist($putData['artistId'], $putData['name']));
            }
        }
        else{
            http_response_code(404);
        }
        break;
    case "POST":
        if (count($urlPieces) == 2) {
            if ($urlPieces[1] == "tracks" && $_SESSION["isAdmin"] == "true" && isset($_POST['name']) && isset($_POST['mediaTypeId']) && isset($_POST['milliseconds']) && isset($_POST['unitPrice'])) {
                //add track
                echo json_encode($track->addTrack($_POST['name'], $_POST['albumId'], $_POST['mediaTypeId'], $_POST['genreId'], $_POST['composer'], $_POST['milliseconds'], $_POST['bytes'], $_POST['unitPrice']));
            } elseif ($urlPieces[1] == "albums" && $_SESSION["isAdmin"] == "true" && isset($_POST['title']) && isset($_POST['artistId'])) {
                //add album
                echo json_encode($album->addAlbum($_POST['title'], $_POST['artistId']));
            } elseif ($urlPieces[1] == "artists" && $_SESSION["isAdmin"] == "true" && isset($_POST['name'])) {
                //add artist
                echo json_encode($artist->addArtist($_POST["name"]));
            } elseif ($urlPieces[1] == "login" && isset($_POST['email']) && isset($_POST['password'])) {
                // Login
                $isAdmin = json_encode($user->isAdmin($_POST['password']));
                if ($isAdmin == "true") {
                    $_SESSION["isAdmin"] = "true";
                    $_SESSION["email"] = "admin";
                } else {
                    $_SESSION["isAdmin"] = "false";
                    $response = json_encode($user->login($_POST['email'], $_POST['password']));
                    if ($response == "true") {
                        $_SESSION["email"] = $_POST['email'];
                    } else {
                        echo 'Wrong username or password';
                    }
                }
            } elseif ($urlPieces[1] == "signup" && isset($_POST['email']) && isset($_POST['firstName'])  && isset($_POST['lastName'])  && isset($_POST['password']) ){
                // Signup 
                echo json_encode($user->addUser($_POST['firstName'], $_POST['lastName'], $_POST['password'], $_POST['email'], $_POST['company'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['country'], $_POST['postalCode'], $_POST['phone'], $_POST['fax']));
            } elseif($urlPieces[1] == "invoices" ){
                $postData = (array) json_decode(file_get_contents('php://input'), TRUE);
                if(isset($postData['customerId']) && isset($postData['date']) && isset($postData['cart'])){
                    // Checkout
                    echo json_encode($invoice->addInvoice($postData['customerId'], $postData['date'], $postData['cart'], $postData['address'], $postData['city'], $postData['state'], $postData['country'], $postData['postalCode'], $postData['postalCode']));
                }
            }
        }
        elseif (count($urlPieces) == 3){
            if($urlPieces[1] == "users"){
                $postData = (array) json_decode(file_get_contents('php://input'), TRUE);
                if($urlPieces[2] == "verify" && isset($postData['customerId']) && isset($postData['password'])){
                    // Verify user password
                    echo json_encode($user->verifyPassword($postData['customerId'], $postData['password']));
                }
            }
        }
        else{
            http_response_code(404);
        }
        break;
    case "DELETE":
        if (count($urlPieces) == 3 && $_SESSION["isAdmin"] == "true") {
            if ($urlPieces[1] == "tracks") {
                // Delete track
                echo json_encode($track->deleteTrack($urlPieces[2]));
            } elseif ($urlPieces[1] == "albums") {
                // Delete album
                echo json_encode($album->deleteAlbum($urlPieces[2]));
            } elseif ($urlPieces[1] == "artists") {
                // Delete artist
                echo json_encode($artist->deleteArtist($urlPieces[2]));
            }
        }
        else{
            http_response_code(404);
        }
        break;
    default:
        http_response_code(404);
}
