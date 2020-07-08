<?php

include("components/logincheck.php");

require('../db.php');

if (isset($_POST['submit'])) {

  $kategoria = $_POST['kategoria'];

  $query = "INSERT INTO kategoria(kategoria) VALUES('$kategoria')";

  if (mysqli_query($conn, $query)) {
    err("Kategoria u shtua me sukses!",1);
  } else {
    err("Kategoria nuk u shtua, rishiko te dhenat.");
  }
}

if (isset($_POST['ndrysho'])) {

  $ndkategoria = $_POST['ndkategoria'];

  $query = "UPDATE kategoria SET kategoria = '" . $ndkategoria . "' WHERE kategoria = '" . $_POST["ndrysho"] . "'";

  if (mysqli_query($conn, $query)) {
    $uquery = "UPDATE lloji SET kategoria = '" . $ndkategoria . "' WHERE kategoria = '" . $_POST["ndrysho"] . "'";

    if (mysqli_query($conn, $uquery)) {
      err("Kategoria u ndryshua me sukses!",1);
    } else {
      err("Ka problem me ndryshimin e kategorise, provo përseri!");
    }
  } else {
    err("Ka problem me ndryshimin e kategorise, provo përseri!");
  }
}

if (isset($_POST['delete'])) {

  $delquery = "DELETE FROM kategoria WHERE kategoria ='" . $_POST['delete'] . "'";
  if (mysqli_query($conn, $delquery)) {
    err("Kategoria është shlyer me sukses!",1);
  } else {
    err("Kategoria nuk është fshirë, provo përseri!");
  }
}




$query = 'SELECT * FROM kategoria';

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);





$charts_days = "-7 days";

$start_date = "yesterday";

$charts_name = "Java e fundit";

$datetype = "d-M-Y";

$dquery = "SELECT MIN(date) FROM click";

$dresult = mysqli_query($conn, $dquery);
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

$numgray = 100;

if (isset($_POST["select-kat"])) {

  $data = explode("#",$_POST["select-kat"]);
  
  
  $numgray = $data[0];

  $charts_days = $data[1];

  $start_date = $data[2];

  $charts_name = $data[3];

  $datetype = $data[4];
}


$d = strtotime($start_date);
$previous_week = strtotime($charts_days);
$start = date("Y-m-d", $previous_week);
$end = date("Y-m-d", $d);

$kategoritarray = array();

$kategoritarray_value = array();
$kategoritarray_key = array();



