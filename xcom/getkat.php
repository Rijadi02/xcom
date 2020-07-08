<?php

require('db.php');


// --------------- Other Items ----------------------


$query = "SELECT * FROM kategoria";

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

print_r(json_encode($items));

