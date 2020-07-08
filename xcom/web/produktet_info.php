<?php

include("components/logincheck.php");

require("../db.php");

///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////              Area Chart              ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//----------------------------------   Week Data   ----------------------------------------------//


$barkodi = $_GET['barkodi'];




$charts_days = "-7 days";

$start_date = "yesterday";

$charts_name = "Java e fundit";

$datetype = "d-M-Y";

$dquery = "SELECT MIN(date) FROM click";

$dresult = mysqli_query($conn, $dquery) or err("Ka problem me kyqjen ne databasë!");
$ditems = mysqli_fetch_assoc($dresult);

$first_date = $ditems['MIN(date)'] . " -1 days";




if (isset($_POST['charts-java'])) {
  $charts_name = $_POST['charts-java'];
  $charts_days = "-7 days";
}

if (isset($_POST['charts-muji'])) {
  $charts_name =  $_POST['charts-muji'];
  $charts_days = "-31 days";
}

if (isset($_POST['charts-viti'])) {
  $charts_name = $_POST['charts-viti'];
  $charts_days = "-360 days";
}

if (isset($_POST['charts-full'])) {
  $charts_name = $_POST['charts-full'];
  $charts_days = $first_date;
}

if (isset($_POST['zgjidh'])) {
  $charts_days = $_POST['start-date'] . " -1 day";
  $start_date = $_POST['end-date'] . " -1 day";
  $charts_name = $_POST['zgjidh'];
}


$d = strtotime($start_date);
$previous_week = strtotime($charts_days);
$start = date("Y-m-d", $previous_week);
$end = date("Y-m-d", $d);

$weekarray = array();
$date = $start;
while (strtotime($date) <= strtotime($end)) {

  $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
  //$day = date("D",$date);

  $weekarray[$date] = 0;

  $query1 = "SELECT qmimi FROM xcom WHERE barkodi = '" . $barkodi . "'";
  $result1 = mysqli_query($conn, $query1) or err("Ka problem me kyqjen ne databasë!");
  $bar = mysqli_fetch_assoc($result1);

  $query = "SELECT barkodi, clicks, date FROM click WHERE date = '" . $date . "' AND barkodi='" . $barkodi . "'";
  $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");
  $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

  foreach ($items as $item) {
    $qmimi = $item['clicks'] * $bar['qmimi'];
    $weekarray[$date] += $qmimi;
  }
}


if ($charts_days == "-360 days") {
  $montharray = array_chunk(array_reverse($weekarray), 30, true);
  $montharray = array_reverse($montharray);
  $weekarray = [];
  foreach ($montharray as $month) {
    $shuma = 0;
    foreach ($month as $day) {
      $shuma += $day;
    }

    $weekarray[array_keys($month)[(int) count($month) / 2]] = $shuma;
    $datetype = "M Y";
  }
}


$area_week_key = "";

foreach ($weekarray as $key => $value) {
  $area_week_key .= '"' . date($datetype, strtotime($key)) . '",';
}
$area_week_key = substr($area_week_key, 0, -1);


$area_week_value = "";
foreach ($weekarray as $key => $value) {
  $area_week_value .= $value . ', ';
}
$area_week_value = substr($area_week_value, 0, -2);

$dataquery = "SELECT * FROM xcom WHERE barkodi ='" . $barkodi . "'";

$datares = mysqli_query($conn, $dataquery) or err("Ka problem me kyqjen ne databasë!");

$data = mysqli_fetch_assoc($datares);

mysqli_free_result($datares);



///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////              Pie Chart              /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//----------------------------------   Kategorit   ----------------------------------------------//



