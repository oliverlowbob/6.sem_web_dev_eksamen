<?php
// session_start();

// if (!isset($_SESSION['username'])) {
//     echo "Not logged in";
//     return;
// }

require_once("src/Track.php");
require_once("src/User.php");
require_once("src/Artist.php");
require_once("src/Album.php");
require_once("src/MediaType.php");
require_once("src/Genre.php");

$track = new Track();
$user = new User();
$artist = new Artist();
$album = new Album();
$mediaType = new MediaType();
$genre = new Genre();

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
            if($urlPieces[1] == "tracks"){
                // Get track by id (<current_dir>/music/tracks/{id})
                echo json_encode($track->getTrack($urlPieces[2]));
            }
            elseif($urlPieces[1] == "artists"){
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($artist->getArtist($urlPieces[2]));
            }
            elseif($urlPieces[1] == "albums"){
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($album->getAlbum($urlPieces[2]));
            }
        }
        elseif (count($urlPieces) == 2 && !isset($_GET['name'])) {
            if($urlPieces[1] == "tracks"){
                // Get all tracks                        
                echo json_encode($track->getAllTracks());
            }
            elseif($urlPieces[1] == "albums"){
                // Get all albums                        
                echo json_encode($album->getAllAlbums());
            }
            elseif($urlPieces[1] == "mediaTypes"){
                // Get all mediaTypes                        
                echo json_encode($mediaType->getAllMediaTypes());
            }
            elseif($urlPieces[1] == "genres"){
                // Get all genres                        
                echo json_encode($genre->getAllGenres());
            }
            elseif($urlPieces[1] == "artists"){
                // Get all genres                        
                echo json_encode($artist->getAllArtists());
            }
        }
        elseif (isset($_GET['name'])) {
            if($urlPieces[1] == "tracks"){
                // Search track by name
                echo json_encode($track->searchTracks($_GET['name']));
            }
            elseif($urlPieces[1] == "albums"){
                // Search album by name
                echo json_encode($album->searchAlbums($_GET['name']));
            }
        } 
        else{
            http_response_code(404);
        }
        break;
    case "PUT":
        if($urlPieces[1] == "tracks"){
            $musicData = (array) json_decode(file_get_contents('php://input'), TRUE);
            echo json_encode($track->updateTrack($musicData['trackId'], $musicData['name'], $musicData['albumId'], $musicData['mediaTypeId'], $musicData['genreId'], $musicData['composer'], $musicData['milliseconds'], $musicData['bytes'], $musicData['unitPrice']));
        }
        break;
    case "POST":
        //add track
        if (count($urlPieces) == 2 &&  $urlPieces[1] == "tracks" && isset($_POST['name']) && isset($_POST['mediaTypeId']) && isset($_POST['milliseconds']) && isset($_POST['unitPrice'])) {
            $track->addTrack($_POST['name'], $_POST['albumId'], $_POST['mediaTypeId'], $_POST['genreId'], $_POST['composer'], $_POST['milliseconds'], $_POST['bytes'], $_POST['unitPrice']);
            header("Location: frontpage.php");
        } elseif ($urlPieces[1] == "login") {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $response = json_encode($user->login($_POST['username'], $_POST['password']));
                if ($response == "true") {
                    $_SESSION["username"] = $_POST['username'];
                    header("Location: frontpage.php");
                }
            }
        }
        break;
    case "DELETE":
        if (count($urlPieces) == 2) {
            echo json_encode($track->deleteTrack($urlPieces[1]));
        }
        break;
    default:
        http_response_code(404);
}
