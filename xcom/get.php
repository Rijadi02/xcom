<?php

    // Get

    $lloji = $_GET['lloji'];

    $barkodi = $_GET['barkodi'];

    $ID = $_GET['ID'];
    
    $splitchar = "^";
    
    // Database

    require('db.php');
    

    // --------------- Other Items ----------------------
    
    
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



    echo $splitchar; 

    // --------------- Rating ----------------------
    

    $ratecheck = "SELECT ID, barkodi, rate FROM rating WHERE ID ='". $ID ."' AND barkodi = '". $barkodi ."'";
    $rating = mysqli_query($conn,$ratecheck) or "1.1 Failed to check id and product";
    

    if(mysqli_num_rows($rating) < 1)
    {
        echo "0";
    }
    else
    {
        $existinginfo = mysqli_fetch_assoc($rating);
        echo $existinginfo["rate"];
    }



    
    echo $splitchar;
    // --------------- flags ----------------------


    
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
            echo $item['flag'];

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
            echo $item['flag'];

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
            echo $item['flag'];
            
            break;
        }
    }



?>