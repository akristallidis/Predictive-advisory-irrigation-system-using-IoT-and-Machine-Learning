<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1>
        <title>Predictive Advise Model</title>
        <link rel="shortcut icon" href="http://erg2.000webhostapp.com/assets/icons/shortcut.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3pro.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue.css">
        
        <style>
        
            .overlay {
                position: fixed;
                width: 100%;
                height: 60%;
                left: 0;
                top: 30%;
                z-index: 10;
            }
            .menu_design {
                position: fixed;
                width: 100%;
                height: 20%;
                left: 0;
                top:  0;
                font-size: 30px;
            }
            .footer_design {
                position: fixed;
                width: 100%;
                height: 20%;
                left: 0;
                bottom:  0;
                font-size: 30px;
            }
            .popupContent {
                font-size: 30px;
            }
            .popup_button {
                width: 100%;
            }
            
        </style>
        
    </head>
    
    <body>
    
        <div class="overlay">
        
	        <div class="menu_design w3-blue">
	        
                <h1><center>Predictive Advise Model</center></h1>
                    
            </div>
            
            <form align="center" action="******path******/irrigation/backend/API/success/writeFarmerVote.php?crop_id=<?php echo $_GET['crop_id'] ?>" method="post">
                    <H5> ΕΠΙΥΧΗΣ ΠΡΟΒΛΕΨΗ<H5><br>
                    <input type="submit" id="btn" name="btn" value="ΝΑΙ" class="" onclick="myFunction()"><br><br>
                    <input type="submit" id="btn" name="btn" value="ΟΧΙ" class="" onclick="myFunction()">
			</form>
			
			<footer class="footer_design w3-blue">
    	        <h5>Designed by Krystallidis Anastasios ©</h5>
            </footer>
        </div>
    
    <script>
        function myFunction() {
            alert("Ευχριστούμε!");
        }
    </script>
    
    </body>

</html>