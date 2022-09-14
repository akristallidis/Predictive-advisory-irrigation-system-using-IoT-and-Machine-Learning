<?php

    // include database and users files
    include_once '../config/database.php';
    include_once '../objects/success.php';
 
    // instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // initialize users
    $success = new success($db);
    
    $now = date("Y-m-d");
    
    if(!empty($_POST['btn']) && !empty($_GET['crop_id'])) {
        
        if ($_POST['btn']=='ΝΑΙ') {
            $point=1;
        }
        else {
            $point=0;
        }
        
        $success->point = $point;
        $success->crop_id = $_GET['crop_id'];
        $success->vote_date = $now;
        $stmt = $success->write($success);
    }
?>