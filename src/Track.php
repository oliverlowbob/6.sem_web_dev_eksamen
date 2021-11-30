<?php
require_once("DatabaseConnector.php");

class Track extends DatabaseConnector
{
    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addTrack($name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "INSERT INTO chinook_abridged.track (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->execute([$name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function deleteTrack($trackId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "DELETE FROM chinook_abridged.track WHERE TrackId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$trackId]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function updateTrack($id, $name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "UPDATE chinook_abridged.track SET Name=?, AlbumId=?, MediaTypeId=?, GenreId=?, Composer=?, Milliseconds=?, Bytes=?, UnitPrice=? WHERE TrackId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$name, $albumId, $mediaTypeId, $genreId, $composer, $milliseconds, $bytes, $unitPrice, $id]);
            $stmt = null;
            return;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getTrack($trackId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "SELECT * FROM chinook_abridged.track WHERE TrackId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$trackId]);

            while ($row = $stmt->fetch()) {
                $result['trackId'] = $row['TrackId'];
                $result['name'] = $row['Name'];
                $result['albumId'] = $row['AlbumId'];
                $result['mediaTypeId'] = $row['MediaTypeId'];
                $result['genreId'] = $row['GenreId'];
                $result['composer'] = $row['Composer'];
                $result['milliseconds'] = $row['Milliseconds'];
                $result['bytes'] = $row['Bytes'];
                $result['unitPrice'] = $row['UnitPrice'];
                $tracks[] = $result;
            }

            $stmt = null;

            return ($tracks[0]);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getAllTracks()
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $cQuery = "SELECT * FROM chinook_abridged.track";
            $stmt = $con->query($cQuery);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['trackId'] = $row['TrackId'];
                $result['name'] = $row['Name'];
                $result['albumId'] = $row['AlbumId'];
                $result['mediaTypeId'] = $row['MediaTypeId'];
                $result['genreId'] = $row['GenreId'];
                $result['composer'] = $row['Composer'];
                $result['milliseconds'] = $row['Milliseconds'];
                $result['bytes'] = $row['Bytes'];
                $result['unitPrice'] = $row['UnitPrice'];
                $tracks[] = $result;
            }

            $results['results'] = $tracks;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getTracksByAlbumId($albumId){
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "SELECT * FROM chinook_abridged.track WHERE AlbumId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$albumId]);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['trackId'] = $row['TrackId'];
                $result['name'] = $row['Name'];
                $result['albumId'] = $row['AlbumId'];
                $result['mediaTypeId'] = $row['MediaTypeId'];
                $result['genreId'] = $row['GenreId'];
                $result['composer'] = $row['Composer'];
                $result['milliseconds'] = $row['Milliseconds'];
                $result['bytes'] = $row['Bytes'];
                $result['unitPrice'] = $row['UnitPrice'];
                $tracks[] = $result;
            }

            $results['results'] = $tracks;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function searchTracks($searchText)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $likeVar = "%" . $searchText . "%";
            $sql = "SELECT * FROM chinook_abridged.track WHERE Name LIKE ?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$likeVar]);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['trackId'] = $row['TrackId'];
                $result['name'] = $row['Name'];
                $result['albumId'] = $row['AlbumId'];
                $result['mediaTypeId'] = $row['MediaTypeId'];
                $result['genreId'] = $row['GenreId'];
                $result['composer'] = $row['Composer'];
                $result['milliseconds'] = $row['Milliseconds'];
                $result['bytes'] = $row['Bytes'];
                $result['unitPrice'] = $row['UnitPrice'];
                $tracks[] = $result;
            }

            $results['results'] = $tracks;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
