<?php

    // include database and users files
    include_once '../config/database.php';
    include_once '../objects/data_table.php';
    include_once '../objects/crop.php';
 
    // instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize users
    $data_table = new data_table($db);
    $crop = new crop($db);
    
    $month = date('m');
    $hour = date('h') + 3;
    
    if(!empty($_GET['crop_id'])) {
        
        $stmt2 = $crop->readRootsNumber($_GET['crop_id']);
        $num2 = $stmt2->rowCount();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        extract($row2);
        
        $data_table->crop_id = $_GET['crop_id'];
        $data_table->current_month = $month;
        $data_table->air_temp = $_GET['temperature'];
        $data_table->air_humidity = $_GET['humidity'];
        $data_table->soil_moisture = $_GET['soilMoisture'];
        $data_table->sun = $_GET['Photoresistor'];
        $data_table->liters = $_GET['liters']/$roots_number;
        $stmt = $data_table->write($data_table);
        
        $filename = '../../python/lastDataRowCSV.csv';
        $f = fopen($filename, 'w');
        if ($f === false) {
            die('Error opening the file ' . $filename);
        }
        $list = array ('crop_id', 'Current_month', 'temp', 'hum', 'soil', 'sun');
        $data = array ($data_table->crop_id, $data_table->current_month, $data_table->air_temp, $data_table->air_humidity, $data_table->soil_moisture, $data_table->sun);
        fputcsv($f, $list);
        fputcsv($f, $data);
        fclose($f);
        
        $startDate = $start_date;
        $now = date("Y-m-d");
        $diff = strtotime($now) - strtotime($startDate);
        $runningDays = abs(round($diff / 86400));
        
        if(($runningDays==11 || $runningDays==31 || $runningDays==61 || $runningDays==91 || $runningDays==121 || $runningDays==151 || $runningDays==181) && $hour<8) {
            
            $stmt3 = $data_table->readCropData($_GET['crop_id']);
            $num3 = $stmt3->rowCount();
            if($num3>0){
                $results[0] = array();
                $results[1] = array();
                $i = 0;
                $filename = '../../python/cropDataCSV.csv';
                $f2 = fopen($filename, 'w');
                if ($f2 === false) {
                    die('Error opening the file ' . $filename);
                }
                $list = array ('id', 'crop_id', 'Current_month', 'temp', 'hum', 'soil', 'sun', 'watering');
                fputcsv($f2, $list);
                while ($row2 = $stmt3->fetch(PDO::FETCH_ASSOC)){
             
                    // extract row
                    extract($row2);
                
                    $results[$i][0] = $id;
                    $results[$i][1] = $crop_id;
                    $results[$i][2] = $current_month;
                    $results[$i][3] = $air_temp;
                    $results[$i][4] = $air_humidity;
                    $results[$i][5] = $soil_moisture;
                    $results[$i][6] = $sun;
                    $results[$i][7] = $liters;
                    //echo "The data table for Crop id " .$results[$i][1] ." are: Current month is: " . $results[$i][2] ."- Air temp is: " .$results[$i][3] ."- Air humidity is: " .$results[$i][4] ."- Soil moisture is: " .$results[$i][5] ."- Sun is on: " .$results[$i][6] ."- Liters usage is: " .$results[$i][7];
                    //echo "<br>";
                    fputcsv($f2, $results[$i]);
                    $i++;
                }
                fclose($f2);
                $command = escapeshellcmd('python ../../python/create_model.py');
                $output = shell_exec($command);
            }
        }
        else if($runningDays>10) {
            $command = escapeshellcmd('python ../../python/run_model.py');
            $output = shell_exec($command);
        }
    }
?>
