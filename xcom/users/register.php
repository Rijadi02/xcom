<?php
require('../db.php');

    $ID = $_GET['id'];
    $emri = $_GET['emri'];
    $pass =  $_GET['pass'];
    $numri = $_GET['numri'];
    $vendi = $_GET['vendi'];

    $idcheckquery = "SELECT ID FROM users WHERE ID ='". $ID ."'";

    $idcheck = mysqli_query($conn,$idcheckquery) or die("2: ID check failed");
    if(mysqli_num_rows($idcheck) > 0)
    {
        echo "3: ID exists";
        exit();
    }

    $nrcheckquery = "SELECT numri FROM users WHERE numri ='". $numri ."'";

    $nrcheck = mysqli_query($conn,$nrcheckquery) or die("2.1: Numri check failed");
    if(mysqli_num_rows($nrcheck) > 0)
    {
        echo "3.1: Numri exists";
        exit();
    }

    $salt = "\$5\$rounds=5000\$" . "steamedhams" . $emri . "\$";
    $hash = crypt($pass,$salt);

    $query = "INSERT INTO  users(ID,emri,hash,salt,numri,vendi) VALUES('$ID','$emri','$hash','$salt','$numri','$vendi')";
    

    if(mysqli_query($conn, $query)){
    }else{
        echo "ERROR: ". mysqli_error($conn);
    }

?>