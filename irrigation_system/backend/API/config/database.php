<?php
    class Database{
 
        // specify our database credentials
        private $host = "localhost";
        private $db_name = "******"; /*Database name*/
        private $username = "******"; /*username*/
        private $password = "******"; /*password*/
        public $conn;
 
        // get the database connection
        public function getConnection(){
 
            $this->conn = null;
 
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }
?>