<?php
require('../db.php');

    $pass =  $_GET['pass'];
    $numri = $_GET['numri'];

    $idcheckquery = "SELECT id, numri, salt, hash FROM users WHERE numri ='". $numri ."'";

    $idcheck = mysqli_query($conn,$idcheckquery) or die("2: Numri check failed");
    if(mysqli_num_rows($idcheck) != 1)
    {
        echo "5: Numri doesnt exists or it exists twice";
        exit();
    }

    $existinginfo = mysqli_fetch_assoc($idcheck);
    $salt = $existinginfo["salt"];
    $hash = $existinginfo["hash"];
    

    //$salt = "\$5\$rounds=5000\$" . "steamedhams" . $emri . "\$";
    $loginhash = crypt($pass,$salt);
    if($hash != $loginhash)
    {
        echo "6: Incorrect password";
        exit();
    }

    echo "0#";
    echo $existinginfo['id'];

?>