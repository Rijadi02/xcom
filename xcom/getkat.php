<?php

require('db.php');


// --------------- Other Items ----------------------


$query = "SELECT * FROM kategoria";

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($items as $item) {
    echo $item['kategoria'];
    echo "#";
}
