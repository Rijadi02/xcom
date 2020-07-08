<?php

    require('db.php');

    $barkodi = $_GET['kodi'];
    
    $query = 'SELECT * FROM xcom WHERE barkodi = '.$barkodi;

    $result = mysqli_query($conn, $query);

    $item = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    mysqli_close($conn);


    echo $item['barkodi'].'#';
    echo $item['emri'].'#';
    echo $item['lloji'].'#';
    echo $item['vendi'].'#';
    echo $item['data'].'#';
    echo number_format((float)$item['qmimi'], 2, '.', '')."#";
    echo $item['rating'].'#';
    echo $item['ratecount'];
?>

