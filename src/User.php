<?php
    require_once("DatabaseConnector.php");

    define('ERROR', 'error');

    Class User extends DatabaseConnector{

        function statusCode($status) {
            $statusInfo['status'] = $status;
            return $statusInfo;
        }

        public function addUser($username, $email, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "INSERT INTO chinook_abridged.customer (username, email, password) VALUES (?, ?, ?)";
                $stmt= $con->prepare($sql);
                $stmt->execute([$username, $email, $password]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function login($email, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "SELECT * FROM chinook_abridged.customer WHERE email=?";

                $stmt= $con->prepare($sql);
                $stmt->execute([$email]);

                $users = array();
                while($row = $stmt->fetch()) {
                    $result['email'] = $row['Email'];
                    $result['password'] = $row['Password'];
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
