<?php

include("components/logincheck.php");


$images = [];
$bases = [];

if (isset($_POST['button'])) {
  $images = $_FILES["upload"]["name"];
  for ($i = 0; $i < count($images); $i++) {
    $images[$i] = pathinfo($images[$i], PATHINFO_FILENAME);
  }

  foreach ($_FILES["upload"]["tmp_name"] as $image) {
    array_push($bases, base64_encode(file_get_contents($image)));
  }
}

if (isset($_POST['delete'])) {
  $i = $_POST['delete'];
  $images = $_POST["barkodi"];
  $bases =  $_POST["base"];
  array_splice($bases, $i, 1);
  array_splice($images, $i, 1);
}


if (filter_has_var(INPUT_POST, 'submit')) {

  for ($i = 0; $i < count($_POST["base"]); $i++) {
    
    require('../db.php');

    $bar = $_POST["barkodi"][$i];

    $imgcheckquery = "SELECT barkodi FROM xcom WHERE barkodi ='" . $bar . "'";

    $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem me kyqjen ne databasë!");

    if (mysqli_num_rows($imgcheck) > 0) {

      $query = "INSERT INTO ads(barkodi,ad) VALUES('$bar','" . $_POST["base"][$i] . "')";

      if (mysqli_query($conn, $query)) {
        
        err("është regjistruar me sukses!",1,$bar);

        $llquery = "SELECT lloji FROM xcom WHERE barkodi ='" . $bar . "'";

        $llresult = mysqli_query($conn, $llquery);

        $llbars = mysqli_fetch_assoc($llresult);

        $query = "SELECT barkodi FROM xcom WHERE lloji='" . $llbars['lloji'] . "'";

        $result = mysqli_query($conn, $query);

        $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_free_result($result);

        $rateitems = [];
        foreach ($bars as $bar1) {

          $query = "SELECT * FROM ads WHERE barkodi = '" . $bar1['barkodi'] . "'";

          $result = mysqli_query($conn, $query);

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
            err("Diqka nuk është ne rregull",2,$bar);
          }
        }

      } else {
        err(", foto është shumë e madhe , provo përseri!",0,$bar);
      }
    } else {
      err("nuk egziston tek produktet!",0,$bar);
    }
    
    mysqli_free_result($imgcheck);
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

  <title>XCom - Reklamat</title>

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

  $active = "ads";

  include("components/topbar.php")

  ?>






  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Shto disa Reklama</h1>
    <p class="mb-4"></p>
    <div class="row">

      <div class="col-lg-6 mb-4">
        <form id="myForm" enctype="multipart/form-data" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>"">
            <input type="file" name="upload[]" multiple="multiple" />

        </form>
      </div>

      <div class="col-lg-6 mb-4">

        <button form="myForm" name="button" value="button" type="submit" style="width:100%" class="text-white btn btn-info">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Shto</span>
          </div>
        </button>
      </div>
    </div>

    <div id="pop" style="display: block">

      <form id="Form" enctype="multipart/form-data" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>"">
      
    <?php for ($i = 0; $i < count($bases); $i++) : ?>
      <div class=" mb-4">
        <div class="card shadow mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table width="100%" cellspacing="0">
                <tr>
                  <td>
                    <div style="margin: 8px 0px">
                      <label for="ndrysho-input" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Barkodi</label>
                      <input type="text" value="<?php echo $images[$i]; ?>" style="display:block;width:600px;" name="barkodi[]" class="form-control bg-light border-1 small" placeholder="Barkodi" aria-label="Search" aria-describedby="basic-addon2">
                    </div>
                  </td>
                  <td style="float: right;margin-right:50px;">
                    <input type="text" style="display: none" name="base[]" value="<?php echo $bases[$i]; ?>">
                    <img height="150px" src="data:image/gif;base64,<?php echo $bases[$i]; ?>" />
                  </td>
                  <td style="position:absolute; top: 20px; right:20px;">

                    <button name="delete" form="Form" type="submit" value="<?php echo $i ?>" class="btn btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
                    </button>

                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
    </div>
  <?php endfor; ?>
  </form>

  <?php if (count($bases) > 0) : ?>
    <button form="Form" name="submit" value="submit" type="submit" style="width:100%; margin-bottom:50px" class="text-white btn btn-primary">
      <div class="card-body">
        <b>Ngarko ne Databas</b>
      </div>
    </button>
  <?php endif; ?>
  </div>


  </div>


  <!-- /.container-fluid -->


  <!-- End of Main Content -->

  <!-- Footer -->



  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
    <?php include("components/errorcom.php");?>

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