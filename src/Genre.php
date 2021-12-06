<?php
require_once("DatabaseConnector.php");

class Genre extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function getAllGenres()
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $cQuery = 'SELECT * FROM chinook_abridged.genre';
            $stmt = $con->query($cQuery);

            while ($row = $stmt->fetch()) {
                $result['genreId'] = $row['GenreId'];
                $result['name'] = $row['Name'];
                $genres[] = $result;
            }

            if(empty($genres)){
                return null;
            }

            $stmt = null;

            return ($genres);
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
