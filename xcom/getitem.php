<?php

    require('db.php');

    $barkodi = $_GET['barkodi'];
    
    $query = 'SELECT * FROM xcom WHERE barkodi = '.$barkodi;

    $result = mysqli_query($conn, $query);

    $item = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    mysqli_close($conn);


    print_r(json_encode($item));
?>

