<?php

    class farmer{
 
        // database connection and table name
        private $conn;
 
        // users propertiesac
        public $id;
		public $farmer_name;
		public $farmer_email;
 
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        function readFarmer($farmer){
 
		    $query = "SELECT * FROM farmer WHERE id=$farmer";
	
		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
    
    }
?>
