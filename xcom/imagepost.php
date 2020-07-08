<?php

    require('db.php');

    $barkodi = $_GET['barkodi'];
    
    $query = "SELECT barkodi, img FROM image WHERE barkodi='".$barkodi."'";

    $result = mysqli_query($conn, $query);

    $item = mysqli_fetch_assoc($result);

    echo $item['barkodi'];
    echo"#";
    echo $item['img'];
   
?>