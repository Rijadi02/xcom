<?php

include("components/logincheck.php");

require("../db.php");

///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////              Area Chart              ////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//----------------------------------   Week Data   ----------------------------------------------//


$lloji = $_GET['lloji'];

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


  $pquery = "SELECT qmimi, barkodi FROM xcom WHERE lloji = '" . $lloji . "'";
  $presult = mysqli_query($conn, $pquery) or err("Ka problem me kyqjen ne databasë!");
  $pitems = mysqli_fetch_all($presult, MYSQLI_ASSOC);

  foreach ($pitems as $pitem) {
    $query1 = "SELECT qmimi FROM xcom WHERE barkodi = '" . $pitem['barkodi'] . "'";
    $result1 = mysqli_query($conn, $query1) or err("Ka problem me kyqjen ne databasë!");
    $bar = mysqli_fetch_assoc($result1);

    $query = "SELECT barkodi, clicks, date FROM click WHERE date = '" . $date . "' AND barkodi='" . $pitem['barkodi'] . "'";
    $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($items as $item) {
      $qmimi = $item['clicks'] * $bar['qmimi'];
      $weekarray[$date] += $qmimi;
    }
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



///////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////              Pie Chart              /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////

//----------------------------------   Kategorit   ----------------------------------------------//









$pquery = "SELECT qmimi, emri, barkodi FROM xcom WHERE lloji = '" . $lloji . "'";
$presult = mysqli_query($conn, $pquery) or err("Ka problem me kyqjen ne databasë!");
$pitems = mysqli_fetch_all($presult, MYSQLI_ASSOC);
foreach ($pitems as $pitem) {

  $d = strtotime("yesterday");
  $previous_week = strtotime($charts_days);
  $start = date("Y-m-d", $previous_week);
  $end = date("Y-m-d", $d);

  $kategoritarray[$pitem['emri']] = 0;

  $date = $start;
  while (strtotime($date) <= strtotime($end)) {
    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
    $cquery = "SELECT clicks FROM click WHERE barkodi = '" . $pitem['barkodi'] . "' AND date ='" . $date . "'";
    $cresult = mysqli_query($conn, $cquery) or err("Ka problem me kyqjen ne databasë!");
    $citems = mysqli_fetch_all($cresult, MYSQLI_ASSOC);
    foreach ($citems as $citem) {
      $qmimi = $pitem['qmimi'];
      $kategoritarray[$pitem['emri']] += $qmimi * $citem['clicks'];
    }
  }
}



arsort($kategoritarray);

$kategoritarray1 = $kategoritarray;
$kategoritarray2 = array_reverse($kategoritarray);


array_slice($kategoritarray, 0, 5);

$kategorit_key = "";
foreach ($kategoritarray as $key => $value) {
  $kategorit_key .= '"' . $key . '",';
}
$kategorit_key = substr($kategorit_key, 0, -1);


$kategorit_value = "";
foreach ($kategoritarray as $key => $value) {
  $kategorit_value .= $value . ',';
}
$kategorit_value = substr($kategorit_value, 0, -1);




$lloji_col = ["bg-danger", "", "bg-warning", "bg-info", "bg-success"];

$piecolors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#64B6AC', '#D66853', '#DBB957'];
$piecolors_txt = "[";
foreach ($piecolors as $color) {
  $piecolors_txt .= "'" . $color . "',";
}
$piecolors_txt = substr($piecolors_txt, 0, -1) . "]";


if (isset($_POST['ndrysho'])) {

  $ndemri = $_POST['ndemri'];

  $ndlloji = $_POST['ndlloj'];

  $ndqmimi = $_POST['ndqmim'];

  $query = "UPDATE xcom SET emri = '" . $ndemri . "', lloji = '" . $ndlloji . "', qmimi = '" . $ndqmimi . "' WHERE barkodi = '" . $_POST["ndrysho"] . "'";

  if (mysqli_query($conn, $query)) {
    err("Produkti u ndryshua me sukses!", 1);
  } else {
    err("Produkti nuk është ndryshuar, provo përseri!");
  }
}

if (isset($_POST['delete'])) {

  $delquery = "DELETE FROM xcom WHERE barkodi ='" . $_POST['delete'] . "'";
  if (mysqli_query($conn, $delquery)) {
    err("Produkti u shlye me sukses!", 1);
  } else {
    err("Produkti nuk është fshirë, provo përseri!");
  }
}

if (isset($_POST['rating'])) {

  $num = explode('#', $_POST['rating'])[0] + 1;

  $index = explode('#', $_POST['rating'])[1];


  $query = "UPDATE `ads` SET `rate` = " . $num . " WHERE `index` = " . $index;

  if (mysqli_query($conn, $query)) {
    err("Reklama u ndryshua me sukses!",1);
  } else {
    err("Reklama nuk është ndryshuar, provo përseri!");
  }



  $query = "SELECT barkodi FROM xcom WHERE lloji='" . $_GET['lloji'] . "'";

  $result = mysqli_query($conn, $query);

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
      err("Ka nje problem me reklamen!",2);
    }
  }
}




