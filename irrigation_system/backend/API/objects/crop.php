<?php

    class crop{
 
        // database connection and table name
        private $conn;
 
        // users propertiesac
        public $id;
		public $farmer_id;
		public $crop_kind;
		public $crop_place;
		public $roots_number;
 
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        function readRootsNumber($crop) {
            $query = "SELECT * FROM crop WHERE farmer_id=$crop";
	
		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
        
        function returnFarmerId($crop) {
            $query = "SELECT * FROM crop WHERE farmer_id=$crop LIMIT 1";
            
            // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
    
    }
?>
