<?php
require('db.php');

$ID = $_GET['ID'];

$barkodi = $_GET['barkodi'];
$rate = $_GET['rate'];

$idcheckquery = "SELECT ID, barkodi, rate FROM rating WHERE ID ='". $ID ."' AND barkodi = '". $barkodi ."'";
$idcheck = mysqli_query($conn,$idcheckquery) or "1.1 Failed to check id and product";
$existinginfo = mysqli_fetch_assoc($idcheck);
if(mysqli_num_rows($idcheck) < 1)
{
    $updatequery = "SELECT barkodi, rating, ratecount FROM xcom WHERE barkodi = '". $barkodi ."'";
    $updatecheck = mysqli_query($conn,$updatequery) or "1.1 Failed to check barkofi";
    $rateinfo = mysqli_fetch_assoc($updatecheck);
    $nextcount = $rateinfo['ratecount'] + 1;
    $newrating = (($rateinfo['rating'] * $rateinfo['ratecount']) + $rate) / $nextcount;
    $update = "UPDATE xcom SET rating=".$newrating.", ratecount = ".$nextcount." WHERE barkodi = '".$barkodi."';";

    $query = "INSERT INTO rating(ID,barkodi,rate) VALUES('$ID','$barkodi',$rate)";
    if(mysqli_query($conn, $query)){
        if(mysqli_query($conn, $update)){
            echo "0";
        }else{
            echo "ERROR: ". mysqli_error($conn);
        }
    }else{
        echo "ERROR: ". mysqli_error($conn);
    }
}
else
{
    $updatequery = "SELECT barkodi, rating, ratecount FROM xcom WHERE barkodi = '". $barkodi ."'";
    $updatecheck = mysqli_query($conn,$updatequery) or "1.1 Failed to check barkodi";
    $rateinfo = mysqli_fetch_assoc($updatecheck);
    $newrating = $rateinfo['rating'] + (($rate - $existinginfo["rate"]) / $rateinfo['ratecount']);
    $update = "UPDATE xcom SET rating=".$newrating." WHERE barkodi = '".$barkodi."';";
    

    $query = "UPDATE rating SET rate = '".$rate."' WHERE ID ='". $ID ."' AND barkodi = '". $barkodi ."';";
    if(mysqli_query($conn, $query)){
        if(mysqli_query($conn, $update)){
            echo "1";
        }else{
            echo "ERROR: ". mysqli_error($conn);
        }
    }else{
        echo "ERROR: ". mysqli_error($conn);
    }
}



?>