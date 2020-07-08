<?php

require('db.php');

$kategoria = $_GET["kategoria"];

$query = "SELECT * FROM lloji WHERE kategoria = '". $kategoria ."'";

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

foreach ($items as $item) {
    echo $item['lloji'];
    echo "#";
}
