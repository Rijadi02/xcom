<?php

error_reporting(0);

$user =  $_GET['ID'];

require('../db.php');

$query = "SELECT * FROM click WHERE ID = '" . $user . "'";

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

$lloji_array = [];

$shuma = 0;

foreach ($items as $item) {

  $pquery = "SELECT lloji FROM xcom WHERE barkodi = '" . $item["barkodi"] . "'";

  $presult = mysqli_query($conn, $pquery);

  $pitem = mysqli_fetch_assoc($presult);

  mysqli_free_result($presult);

  $lloji_array[$pitem["lloji"]] += $item['clicks'];
  $shuma += $item['clicks'];
}

function random_probability($probabilities)
{
  $rand = rand(0, array_sum($probabilities));
  do {
    $sum = array_sum($probabilities);
    if ($rand <= $sum && $rand >= $sum - end($probabilities)) {
      return key($probabilities);
    }
  } while (array_pop($probabilities));
}

for ($i = 0; $i < 3; $i++) {

  $x = random_probability($lloji_array);

  $query = "SELECT barkodi FROM xcom WHERE lloji='" . $x . "'";

  $result = mysqli_query($conn, $query);

  $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);

  $items = [];
  foreach ($bars as $bar) {

    $query = "SELECT * FROM ads WHERE barkodi = '" . $bar['barkodi'] . "'";

    $result = mysqli_query($conn, $query);

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    foreach ($ads as $ad) {
      $items[$ad['index']] = $ad['percent'];
    }
  }

  $j = random_probability($items);

  $query = "SELECT `barkodi`, `ad` FROM `ads` WHERE `index` = " . $j;

  $result = mysqli_query($conn, $query);

  $adimg = mysqli_fetch_assoc($result);

  mysqli_free_result($result);

  echo $adimg["barkodi"];
  echo "@";
  echo $adimg["ad"];
  echo "#";

  unset($lloji_array[$x]);

}
