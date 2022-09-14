<?php

    class success{
 
        // database connection and table name
        private $conn;
 
        // users propertiesac
        public $id;
		public $point;
		public $vote_date;
 
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        function write($success){
		    
		    $query = "INSERT INTO  success (crop_id, point, vote_date) VALUES
		    ('$success->crop_id', '$success->point', '$success->vote_date')";

		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
            
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;  
        }
    
    }
?>
