<?php

    class advice{
 
        // database connection and table name
        private $conn;
 
        // users propertiesac
        public $id;
        public $crop_id;
        public $liters;
 
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        function write($advice){
		    
		    $query = "INSERT INTO advice (crop_id, liters, advice_date) VALUES
		    ('$advice->crop_id', '$advice->liters', '$advice->advice_date')";

		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
            
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;  
        }
        
        function readAdvice() {
            $query = "SELECT * FROM advice WHERE id=(SELECT max(id) FROM advice);";
	
		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
        
    }
?>
