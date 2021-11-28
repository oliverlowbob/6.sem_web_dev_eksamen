<?php
require_once("DatabaseConnector.php");

class Artist extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function addArtist($name)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "INSERT INTO chinook_abridged.artist (Name) VALUES (?)";
            $stmt = $con->prepare($sql);
            $stmt->execute([$name]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function deleteArtist($artistId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "DELETE FROM chinook_abridged.artist WHERE ArtistId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$artistId]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function updateArtist($id, $name)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "UPDATE chinook_abridged.artist SET Name=? WHERE ArtistId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$name, $id]);
            $stmt = null;
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getArtist($artistId)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $sql = "SELECT * FROM chinook_abridged.artist WHERE ArtistId=?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$artistId]);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Name'];
                $artists[] = $result;
            }

            $results['results'] = $artists;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function getAllArtists()
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $cQuery = "SELECT * FROM chinook_abridged.artist";
            $stmt = $con->query($cQuery);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Name'];
                $artists[] = $result;
            }

            $results['results'] = $artists;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }

    public function searchArtists($searchText)
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $likeVar = "%" . $searchText . "%";
            $sql = "SELECT * FROM chinook_abridged.artist WHERE Name LIKE ?";
            $stmt = $con->prepare($sql);
            $stmt->execute([$likeVar]);

            $results['_total'] = $stmt->rowCount();

            while ($row = $stmt->fetch()) {
                $result['artistId'] = $row['ArtistId'];
                $result['name'] = $row['Name'];
                $artists[] = $result;
            }

            $results['results'] = $artists;

            $stmt = null;

            return ($results);
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
