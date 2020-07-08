<?php

    require('db.php');

    $barkodi = $_GET['barkodi'];
    
    $query = "SELECT prefix FROM flags";

    $result = mysqli_query($conn, $query);

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    foreach($items as $currentItem){

        if ($currentItem['prefix'] == substr($barkodi,0,1))
        {
            $query = "SELECT * FROM flags WHERE prefix = '".$currentItem['prefix']."'";

            $result1 = mysqli_query($conn, $query);

            $item = mysqli_fetch_assoc($result1);
            mysqli_free_result($result1);
            mysqli_close($conn);

            echo $item['prefix'].'#';
            echo $item['name'].'#';
            echo $item['prename'].'#';
            echo $item['flag'].'#';

            break;
        }
        elseif($currentItem['prefix'] == substr($barkodi,0,2))
        {
            $query = "SELECT * FROM flags WHERE prefix = '".$currentItem['prefix']."'";

            $result1 = mysqli_query($conn, $query);

            $item = mysqli_fetch_assoc($result1);
            mysqli_free_result($result1);
            mysqli_close($conn);

            echo $item['prefix'].'#';
            echo $item['name'].'#';
            echo $item['prename'].'#';
            echo $item['flag'].'#';

            break;
        }
        elseif($currentItem['prefix'] == substr($barkodi,0,3))
        {
            $query1 = "SELECT * FROM flags WHERE prefix = '".$currentItem['prefix']."'";

            $result1 = mysqli_query($conn, $query1);

            $item = mysqli_fetch_assoc($result1);
            mysqli_free_result($result1);
            mysqli_close($conn);

            echo $item['prefix'].'#';
            echo $item['name'].'#';
            echo $item['prename'].'#';
            echo $item['flag'].'#';
            
            break;
        }
    }
    //echo $item['prefix'].'#';
    //echo $item['name'].'#';
    //echo $item['prename'].'#';
    //echo $item['flag'].'#';
?>

