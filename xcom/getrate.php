<?php
require('db.php');

$ID = $_GET['ID'];

$barkodi = $_GET['barkodi'];

$idcheckquery = "SELECT ID, barkodi, rate FROM rating WHERE ID ='". $ID ."' AND barkodi = '". $barkodi ."'";
$idcheck = mysqli_query($conn,$idcheckquery) or "1.1 Failed to check id and product";
$existinginfo = mysqli_fetch_assoc($idcheck);

if(mysqli_num_rows($idcheck) < 1)
{
    echo "0";
}
else
{
    echo $existinginfo["rate"];
} 
?>