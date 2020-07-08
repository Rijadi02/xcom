<?php

    require('db.php');
    
    $query = 'SELECT * FROM xcom';

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
        echo "#";
    } 

?>
    