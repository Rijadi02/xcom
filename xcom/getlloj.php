<?php

require('db.php');

$kategoria = $_GET["kategoria"];

$query = "SELECT * FROM lloji WHERE kategoria = '". $kategoria ."'";

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

print_r(json_encode($items));