if (isset($_POST['delete'])) {

  $adcheckquery = "SELECT barkodi FROM ads WHERE `index` = " . $_POST['delete'];

  $adcheck = mysqli_query($conn, $adcheckquery) or err("Ka problem me kyqjen ne databasë!");

  $ad = mysqli_fetch_assoc($adcheck);

  $delquery = "DELETE FROM `ads` WHERE `index` = " . $_POST['delete'];
  if (mysqli_query($conn, $delquery)) {
    err("Reklama u shlye me sukses!", 1);
  } else {
    err("Reklama nuk është fshirë, provo përseri!");
  }


  $imgcheckquery = "SELECT barkodi, lloji FROM xcom WHERE barkodi ='" . $barkodi . "'";

  $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem me kyqjen ne databasë!");

  $lloj = mysqli_fetch_assoc($imgcheck);



  $query = "SELECT barkodi FROM xcom WHERE lloji='" . $lloj['lloji'] . "'";

  $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");

  $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);

  $rateitems = [];
  foreach ($bars as $bar) {

    $query = "SELECT * FROM ads WHERE barkodi = '" . $bar['barkodi'] . "'";

    $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    foreach ($ads as $ad) {
      array_push($rateitems, $ad);
    }
  }

  $allRate = 0;
  foreach ($rateitems as $item) {
    $allRate += $item['rate'];
  }

  foreach ($rateitems as $item) {
    $query = "UPDATE `ads` SET `percent` = " . $item['rate'] * (1 / $allRate) . " WHERE `index` = " . $item['index'];

    if (mysqli_query($conn, $query)) {
    } else {
      err("Ka nje problem me reklamen!", 2);
    }
  }
}

