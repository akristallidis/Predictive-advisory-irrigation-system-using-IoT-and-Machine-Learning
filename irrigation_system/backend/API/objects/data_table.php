<?php

    class data_table{
 
        // database connection and table name
        private $conn;
 
        // users propertiesac
        public $id;
        public $current_month;
		public $crop_id;
		public $air_temp;
		public $air_humidity;
		public $soil_moisture;
		public $sun;
		public $liters;
 
        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        function write($data_table){
		    
		    $query = "INSERT INTO  data_table (crop_id, current_month, air_temp, air_humidity, soil_moisture, sun, liters) VALUES
		    ('$data_table->crop_id', '$data_table->current_month','$data_table->air_temp','$data_table->air_humidity','$data_table->soil_moisture','$data_table->sun','$data_table->liters')";

		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
            
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;  
        }
        
        function readCropData($crop) {
            $query = "SELECT * FROM data_table WHERE crop_id=$crop";
	
		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
        
        function readLastDataRow() {
            $query = "SELECT * FROM data_table WHERE id=(SELECT max(id) FROM data_table);";
	
		    // prepare query statement
		    $stmt = $this->conn->prepare($query);
    
		    // execute query
		    $stmt->execute();
		    
		    return $stmt;
        }
    }
?>
