<?php

    // include database and users files
    include_once '../config/database.php';
    include_once '../objects/advice.php';
    include_once '../objects/crop.php';
    include_once '../objects/farmer.php';
 
    // instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize users
    $advice = new advice($db);
    $crop = new crop($db);
    $farmer = new farmer($db);
    
    $now = date("Y-m-d");
    
    if(!empty($_GET['liters'])) {
        
        $stmt2 = $crop->readRootsNumber($_GET['cropId']);
        $num2 = $stmt2->rowCount();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        extract($row2);
        
        $stmt3 = $crop->returnFarmerId($_GET['cropId']);
        $num3 = $stmt3->rowCount();
        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        extract($row3);
        
        $stmt4 = $farmer->readFarmer($farmer_id);
        $num4 = $stmt4->rowCount();
        $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        extract($row4);
        
        $advice->crop_id = $_GET['cropId'];
        $advice->liters = $_GET['liters']*$roots_number;
        $advice->advice_date = $now;
        $stmt = $advice->write($advice);
        $num = $stmt->rowCount();

        if ($advice->liters>0.0) {
        
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            $from = "******@******.***"; /*Your email. Must be in the same server so you dont need to establise connection.*/
            $to = $farmer_email;
            $subject = "Συμβουλή ποτίσματος";
            $link = '******path******/irrigation_system/frontend/adviceVote.php?crop_id='.$_GET['cropId'];
            $title = 'Επικύρωση αποτελέσματος.';
            $message = "Το πρόγραμμα προτείνει " .$_GET['liters']*$roots_number ." λίτρα ποτίσματος για το σύνολο της καλλιέργειας " .$crop_kind ." στην περιοχή " .$crop_place ."<br><br>Παρακαλώ ακολουθήστε τον παρακάτω σύνδεσμο για την επικύρωση ή μη της πρόβλεψης.<br><a href='". $link. "'>" .$title. "</a>";
            $headers = "From: $from \r\n". "MIME-Version: 1.0" . "\r\n" . "Content-type: text/html; charset=UTF-8" . "\r\n";
            if(mail($to,$subject,$message, $headers)) {
                echo "The email message was sent.";
            } else {
                echo "The email message was not sent.";
            }
        }
    }
?>