/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


$mstart = date("Y-m-d", strtotime("-31 days"));
$mend = date("Y-m-d", strtotime("yesterday"));

$mshuma = 0;
$mdate = $mstart;
while (strtotime($mdate) <= strtotime($mend)) {

  $mdate = date("Y-m-d", strtotime("+1 day", strtotime($mdate)));
  //$day = date("D",$date);

  $mpquery = "SELECT qmimi, barkodi FROM xcom WHERE lloji = '" . $lloji . "'";
  $mpresult = mysqli_query($conn, $mpquery) or err("Ka problem me kyqjen ne databasë!");
  $mpitems = mysqli_fetch_all($mpresult, MYSQLI_ASSOC);

  foreach ($pitems as $pitem) {
    $query1 = "SELECT qmimi FROM xcom WHERE barkodi = '" . $pitem['barkodi'] . "'";
    $result1 = mysqli_query($conn, $query1) or err("Ka problem me kyqjen ne databasë!");
    $bar = mysqli_fetch_assoc($result1);

    $query = "SELECT barkodi, clicks, date FROM click WHERE date = '" . $mdate . "' AND barkodi='" . $pitem['barkodi'] . "'";
    $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($items as $item) {
      $qmimi = $item['clicks'] * $bar['qmimi'];
      $mshuma += $qmimi;
    }
  }
}



///////////////////////////////////////////////////////////////////////////////////


$wstart = date("Y-m-d", strtotime("-365 days"));
$wend = date("Y-m-d", strtotime("yesterday"));

$wshuma = 0;
$wdate = $wstart;
while (strtotime($wdate) <= strtotime($wend)) {

  $wdate = date("Y-m-d", strtotime("+1 day", strtotime($wdate)));
  //$day = date("D",$date);

  $wpquery = "SELECT qmimi, barkodi FROM xcom WHERE lloji = '" . $lloji . "'";
  $wpresult = mysqli_query($conn, $wpquery) or err("Ka problem me kyqjen ne databasë!");
  $wpitems = mysqli_fetch_all($wpresult, MYSQLI_ASSOC);

  foreach ($pitems as $pitem) {
    $query1 = "SELECT qmimi FROM xcom WHERE barkodi = '" . $pitem['barkodi'] . "'";
    $result1 = mysqli_query($conn, $query1) or err("Ka problem me kyqjen ne databasë!");
    $bar = mysqli_fetch_assoc($result1);

    $query = "SELECT barkodi, clicks, date FROM click WHERE date = '" . $wdate . "' AND barkodi='" . $pitem['barkodi'] . "'";
    $result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($items as $item) {
      $qmimi = $item['clicks'] * $bar['qmimi'];
      $wshuma += $qmimi;
    }
  }
}

$kategoria_percent = (int) ((reset($kategoritarray1) / array_sum(($kategoritarray1))) * 100) . "%";
$kategoria_name = key($kategoritarray1);

$kategoria_percent_r = (int) ((reset($kategoritarray2) / array_sum(($kategoritarray2))) * 100) . "%";
$kategoria_name_r = key($kategoritarray2);

