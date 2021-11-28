<?php
require_once("DatabaseConnector.php");

class MediaType extends DatabaseConnector
{

    function statusCode($status)
    {
        $statusInfo['status'] = $status;
        return $statusInfo;
    }

    public function getAllMediaTypes()
    {
        $con = (new DatabaseConnector())->getConnection();

        if ($con) {
            $cQuery = "SELECT * FROM chinook_abridged.mediatype";
            $stmt = $con->query($cQuery);

            while ($row = $stmt->fetch()) {
                $result['mediaTypeId'] = $row['MediaTypeId'];
                $result['name'] = $row['Name'];
                $mediaTypes[] = $result;
            }

            $stmt = null;

            return ($mediaTypes);
        } else {
            return $this->statusCode(ERROR);
        }
    }
}
