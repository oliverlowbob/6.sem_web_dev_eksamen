<?php
class DatabaseConnector {

    private $dbConnection = null;

    public function __construct()
    {
        $host = '127.0.0.1';
        $port = 3306;
        $db   = 'chinook_abridged';
        $user = 'root';
        $pass = '';

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
?>