?>



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>XCom - Lloji_<?php echo $lloji ?></title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <?php

  $active = "lloj";

  include("components/topbar.php")

  ?>

  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Begin Page Content -->
  <div class="container-fluid">

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

              <form>

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

    <div id="pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
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
                  <button onclick="closeForm()" name="close" class="btn btn-sm btn-danger btn-circle">
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
                <label for="ndqmimi" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Emri</label>
                <input type="text" id="ndemri" name="ndemri" class="form-control bg-light border-0 small" placeholder="Lloji" aria-label="Search" aria-describedby="basic-addon2">
              </div>

              <div style="margin: 8px 0px">

              <label for="ndlloj" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Lloji</label>

              <select name="ndlloj" class="browser-default custom-select">
                <option id="ndlloj" selected>helo</option>
                
                <?php

                $query = 'SELECT * FROM lloji';

                $result = mysqli_query($conn, $query);

                $kategorit = mysqli_fetch_all($result, MYSQLI_ASSOC);

                mysqli_free_result($result);

                ?>

                <?php foreach ($kategorit as $kategori) : ?>
                  <option value="<?php echo $kategori["lloji"] ?>"><?php echo $kategori["lloji"] ?></option>
                <?php endforeach; ?>

              </select>
            </div>

              <div style="margin: 8px 0px">
                <label for="ndqmimi" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Qmimi</label>
                <input type="text" id="ndqmim" name="ndqmim" class="form-control bg-light border-0 small" placeholder="Qmimi" aria-label="Search" aria-describedby="basic-addon2">
              </div>

              <button style="width: 100%; margin: 15px 0px" class="btn btn-primary btn-icon-split" id="ndrysho-edit-btn" type="submit" name="ndrysho" form="ndForm" value="ndrysho">
                <span class="text">Ndrysho</span>
              </button>



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

            <p style="margin-top: 5px">Deshironi ta shlyeni produktin:</p>
            <p id="p1" class="error mx-3" style="font-size:1.2rem" data-text="hello">hello</p>

            <form style="display: inline-block" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" id="delete_form">

              <button onclick="submitMyForm('delete_form')" style="width: 100%; margin-top:20px;" class="btn btn-danger btn-icon-split" id="del-value" name="delete" value="ndrysho">

                <span class="text">Delete</span>
              </button>
            </form>


          </div>
        </div>
      </div>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">
        <?php echo $_GET['lloji'] ?>
      </h1>
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

    <!-- Content Row -->
    <div class="row">

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Te ardhurat mujore</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">€<?php echo (int) $mshuma ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Te ardhurat vjetore</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">€<?php echo (int) $wshuma ?></div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><?php echo $kategoria_name; ?></div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $kategoria_percent; ?></div>
                  </div>
                  <div class="col">
                    <div class="progress progress-sm mr-2">
                      <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $kategoria_percent; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-tag fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"><?php echo $kategoria_name_r; ?></div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $kategoria_percent_r; ?></div>
                  </div>
                  <div class="col">
                    <div class="progress progress-sm mr-2">
                      <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $kategoria_percent_r; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-tag fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Content Row -->

    <div class="row">

      <!-- Area Chart -->
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Grafiku i të Ardhurave</h6>

          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="chart-area">
              <canvas id="myAreaChart"></canvas>
            </div>
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





      <!-- Pie Chart -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Statistikat për produkte</h6>

          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
              <canvas id="myPieChart"></canvas>
            </div>

            <div class="mt-4 text-center small">
              <?php for ($i = 0; $i < count($kategoritarray); $i++) : ?>
                <span class="mr-2">
                  <i class="fas fa-circle" style="color: <?php echo $piecolors[$i] ?>!important;"></i> <?php echo array_keys($kategoritarray)[$i] ?>
                </span>
              <?php endfor ?>
            </div>
          </div>
        </div>
      </div>


      <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: [<?php echo $kategorit_key ?>],
            datasets: [{
              data: [<?php echo $kategorit_value ?>],
              backgroundColor: <?php echo $piecolors_txt; ?>,
              hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
              hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
          },
          options: {
            maintainAspectRatio: false,
            tooltips: {
              backgroundColor: "rgb(255,255,255)",
              bodyFontColor: "#858796",
              borderColor: '#dddfeb',
              borderWidth: 1,
              xPadding: 15,
              yPadding: 15,
              displayColors: false,
              caretPadding: 10,
            },
            legend: {
              display: false
            },
            cutoutPercentage: 80,
          },
        });
      </script>



    </div>

    <?php

    $query = "SELECT * FROM xcom WHERE lloji='" . $_GET['lloji'] . "'";

    $result = mysqli_query($conn, $query);

    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    ?>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e produkteve</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Emri</th>
                <th>Barkodi</th>
                <th>Qmimi</th>
                <th>Vlersimi</th>
                <th>Numri i vlersimeve</th>
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Emri</th>
                <th>Barkodi</th>
                <th>Qmimi</th>
                <th>Vlersimi</th>
                <th>Numri i vlersimeve</th>
                <th>Tjera</th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td><?php echo $item['emri'] ?></td>
                  <td><?php echo $item['barkodi'] ?></td>
                  <td><?php echo $item['qmimi'] ?></td>
                  <td><?php echo $item['rating'] ?></td>
                  <td><?php echo $item['ratecount'] ?></td>
                  <td>

                    <a href="produktet_info.php?barkodi=<?php echo $item['barkodi'] ?>" class="btn btn-sm btn-info btn-circle">
                      <i class="fas fa-info-circle"></i>
                    </a>

                    <button onclick="openDel('<?php echo $item['barkodi'] ?>','<?php echo $item['emri'] ?>')" class="btn btn-sm btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
                    </button>

                    <button onclick="openForm('<?php echo $item['barkodi'] ?>','<?php echo $item['emri'] ?>','<?php echo $_GET['lloji'] ?>','<?php echo $item['qmimi'] ?>')" class="btn btn-sm btn-primary btn-circle">
                      <i class='fa fa-pen'></i>
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

            function openForm(barkodi,emri, lloji, qmimi) {
              document.getElementById("pop").style.display = "block";
              document.getElementById("ndrysho-edit-btn").value = barkodi;
              document.getElementById("ndemri").value = emri;
              document.getElementById("ndlloj").value = lloji;
              document.getElementById("ndlloj").innerHTML = lloji;
              document.getElementById("ndqmim").value = qmimi;

            }

            function closeForm() {
              document.getElementById("pop").style.display = "none";
            }

            function openDel(value, emri) {
              document.getElementById("del-pop").style.display = "block";
              document.getElementById("del-value").value = value;
              document.getElementById("p1").innerHTML = emri;
              document.getElementById("p1").setAttribute("data-text", emri);
            }

            function closeDel() {
              document.getElementById("del-pop").style.display = "none";
            }
          </script>


        </div>
      </div>
    </div>



    <?php

    $query = "SELECT barkodi FROM xcom WHERE lloji='" . $_GET['lloji'] . "'";

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
        array_push($items, $ad);
      }
    }

    ?>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e reklamave</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Barkodi</th>
                <th>Emri</th>
                <th>Vlersimi</th>
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Barkodi</th>
                <th>Emri</th>
                <th>Vlersimi</th>
                <th>Tjera</th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td>
                    <a href="produktet_info.php?barkodi=<?php echo $item['barkodi'] ?>">
                      <?php echo $item['barkodi'] ?>
                    </a>
                  </td>
                  <?php

                  $query1 = "SELECT lloji, emri FROM xcom WHERE barkodi='" . $item['barkodi'] . "'";
                  $result1 = mysqli_query($conn, $query1);
                  $lloj = mysqli_fetch_assoc($result1);


                  ?>
                  <td>
                    <?php echo $lloj['emri'] ?>
                  </td>

                  <td>
                    <form id="rate" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

                      <?php
                      for ($i = 0; $i < 5; $i++) {
                        if ($i < $item['rate']) {
                          echo  '<button value="' . $i . '#' . $item['index'] . '" name="rating" class="text-primary" style="outline:none;border:0;padding:0;background-color:transparent" form="rate"> <span class="fa fa-star"></span> </button>';
                        } else {
                          echo '<button value="' . $i . '#' . $item['index'] . '" name="rating" class="text-gray-400" style="outline:none;border:0;padding:0;background-color:transparent" form="rate"> <span class="fa fa-star"></span> </buttton>';
                        }
                      } ?>

                    </form>
                  </td>

                  <td>
                    <button onclick="openImg('<?php echo $item['ad'] ?>')" class="btn btn-primary btn-circle btn-sm">
                      <span>
                        <i class="fas fa-fw fa-image"></i>
                      </span>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>



          <script>
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



      <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white">
      <div class="container my-auto">
        <div class="copyright text-center my-auto">
          <span>Copyright &copy; XCom | TachyonDev | ICK | Unicef 2020</span>
        </div>
      </div>
    </footer>
    <!-- End of Footer -->

  </div>
  <!-- End of Content Wrapper -->

  </div>
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

  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script src="js/demo/datatables-demo.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->




</body>

</html>