foreach ($items as $item) {
  $weekarray = array();

  $llquery = "SELECT * FROM lloji WHERE kategoria = '" . $item['kategoria'] . "'";
  $llresult = mysqli_query($conn, $llquery);
  $llitems = mysqli_fetch_all($llresult, MYSQLI_ASSOC);
  mysqli_free_result($llresult);

  $date = $start;
  while (strtotime($date) <= strtotime($end)) {

    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
    //$day = date("D",$date);

    $weekarray[$date] = 0;

    foreach ($llitems as $llitem) {
      $pquery = "SELECT * FROM xcom WHERE lloji = '" . $llitem['lloji'] . "'";
      $presult = mysqli_query($conn, $pquery);
      $pitems = mysqli_fetch_all($presult, MYSQLI_ASSOC);
      mysqli_free_result($presult);
      foreach ($pitems as $pitem) {
        $bquery = "SELECT barkodi, clicks, date FROM click WHERE date = '" . $date . "' AND barkodi = '" . $pitem['barkodi'] . "'";
        $bresult = mysqli_query($conn, $bquery);
        $bitems = mysqli_fetch_all($bresult, MYSQLI_ASSOC);
        foreach ($bitems as $bitem) {
          $weekarray[$date] += $bitem['clicks'] * $pitem['qmimi'];
        }
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
  $kategoritarray_value[$item['kategoria']] =  $area_week_value;
}

//print_r($kategoritarray);


$piecolors = ['78, 115, 223', '54, 185, 204', '246, 194, 62', '231, 74, 59', '28, 200, 138', '100, 182, 172', '214, 104, 83', '219, 185, 87'];

$gray = "80, 80, 80";

?>



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>XCom - Kategoritë</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">


  <?php

  $active = "kat";

  include("components/topbar.php")

  ?>


  <!-- Begin Page Content -->

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
              <label for="ndrysho-input" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Kategoria</label>
              <input style="width: 100%;" id="ndrysho-input" type="text" name="ndkategoria" class="form-control bg-light border-0 small" placeholder="Kategoria">
            </div>

            <button style="width: 100%; margin-top:20px;" class="btn btn-primary btn-icon-split" id="ndrysho-edit-btn" type="submit" name="ndrysho" form="ndForm" value="ndrysho">
              <span class="text">Ndrysho</span>
            </button>

            <form>

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

          <p style="margin-top: 5px">Deshironi ta shlyeni kategorine:</p>
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







  <script src="vendor/chart.js/Chart.min.js"></script>




  <div class="container-fluid">





    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Kategoritë</h1>
      
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




    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Grafiku i të Ardhurave për Kategori</h6>

      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="text-center small">
          <form id="kat-col" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>"></form>


          <?php for ($i = 0; $i < count($kategoritarray_value); $i++) : ?>
            <button type="submit" value="<?php echo $i ?>#<?php echo $charts_days ?>#<?php echo $start_date ?>#<?php echo $charts_name ?>#<?php echo $datetype ?>" style="outline:none;border:0;padding:0;background-color:transparent" name="select-kat" id="select-kat" form="kat-col">
              <span class="mr-2 text-gray-800">
                <i class="fas fa-circle" style="color:rgb(<?php echo $piecolors[$i] ?>) !important;"></i> <?php echo array_keys($kategoritarray_value)[$i] ?>
              </span>
            </button>
          <?php endfor ?>
        </div>
        <div class="chart-area">
          <canvas id="myAreaChart"></canvas>
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
            labels: [<?php echo $area_week_key; ?>],
            datasets: [<?php $m = -1; ?>
              <?php foreach ($kategoritarray_value as $key => $value) : $m++; ?> {
                  label: "<?php echo $key; ?>",
                  lineTension: 0.3,
                  backgroundColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                            echo $piecolors[$m] . ", 0.05";
                                          } else {
                                            echo $gray . ", 0.02";
                                          } ?>)",
                  borderColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                        echo $piecolors[$m] . ", 0.9";
                                      } else {
                                        echo $gray . ", 0.05";
                                      } ?>)",
                  pointRadius: 0,
                  pointBackgroundColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                                echo $piecolors[$m] . ", 0.9";
                                              } else {
                                                echo $gray . ", 0.02";
                                              } ?>)",
                  pointBorderColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                            echo $piecolors[$m] . ", 0.9";
                                          } else {
                                            echo $gray . ", 0.02";
                                          } ?>)",
                  pointHoverRadius: 3,
                  pointHoverBackgroundColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                                      echo $piecolors[$m] . ", 0.9";
                                                    } else {
                                                      echo $gray . ", 0.02";
                                                    } ?>)",
                  pointHoverBorderColor: "rgba(<?php if ($numgray > 8 or $numgray == $m) {
                                                  echo $piecolors[$m] . ", 0.9";
                                                } else {
                                                  echo $gray . ", 0.02";
                                                } ?>)",
                  pointHitRadius: 10,
                  pointBorderWidth: 2,
                  data: [<?php echo $value; ?>],
                },
              <?php endforeach; ?>

            ],
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



    </div>







    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e kategorive</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Kategotia</th>
                <th>Lloji</th>
                <th>Tjera</th>

              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Kategoria</th>
                <th>Lloji</th>

                <th>Tjera</th>

              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td><?php echo $item['kategoria'] ?></td>
                  <td><a href="#">Llojet</a></td>

                  <td>

                    <a href="kategorit_info.php?kategoria=<?php echo $item['kategoria'] ?>" class="btn btn-sm btn-info btn-circle">
                      <i class="fas fa-info-circle"></i>
                    </a>


                    <button <?php echo "onclick=\"openDel('" . $item['kategoria'] . "')\"" ?> class="btn btn-sm btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
                    </button>

                    <button <?php echo "onclick=\"openForm('" . $item['kategoria'] . "')\"" ?> class="btn btn-sm btn-primary btn-circle">
                      <i class='fa fa-pen'></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>






              <script>
                function submitMyForm(form) {
                  document.getElementById(form).submitForm();
                }

                function openForm(value) {
                  document.getElementById("pop").style.display = "block";
                  document.getElementById("ndrysho-edit-btn").value = value;
                  document.getElementById("ndrysho-input").value = value;
                  document.getElementById("del-p").innerHTML = "New text!";
                }

                function closeForm() {
                  document.getElementById("pop").style.display = "none";
                }

                function openDel(value) {
                  document.getElementById("del-pop").style.display = "block";
                  document.getElementById("del-value").value = value;
                  document.getElementById("p1").innerHTML = value;
                  document.getElementById("p1").setAttribute("data-text", value);
                }

                function closeDel() {
                  document.getElementById("del-pop").style.display = "none";
                }
              </script>


            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Krijo kategori</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">

            <form id="form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
              <table class="table" width="100%" cellspacing="0" style="margin-top: 20px;">
                <tbody>
                  <tr>

                    <td>
                      <input type="text" name="kategoria" class="form-control bg-light border-0 small" placeholder="Kategoria" aria-label="Search" aria-describedby="basic-addon2">
                    </td>

                    <td>
                      <button class="btn btn-secondary btn-icon-split" name="submit" type="submit" id="submit" form="form">
                        <span class="icon text-white-50">
                          <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text">Krijo Kategori</span>
                      </button>
                    </td>

                  </tr>
                </tbody>
              </table>
              <form>

          </div>
        </div>
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
  <?php include("components/errorcom.php") ?>

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

</body>

</html>