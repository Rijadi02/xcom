<?php
    $lloji = $_GET['lloji'];

    require('db.php');
    
    $query = "SELECT * FROM xcom WHERE lloji = '".$lloji."'";

    $result = mysqli_query($conn, $query);

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach($items as $item) 
    {
        echo $item['barkodi'];
        echo "@"; 
        echo $item['emri'];
        echo "@";
        echo $item['lloji'];
        echo "@";
        echo $item['vendi'];
        echo "@";
        echo $item['data'];
        echo "@";
        echo $item['qmimi'];
        echo "@";
        echo $item['rating'];
        echo "@";
        echo $item['ratecount'];
        echo "#";
    } 

?>