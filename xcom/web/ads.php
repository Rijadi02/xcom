<?php

include("components/logincheck.php");

require('../db.php');



if (isset($_POST['delete'])) {

  $adcheckquery = "SELECT barkodi FROM ads WHERE `index` = " . $_POST['delete'];

  $adcheck = mysqli_query($conn, $adcheckquery) or err("Ka problem ne lidhjen me databaz.");

  $ad = mysqli_fetch_assoc($adcheck);

  $delquery = "DELETE FROM `ads` WHERE `index` = " . $_POST['delete'];
  if (mysqli_query($conn, $delquery)) {
    err("Reklama është shlyer me sukses!", 1);

    $imgcheckquery = "SELECT barkodi, lloji FROM xcom WHERE barkodi ='" . $ad['barkodi'] . "'";

    $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem ne lidhjen me databaz.", 2);

    $lloj = mysqli_fetch_assoc($imgcheck);



    $query = "SELECT barkodi FROM xcom WHERE lloji='" . $lloj['lloji'] . "'";

    $result = mysqli_query($conn, $query);

    $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    $rateitems = [];
    foreach ($bars as $bar) {

      $query = "SELECT * FROM ads WHERE barkodi = '" . $bar['barkodi'] . "'";

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
        err("Diqka nuk është ne rregull", 2);
      }
    }
  } else {
    err("Reklama nuk ka arritur te fshihet, provo përseri.");
  }
}

if (filter_has_var(INPUT_POST, 'submit')) {

  $bar = $_POST['bar'];

  $imgcheckquery = "SELECT barkodi, lloji FROM xcom WHERE barkodi ='" . $bar . "'";

  $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem ne lidhjen me databasë!");

  $lloj = mysqli_fetch_assoc($imgcheck);
  $maxsize = 2097152;

  if (mysqli_num_rows($imgcheck) > 0) {
    if (!empty($_FILES['image']['tmp_name']) && file_exists($_FILES['image']['tmp_name'])) {

      if ($_FILES['image']['size'] >= $maxsize) {

      $query = "INSERT INTO ads(barkodi,ad,percent) VALUES('$bar','" . base64_encode(file_get_contents($_FILES['image']['tmp_name'])) . "','0')";

      if (mysqli_query($conn, $query)) {
        err("Reklama u shtua me sukses!", 1);
      } else {
        err("Foto është shumë e madhe, provo përseri.");
      }

      $query = "SELECT barkodi FROM xcom WHERE lloji='" . $lloj['lloji'] . "'";

      $result = mysqli_query($conn, $query);

      $bars = mysqli_fetch_all($result, MYSQLI_ASSOC);

      mysqli_free_result($result);

      $rateitems = [];
      foreach ($bars as $bar) {

        $query = "SELECT * FROM ads WHERE barkodi = '" . $bar['barkodi'] . "'";

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
          err("Diqka nuk është ne rregull", 2);
        }
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

  $imgcheck = mysqli_query($conn, $imgcheckquery) or err("Ka problem ne lidhjen me databaz.");

  if (mysqli_num_rows($imgcheck) > 0) {

    if (!empty($_FILES['nd-ad']['tmp_name']) && file_exists($_FILES['nd-ad']['tmp_name'])) {
      $query = "UPDATE `ads` SET `barkodi` = '" . $_POST["nd-bar"] . "', `ad` = '" . base64_encode(file_get_contents($_FILES["nd-ad"]['tmp_name'])) . "' WHERE `index` = " . $_POST["nd-submit"];
    } else {
      $query = "UPDATE `ads` SET `barkodi` = '" . $_POST["nd-bar"] . "' WHERE `index` = " . $_POST["nd-submit"];
    }

    if (mysqli_query($conn, $query)) {
      err("Reklama u ndryshua me sukses!", 1);
    } else {
      err("Reklama nuk ka arritur te ndryshohet, provo përseri.");
    }
  }else {
    err("nuk gjendet tek produktet!", 0, $_POST["nd-bar"]);
  }
}


$query = 'SELECT * FROM ads';

$result = mysqli_query($conn, $query);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);



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
                  <h1 class="h4 text-gray-900 mb-5">Shto Reklamë</h1>
                </div>


                <form class="user mb-5" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="bar" value="<?php echo isset($_POST['bar']) ? $bar : ""; ?>" type="number" class="form-control text-center form-control-user" id="exampleFirstName" placeholder="Barkodi">
                    </div>
                  </div>

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
                  <h1 class="h4 text-gray-900 mb-5">Ndrysho të dhënat</h1>
                </div>

                <form class="user mb-5" id="ndForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="nd-bar" id="bar" type="number" class="form-control text-center form-control-user" id="exampleFirstName" placeholder="Barkodi">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="nd-ad" id="ad" type="file" class="form-control form-control-user hide-file" id="exampleFirstName">
                    </div>
                  </div>
                  <button form="ndForm" type="submit" id="index" name="nd-submit" value="Regjistro Produktin" class="btn btn-primary btn-user btn-block">
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

  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Reklamat</h1>
    <p class="mb-4"></p>
    <div class="row">
      <div class="col-lg-6 mb-4">
        <button onclick="openForm()" style="width:100%" class="text-white btn btn-primary">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Shto Reklamë</span></a>

          </div>
        </button>
      </div>

      <div class="col-lg-6 mb-4">
        <a href="ads_multi.php" style="width:100%" class="text-white btn btn-info">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i><i class="fas fa-fw fa-upload"></i><i class="fas fa-fw fa-upload"></i>
            <span>Shto Reklama</span>
          </div>
        </a>
      </div>
    </div>
    <!-- DataTales Example -->
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
                <th>Lloji</th>
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Barkodi</th>
                <th>Emri</th>
                <th>Lloji</th>
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

  </div>
  <!-- /.container-fluid -->

  </div>
  <!-- End of Main Content -->

  <!-- Footer -->
 
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