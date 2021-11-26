<?php
    require_once("DatabaseConnector.php");

    define('ERROR', 'error');

    Class Users extends DatabaseConnector{

        function statusCode($status) {
            $statusInfo['status'] = $status;
            return $statusInfo;
        }

        public function addUser($username, $email, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "INSERT INTO films.user (username, email, password) VALUES (?, ?, ?)";
                $stmt= $con->prepare($sql);
                $stmt->execute([$username, $email, $password]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function login($username, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $results = array();

                $cQuery = "SELECT * FROM films.user WHERE username = " . $username . ";";

                $stmt = $con->query($cQuery);      

                $users = array();
                while($row = $stmt->fetch()) {
                    $result['username'] = $row['username'];
                    $result['password'] = $row['password'];
                    $users[] = $result;
                }
                
                $stmt = null;
                
                if(empty($users)){
                    return false;
                }
                else{
                    $user = $users[0];
                    if($user['password'] == $password){
                        return true;
                    }
                    else{
                        return false;
                    }
                }

            } else {
                return $this->statusCode(ERROR);
            } 
        }

        
    }
