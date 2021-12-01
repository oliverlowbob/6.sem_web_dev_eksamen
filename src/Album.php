<?php
require_once("DatabaseConnector.php");

class Album extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addAlbum($name, $artistId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'INSERT INTO chinook_abridged.album (Title, ArtistId) VALUES (?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->execute([$name, $artistId]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function deleteAlbum($albumId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'DELETE FROM chinook_abridged.album WHERE AlbumId=?';
            $stmt = $con->prepare($sql);
            $stmt->execute([$albumId]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function updateAlbum($id, $name, $artistId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'UPDATE chinook_abridged.album SET Title=?, ArtistId=? WHERE AlbumId=?';
            $stmt = $con->prepare($sql);
            $stmt->execute([$name, $artistId, $id]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getAlbum($albumId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = 'SELECT * FROM chinook_abridged.album WHERE AlbumId=?';
            $stmt = $con->prepare($sql);
            $stmt->execute([$albumId]);

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Title'];
                $result['albumId'] = $row['AlbumId'];
                $albums[] = $result;
            }

            $stmt = null;

            return ($albums[0]);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getAllAlbums()
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $cQuery = 'SELECT * FROM chinook_abridged.album';
            $stmt = $con->query($cQuery);

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Title'];
                $result['albumId'] = $row['AlbumId'];
                $albums[] = $result;
            }

            $stmt = null;

            return ($albums);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function searchAlbums($searchText)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $likeVar = "%" . $searchText . "%";
            $sql = 'SELECT * FROM chinook_abridged.album WHERE Title LIKE ?';
            $stmt = $con->prepare($sql);
            $stmt->execute([$likeVar]);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Title'];
                $result['albumId'] = $row['AlbumId'];
                $albums[] = $result;
            }

            $results['results'] = $albums;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