if (filter_has_var(INPUT_POST, 'submit')) {

  $imgcheckquery = "SELECT barkodi, lloji FROM xcom WHERE barkodi ='" . $barkodi . "'";

  $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem me kyqjen ne databasë!");

  $lloj = mysqli_fetch_assoc($imgcheck);

  if (mysqli_num_rows($imgcheck) > 0) {
    if (!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {

      $query = "INSERT INTO ads(barkodi,ad,percent) VALUES('$barkodi','" . base64_encode(file_get_contents($_FILES['image']['tmp_name'])) . "','0')";

      if (mysqli_query($conn, $query)) {
        err("Reklama u shtua me sukses!", 1);
      } else {
        err("Reklama nuk ka arritur te shtohet, provo përseri.");
      }

      $query = "SELECT barkodi FROM xcom WHERE lloji='" . $lloj['lloji'] . "'";

      $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");

      $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

      mysqli_free_result($result);

      $rateitems = [];
      foreach ($bars as $bar) {

        $query = "SELECT * FROM ads WHERE barkodi = '" . $bar['barkodi'] . "'";

        $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");

        $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_free_result($result);

        foreach ($ads as $ad) {
          array_push($rateitems, $ad);
        }
      }

      $allRate = 0;
      foreach ($rateitems as $item) {
        $allRate += $item['rate'];
      }

      foreach ($rateitems as $item) {
        $query = "UPDATE `ads` SET `percent` = " . $item['rate'] * (1 / $allRate) . " WHERE `index` = " . $item['index'];

        if (mysqli_query($conn, $query)) {
        } else {
          err("Diqka nuk është ne rregull", 2);
        }
      }
    } else {
      err("Nuk keni ngarkuar foto!");
    }
  } else {
    err("nuk gjendet tek produktet!", 0, $bar);
  }
}


if (filter_has_var(INPUT_POST, 'nd-submit')) {

  $imgcheckquery = "SELECT barkodi FROM xcom WHERE barkodi ='" . $_POST["nd-bar"] . "'";

  $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem me kyqjen ne databasë!");

  if (mysqli_num_rows($imgcheck) > 0) {

    if (!empty($_FILES['nd-ad']['tmp_name']) && file_exists($_FILES['nd-ad']['tmp_name'])) {
      $query = "UPDATE `ads` SET `barkodi` = '" . $_POST["nd-bar"] . "', `ad` = '" . base64_encode(file_get_contents($_FILES["nd-ad"]['tmp_name'])) . "' WHERE `index` = " . $_POST["nd-submit"];
    } else {
      $query = "UPDATE `ads` SET `barkodi` = '" . $_POST["nd-bar"] . "' WHERE `index` = " . $_POST["nd-submit"];
    }

    if (mysqli_query($conn, $query)) {
      err("Reklama u ndryshua me sukses!", 1);
    } else {
      err("Reklama nuk është ndryshuar, provo përseri!");
    }
  }else {
    err("nuk gjendet tek produktet!", 0, $_POST["nd-bar"]);
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>XCom - Produkti_<?php echo $data['emri'];?></title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


  <!-- Custom styles for this template-->
  <link href="../../css/sb-admin-2.css" rel="stylesheet">
  <link href="../../css/style.css" rel="stylesheet">
  <style>
    .p-5-2 {
      padding: 50px 20px !important;
    }
  </style>

</head>

<body id="page-top">

  <?php

  $active = "pro";

  include("components/topbar.php")

  ?>


  <div id="date-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <table style="width: 100%">
            <tr>
              <td style="width: 100%">
                <h6 class="m-0 font-weight-bold text-primary">Zgjidh</h6>
              </td>

              <td>
                <button onclick="closeDate()" name="close" class="btn btn-sm btn-danger btn-circle">
                  <i class='fa fa-times'></i>
                </button>
              </td>
            </tr>
          </table>


        </div>

        <!-- Card Body -->
        <div class="card-body">


          <form id="ndForm" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

            <div style="margin: 8px 0px">
              <label for="start-date" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Data e pare</label>
              <input value="<?php echo date("Y-m-d", strtotime('-7 days')); ?>" style="width: 100%;" id="start-date" type="date" name="start-date" class="form-control bg-light border-0 small" placeholder="Kategoria">
            </div>

            <div style="margin: 8px 0px">
              <label for="end-date" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Data e fundit</label>
              <input value="<?php echo date("Y-m-d") ?>" style="width: 100%;" id="end-date" type="date" name="end-date" class="form-control bg-light border-0 small">
            </div>

            <button style="width: 100%; margin-top:20px;" class="btn btn-primary btn-icon-split" id="Zgjidh" type="submit" name="zgjidh" form="ndForm" value="Zgjidh">
              <span class="text">Zgjidh</span>
            </button>

          </form>

        </div>
      </div>
    </div>
  </div>

  <div id="pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">

        <button onclick="closeForm()" class="btn btn-sm btn-danger btn-circle" style="float:right;margin:20px 0px 0px 20px;">
          <i class='fa fa-times'></i>
        </button>

        <div class="card-body">
          <!-- Nested Row within Card Body -->
          <div class=" justify-content-center">

            <div class="col-lg-12">
              <div class="p-5-2">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-5">Shto Produkt</h1>
                </div>


                <form class="user mb-5" method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?barkodi=" . $barkodi; ?>" enctype="multipart/form-data">

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="image" type="file" class="form-control form-control-user hide-file" id="exampleFirstName">
                    </div>
                  </div>

                  <input type="submit" name="submit" value="Regjistro Produktin" class="btn btn-primary btn-user btn-block">
                </form>


              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div id="nd-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">

        <button onclick="closeNd()" class="btn btn-sm btn-danger btn-circle" style="float:right;margin:20px 0px 0px 20px;">
          <i class='fa fa-times'></i>
        </button>

        <div class="card-body">
          <!-- Nested Row within Card Body -->
          <div class=" justify-content-center">

            <div class="col-lg-12">
              <div class="p-5-2">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-5">Ndrysho Reklamen</h1>
                </div>

                <form class="user mb-5" id="secForm" method="POST" action="<?php echo $_SERVER['PHP_SELF'] . "?barkodi=" . $barkodi; ?>" enctype="multipart/form-data">

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="nd-bar" id="bar" type="number" class="form-control text-center form-control-user" placeholder="Barkodi">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="nd-ad" id="ad" type="file" class="form-control form-control-user hide-file">
                    </div>
                  </div>
                  <button form="secForm" type="submit" id="index" name="nd-submit" value="Regjistro Produktin" class="btn btn-primary btn-user btn-block">
                    Ndrysho
                  </button>
                </form>


              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div id="del-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <table style="width: 100%">
            <tr>
              <td style="width: 100%">
                <h6 class="m-0 font-weight-bold text-primary">Ndrysho te dhenat</h6>
              </td>

              <td>
                <button onclick="closeDel()" class="btn btn-sm btn-danger btn-circle">
                  <i class='fa fa-times'></i>
                </button>
              </td>
            </tr>
          </table>


        </div>

        <!-- Card Body -->
        <div class="card-body">

          <p style="margin-top: 5px">Deshironi ta shlyeni reklamen:</p>
          <p id="p1" class="error mx-3" style="font-size:1.2rem" data-text="hello">hello</p>

          <form style="display: inline-block" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" id="delete_form">
            <input style="display: none" value="" name="p2" id="p2" />
            <button onclick="submitMyForm('delete_form')" style="width: 100%; margin-top:20px;" class="btn btn-danger btn-icon-split" id="del-value" name="delete" value="ndrysho">
              <span class="text">Delete</span>
            </button>
          </form>


        </div>
      </div>
    </div>
  </div>

  <div id="img-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">

        <button onclick="closeImg()" class="btn btn-sm btn-danger btn-circle" style="float:right;margin:20px 0px 0px 20px;">
          <i class='fa fa-times'></i>
        </button>

        <div class="card-body">
          <!-- Nested Row within Card Body -->
          <div class=" justify-content-center">

            <img width="100%" id="img-src" />
          </div>

        </div>
      </div>
    </div>
  </div>


  <div id="img-pop1" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">

        <button onclick="closeImg1()" class="btn btn-sm btn-danger btn-circle" style="float:right;margin:20px 0px 0px 20px;">
          <i class='fa fa-times'></i>
        </button>

        <div class="card-body">
          <!-- Nested Row within Card Body -->
          <div class=" justify-content-center">
            <img width="100%" id="img-src1" />
          </div>

        </div>
      </div>
    </div>
  </div>

  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <table width="100%">
        <tr>
          <td>
            <h1 class="h3 mb-0 text-gray-800"><?php echo $data['emri'] ?></h1>
            <h3 class="h5 mb-0 text-gray-600"><?php echo $barkodi ?></h3>
            <h3 class="h5 mb-0 text-gray-600"><?php echo $data['vendi'] ?></h3>
          </td>
          <td>

            <?php

            $query = "SELECT img FROM image WHERE barkodi='" . $barkodi . "'";

            $result1 = mysqli_query($conn, $query);

            $item11 = mysqli_fetch_assoc($result1);

            mysqli_free_result($result1);
            ?>

            <button onclick="openImg1('<?php echo $item11['img'] ?>')" class="btn btn-primary" style="border-radius:100%;height: 4.5rem;width: 4.5rem;">
              <span style="font-size: 2rem">
                <i class="fas fa-fw fa-image"></i>
              </span>
            </button>

            <script>
              function openImg1(value) {
                document.getElementById("img-pop").style.display = "block";
                document.getElementById("img-src").src = "data:image/gif;base64," + value;
              }

              function closeImg1() {
                document.getElementById("img-pop").style.display = "none";
              }
            </script>

          </td>
          <td>
          <td style="float: right">

            <?php for ($i = 1; $i <= 5; $i++) {
              if ($i < $data['rating']) {
                echo  '<span class="fa fa-star" style="font-size:1.5rem;color:orange"></span>';
              } elseif ($i - 0.5 <= $data['rating']) {
                echo  '<span class="fa fa-star-half" style="font-size:1.5rem;position:relative;color:orange;z-index:4"> <span class="fa fa-star" style="position:absolute;color:gray;top:0;left:0;z-index:-1"></span> </span>';
              } else {
                echo '<span class="fa fa-star" style="font-size:1.5rem;"></span>';
              }
            } ?>
            <span style="font-size:1.5rem;margin-left: 10px"><?php echo $data['rating'] ?></span>

            </br>

            <h1 class="h3 mb-0 mt-2 text-primary" style="font-size:2rem;float: right"><?php echo "€" . $data['qmimi'] ?></h1>
          </td>
          </td>
        </tr>
      </table>


    </div>

    <!-- Content Row -->
    
    <!-- Content Row -->



    <!-- Area Chart -->

    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Grafiku i të Ardhurave</h6>
        <div class="dropdown no-arrow">

          <p style="display:inline-block; margin:0"><?php echo $charts_name ?></p>

          <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v fa-sm fa-fw text-primary"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
            <form id="charts_select" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
              <div class="dropdown-header">Zgjidh periudhen kohore:</div>
              <input type="submit" name="charts-java" class="dropdown-item" value="Java e fundit" />
              <input type="submit" name="charts-muji" class="dropdown-item" value="Muaji i fundit" />
              <input type="submit" name="charts-viti" class="dropdown-item" value="Viti i fundit" />
              <div class="dropdown-divider"></div>
              <button name="charts-full" class="dropdown-item" value="E gjitha">E gjitha</button>
            </form>

            <button onclick="openDate()" name="charts-zgjidh" class="dropdown-item" value="Zgjidh">Zgjidh përiudhen kohore</button>

            <script>
              function openDate() {
                document.getElementById("date-pop").style.display = "block";
              }

              function closeDate() {
                document.getElementById("date-pop").style.display = "none";
              }
            </script>

          </div>
        </div>

      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="chart-area">
          <canvas id="myAreaChart"></canvas>
        </div>
      </div>
    </div>





    <script>
      // Set new default font family and font color to mimic Bootstrap's default styling
      Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
      Chart.defaults.global.defaultFontColor = '#858796';

      function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
          };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      }

      // Area Chart Example
      var ctx = document.getElementById("myAreaChart");
      var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [<?php echo $area_week_key ?>],
          datasets: [{
            label: "Earnings",
            lineTension: 0.1,
            backgroundColor: "rgba(78, 115, 223, 0.05)",
            borderColor: "rgba(78, 115, 223, 1)",
            pointRadius: 0,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [<?php echo $area_week_value ?>],
          }],
        },
        options: {
          maintainAspectRatio: false,
          layout: {
            padding: {
              left: 10,
              right: 25,
              top: 25,
              bottom: 0
            }
          },
          scales: {
            xAxes: [{
              time: {
                unit: 'date'
              },
              gridLines: {
                display: false,
                drawBorder: false
              },
              ticks: {
                maxTicksLimit: 7,

                maxRotation: 0,
                minRotation: 0
              }
            }],
            yAxes: [{
              ticks: {
                maxTicksLimit: 5,
                padding: 10,
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return value;
                }
              },
              gridLines: {
                color: "rgb(234, 236, 244)",
                zeroLineColor: "rgb(234, 236, 244)",
                drawBorder: false,
                borderDash: [2],
                zeroLineBorderDash: [2]
              }
            }],
          },
          legend: {
            display: false
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
              label: function(tooltipItem, chart) {
                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
              }
            }
          }
        }
      });
    </script>


    <div class="row">

      <?php

      $query1 = "SELECT * FROM lloji WHERE kategoria='" . $barkodi . "'";
      $result = mysqli_query($conn, $query1);
      $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

      mysqli_free_result($result);

      $query1 = "SELECT * FROM ads WHERE barkodi='" . $barkodi . "'";
      $result = mysqli_query($conn, $query1);
      $itemsad = mysqli_fetch_all($result, MYSQLI_ASSOC);

      mysqli_free_result($result);

      ?>
      <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reklamat</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Lloji</th>
                    <th>Tjera</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Lloji</th>
                    <th>Tjera</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php foreach ($itemsad as $item) : ?>
                    <tr>

                      <?php

                      $query1 = "SELECT lloji, emri FROM xcom WHERE barkodi='" . $item['barkodi'] . "'";
                      $result1 = mysqli_query($conn, $query1);
                      $lloj = mysqli_fetch_assoc($result1);


                      ?>


                      <td>

                        <a href="llojet_info.php?lloji=<?php echo $lloj['lloji'] ?>">
                          <?php echo $lloj['lloji'] ?>
                        </a>
                      </td>

                      <td>
                        <button onclick="openImg('<?php echo $item['ad'] ?>')" class="btn btn-primary btn-circle btn-sm">
                          <span>
                            <i class="fas fa-fw fa-image"></i>
                          </span>
                        </button>

                        <button onclick="openDel('<?php echo $item['index'] ?>','<?php echo $item['barkodi'] ?>')" class="btn btn-sm btn-danger btn-circle">
                          <i class="fas fa-trash"></i>
                        </button>

                        <button onclick="openNd('<?php echo $item['index'] ?>','<?php echo $item['barkodi'] ?>')" class="btn btn-info btn-circle btn-sm">
                          <span>
                            <i class='fa fa-pen'></i>
                          </span>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>



              <script>
                function submitMyForm(form) {
                  document.getElementById(form).submitForm();
                }

                function openForm() {
                  document.getElementById("pop").style.display = "block";
                }

                function closeForm() {
                  document.getElementById("pop").style.display = "none";
                }

                function openNd(index, bar, ad) {
                  document.getElementById("nd-pop").style.display = "block";
                  document.getElementById("index").value = index;
                  document.getElementById("bar").value = bar;
                }

                function closeNd() {
                  document.getElementById("nd-pop").style.display = "none";
                }

                function openDel(value, emri) {
                  document.getElementById("del-pop").style.display = "block";
                  document.getElementById("del-value").value = value;
                  document.getElementById("p1").innerHTML = emri;
                  document.getElementById("p1").setAttribute("data-text", emri);
                  document.getElementById("p2").value = emri;
                }

                function closeDel() {
                  document.getElementById("del-pop").style.display = "none";
                }

                function openImg(value) {
                  document.getElementById("img-pop").style.display = "block";
                  document.getElementById("img-src").src = "data:image/gif;base64," + value;
                }

                function closeImg() {
                  document.getElementById("img-pop").style.display = "none";
                }
              </script>


            </div>
          </div>
        </div>

        <button onclick="openForm()" style="width:100%" class="text-white btn btn-primary">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Ngarko Reklam</span></a>
          </div>
        </button>

      </div>

      <div class="col-lg-6 mb-4">

        <?php

        $query1 = "SELECT * FROM rating WHERE barkodi='" . $barkodi . "'";

        $result1 = mysqli_query($conn, $query1);

        $items1 = mysqli_fetch_all($result1, MYSQLI_ASSOC);

        mysqli_free_result($result1);

        function sortFunction($a, $b)
        {
          return strtotime($a["date"]) - strtotime($b["date"]);
        }
        usort($items1, "sortFunction");
        $items1 = array_slice($items1, 0, 5, true);
        ?>

        <!-- Illustrations -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Vlersimet E Fundit</h6>
          </div>
          <div class="card-body text-center">
          <p><a href="rate.php">Shiko te gjitha</a></p>
          </div>
        </div>

        <!-- Approach -->
        <?php foreach ($items1 as $item) : ?>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div>
                <table width="100%">
                  <tr>
                    <td>
                      <?php for ($i = 0; $i < 5; $i++) {
                        if ($i < $item['rate']) {
                          echo  '<span class="fa fa-star" style="color:orange"></span>';
                        } else {
                          echo '<span class="fa fa-star"></span>';
                        }
                      } ?>
                      <span style="margin-left: 10px"><?php echo $item['rate'] ?></span>
                    </td>


                    <td>
                      <span class="fa fa-calendar-day"></span>
                      <?php echo $item['date']; ?>
                    </td>
                    <td style="float: left">
                      <div style="margin-left:0">

                        <span class="fa fa-user"></span>
                        <?php echo $item['ID']; ?>
                      </div>
                    </td>
                  </tr>
                </table>

              </div>
              <p class="mb-0"> <?php echo $item['comment'] ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>


    </div>



  </div>













  <!-- End of Main Content -->

  <!-- Footer -->

  <!-- End of Footer -->


  <!-- End of Content Wrapper -->

  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->

  <?php include("components/errorcom.php"); ?>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

  <!-- Page level plugins -->




</body>

</html>