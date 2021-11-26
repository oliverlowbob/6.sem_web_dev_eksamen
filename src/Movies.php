<?php
    require_once("DatabaseConnector.php");

    Class Movies extends DatabaseConnector{

        function statusCode($status) {
            $statusInfo['status'] = $status;
            return $statusInfo;
        }

        public function addMovie($title, $overview, $released, $runtime){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "INSERT INTO films.movie (title, overview, release_date, runtime) VALUES (?, ?, ?, ?)";
                $stmt= $con->prepare($sql);
                $stmt->execute([$title, $overview, $released, $runtime]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function deleteMovie($movieId){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "DELETE FROM films.movie WHERE movie_id=?";
                $stmt= $con->prepare($sql);
                $stmt->execute([$movieId]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function updateMovie($id, $title, $overview, $released, $runtime){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $sql = "UPDATE films.movie SET title=?, overview=?, release_date=?, runtime=? WHERE movie_id=?";
                $stmt= $con->prepare($sql);
                $stmt->execute([$title, $overview, $released, $runtime, $id]);
                $stmt = null;
            }else {
                return $this->statusCode(ERROR);
            } 
        }

        public function getMovie($movieId){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $results = array();

                $cQuery = "SELECT * FROM films.movie WHERE movie_id = " . $movieId . ";";

                $stmt = $con->query($cQuery);      

                $results['_total'] = $stmt->rowCount();
                
                $movies = array();
                while($row = $stmt->fetch()) {
                    $result['id'] = $row['movie_id'];
                    $result['title'] = $row['title'];
                    $result['overview'] = $row['overview'];
                    $result['released'] = $row['release_date'];
                    $result['runtime'] = $row['runtime'];
                    $movies[] = $result;
                }
                
                $results['results'] = $movies;

                $stmt = null;
                
                return($results);

            } else {
                return $this->statusCode(ERROR);
            } 
        }

        public function getAllMovies(){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $results = array();

                $cQuery = "SELECT * FROM films.movie";

                $stmt = $con->query($cQuery);      

                $results['_total'] = $stmt->rowCount();
                
                $movies = array();
                while($row = $stmt->fetch()) {
                    $result['id'] = $row['movie_id'];
                    $result['title'] = $row['title'];
                    $result['overview'] = $row['overview'];
                    $result['released'] = $row['release_date'];
                    $result['runtime'] = $row['runtime'];
                    $movies[] = $result;
                }
                
                $results['results'] = $movies;

                $stmt = null;
                
                return($results);

            } else {
                return $this->statusCode(ERROR);
            } 
        }

        public function searchMovies($searchText){
            $con = (new DatabaseConnector())->getConnection();

            if ($con) {
                $results = array();

                $cQuery = 'SELECT * FROM films.movie WHERE title LIKE "%' . $searchText . '%"';

                $stmt = $con->query($cQuery);      

                $results['_total'] = $stmt->rowCount();
                
                $movies = array();
                while($row = $stmt->fetch()) {
                    $result['id'] = $row['movie_id'];
                    $result['title'] = $row['title'];
                    $result['overview'] = $row['overview'];
                    $result['released'] = $row['release_date'];
                    $result['runtime'] = $row['runtime'];
                    $movies[] = $result;
                }
                
                $results['results'] = $movies;

                $stmt = null;
                
                return($results);

            } else {
                return $this->statusCode(ERROR);
            } 
        }

    }
