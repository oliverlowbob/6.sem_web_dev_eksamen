<?php
    require_once("DatabaseConnector.php");

    define('ERROR', 'error');

    Class User extends DatabaseConnector{

        function statusCode($status) {
            $statusInfo['status'] = $status;
            return $statusInfo;
        }

        public function updateUser($customerId, $firstName, $lastName, $company, $address, $city, $state, $country, $postalCode, $phone, $fax, $email){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "UPDATE chinook_abridged.customer SET FirstName=?, LastName=?, Company=?, Address=?, City=?, State=?, Country=?, PostalCode=?, Phone=?, Fax=?, Email=? WHERE CustomerId=?";
                $stmt = $con->prepare($sql);
                $stmt->execute([htmlspecialchars($firstName), htmlspecialchars($lastName), htmlspecialchars($company), htmlspecialchars($address), htmlspecialchars($city), htmlspecialchars($state), htmlspecialchars($country), htmlspecialchars($postalCode), htmlspecialchars($phone), htmlspecialchars($fax), htmlspecialchars($email), $customerId]);
                $stmt = null;
                return;
            } else {
                return $this->statusCode(ERROR);
            }            
        }

        public function updatePassword($customerId, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "UPDATE chinook_abridged.customer SET Password=? WHERE CustomerId=?";
                $stmt = $con->prepare($sql);
                $stmt->execute([$password, $customerId]);
                $stmt = null; 
                return;
            } else {
                return $this->statusCode(ERROR);
            }            
        }

        public function getMe($email){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = 'SELECT * FROM chinook_abridged.customer WHERE Email=?';

                $stmt= $con->prepare($sql);
                $stmt->execute([$email]);

                while($row = $stmt->fetch()) {
                    $result['customerId'] = $row['CustomerId'];
                    $result['firstName'] = $row['FirstName'];
                    $result['lastName'] = $row['LastName'];
                    $result['oldPassword'] = $row['Password'];
                    $result['company'] = $row['Company'];
                    $result['address'] = $row['Address'];
                    $result['city'] = $row['City'];
                    $result['state'] = $row['State'];
                    $result['country'] = $row['Country'];
                    $result['postalCode'] = $row['PostalCode'];
                    $result['phone'] = $row['Phone'];
                    $result['fax'] = $row['Fax'];
                    $result['email'] = $row['Email'];
                    $users[] = $result;
                }
                
                $stmt = null;
                
                return $users[0];
            } else {
                return $this->statusCode(ERROR);
            } 
        }

        public function addUser($username, $email, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = 'INSERT INTO chinook_abridged.customer (username, email, password) VALUES (?, ?, ?)';
                $stmt= $con->prepare($sql);
                $stmt->execute([htmlspecialchars($username), htmlspecialchars($email), htmlspecialchars($password)]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function isAdmin($password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = 'SELECT * FROM chinook_abridged.admin WHERE Password=?';

                $stmt= $con->prepare($sql);
                $stmt->execute([$password]);

                while($row = $stmt->fetch()) {
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

        public function login($email, $password){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = 'SELECT * FROM chinook_abridged.customer WHERE email=?';

                $stmt= $con->prepare($sql);
                $stmt->execute([$email]);

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