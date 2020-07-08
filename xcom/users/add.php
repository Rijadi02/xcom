<?php
require('../db.php');

$ID = $_GET['ID'];

$lloji = $_GET['barkodi'];

$date = date('Y-m-d',time());

$idcheckquery = "SELECT ID, barkodi, clicks, date FROM click WHERE ID ='". $ID ."' AND barkodi = '". $lloji ."' AND date = '" . $date ."'";
$idcheck = mysqli_query($conn,$idcheckquery) or "Problem with connecting with database!";
$existinginfo = mysqli_fetch_assoc($idcheck);
if(mysqli_num_rows($idcheck) > 0)
{
    $newscore = $existinginfo["clicks"] + 1;
    $updatequery = "UPDATE click SET clicks=".$newscore." WHERE ID = '".$ID."' AND barkodi = '". $lloji ."' AND date = '" . $date ."';";
    mysqli_query($conn,$updatequery) or die("7: Update Query Failed");
    echo "0";
}
else
{
    $query = "INSERT INTO click(ID,barkodi,clicks,date) VALUES('$ID','$lloji',1,'$date')";
    if(mysqli_query($conn, $query)){
        echo "0";
    }else{
        echo "ERROR: ". mysqli_error($conn);
    }
}
?>