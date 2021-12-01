<?php
session_start();
//session_destroy();
//echo $_SESSION["isAdmin"];

if (!isset($_SESSION['email'])) {
    header('Location: views/login.php');
}

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
            if ($urlPieces[1] == "tracks") {
                // Get track by id (<current_dir>/music/tracks/{id})
                echo json_encode($track->getTrack($urlPieces[2]));
            } elseif ($urlPieces[1] == "artists") {
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($artist->getArtist($urlPieces[2]));
            } elseif ($urlPieces[1] == "albums") {
                // Get artist by id (<current_dir>/music/artists/{id})
                echo json_encode($album->getAlbum($urlPieces[2]));
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
            } elseif($urlPieces[1] == "logout"){
                session_destroy();
                header("Location: ../views/login.php");
            }
            elseif($urlPieces[1] == "user"){
                // Get current user logic
                // Get email from session
            }
        } elseif (isset($_GET['name'])) {
            if ($urlPieces[1] == "tracks") {
                // Search track by name
                echo json_encode($track->searchTracks($_GET['name']));
            } elseif ($urlPieces[1] == "albums") {
                // Search album by name
                echo json_encode($album->searchAlbums($_GET['name']));
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
        if ($urlPieces[1] == "tracks") {
            $musicData = (array) json_decode(file_get_contents('php://input'), TRUE);
            echo json_encode($track->updateTrack($musicData['trackId'], $musicData['name'], $musicData['albumId'], $musicData['mediaTypeId'], $musicData['genreId'], $musicData['composer'], $musicData['milliseconds'], $musicData['bytes'], $musicData['unitPrice']));
        }
        break;
    case "POST":
        if (count($urlPieces) == 2) {
            if ($urlPieces[1] == "tracks" && isset($_POST['name']) && isset($_POST['mediaTypeId']) && isset($_POST['milliseconds']) && isset($_POST['unitPrice'])) {
                //add track
                $track->addTrack($_POST['name'], $_POST['albumId'], $_POST['mediaTypeId'], $_POST['genreId'], $_POST['composer'], $_POST['milliseconds'], $_POST['bytes'], $_POST['unitPrice']);
                header("Location: ./views/frontpage.php");
            } elseif ($urlPieces[1] == "albums" && isset($_POST['title']) && isset($_POST['artistId'])) {
                //add album
                $album->addAlbum($_POST['title'], $_POST['artistId']);
                header("Location: ./views/frontpage.php");
            } elseif ($urlPieces[1] == "artists" && isset($_POST['name'])) {
                //add artist
                $artist->addArtist($_POST["name"]);
                header("Location: ./views/frontpage.php");
            }
            //Login logic
            elseif ($urlPieces[1] == "login") {
                if (isset($_POST['email']) && isset($_POST['password'])) {
                    $isAdmin = json_encode($user->isAdmin($_POST['password']));
                    if ($isAdmin == "true") {
                        $_SESSION["isAdmin"] = "true";
                    }else{
                        $_SESSION["isAdmin"] = "false";
                    }
                    $response = json_encode($user->login($_POST['email'], $_POST['password']));
                    if ($response == "true") {
                        
                        $_SESSION["email"] = $_POST['email'];
                        header("Location: ../views/frontpage.php");
                    } else {
                        echo "Wrong username or password";
                    }
                }
            }
        }
        break;
    case "DELETE":
        if (count($urlPieces) == 3) {
            if ($urlPieces[1] == "tracks") {
                echo json_encode($track->deleteTrack($urlPieces[2]));
            }
        }
        break;
    default:
        http_response_code(404);
}
