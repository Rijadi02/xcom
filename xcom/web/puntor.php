<?php

include("components/logincheck.php");

require('../db.php');

if (isset($_POST['ndrysho'])) {

  $ndemri = $_POST['ndemri'];

  $ndpaga = $_POST['ndpaga'];

  $ndlogin = 0;

  if (isset($_POST['ndlogin'])) {
    $ndlogin = 1;
  }

  $query = "UPDATE puntor SET emri = '" . $ndemri . "', paga = " . $ndpaga . ", login = '" . $ndlogin . "' WHERE `index` = " . $_POST["ndrysho"];

  if (mysqli_query($conn, $query)) {
    err("Puntori u ndryshue me sukses!", 1);
  } else {
    err("Puntori nuk është ndryshuar, provo përseri!");
  }
}

if (isset($_POST['upload'])) {

  $upemri = $_POST['upemri'];

  $upmail = $_POST['upmail'];

  $uppass = $_POST['uppass'];

  $uppaga = $_POST['upqmim'];

  if ($_POST['uplog'] == "on") {
    $uplog = 1;
  } else {
    $uplog = 0;
  }

  $idcheckquery = "SELECT email FROM puntor WHERE email ='" . $upmail . "'";

  $idcheck = mysqli_query($conn, $idcheckquery) or die("2: ID check failed");
  if (mysqli_num_rows($idcheck) == 0) {
    if (filter_var($upmail, FILTER_VALIDATE_EMAIL)) {
      $salt = "\$5\$rounds=5000\$" . "steamedhams" . $upemri . "\$";
      $hash = crypt($uppass, $salt);

      $query = "INSERT INTO puntor(emri,email,hash,salt,paga,login) VALUES('$upemri','$upmail','$hash','$salt','$uppaga','$uplog')";


      if (mysqli_query($conn, $query)) {
        err("Puntori u regjistrua me sukses!", 1);
      } else {
        err("Puntori nuk është regjistruar, provo përseri!");
      }
    } else {
      err("Emaili nuk është valid!");
    }
  } else {
    err("Emaili egziston ne databs!");
  }
}

if (isset($_POST['delete'])) {

  $delquery = "DELETE FROM puntor WHERE `index` ='" . $_POST['delete'] . "'";
  if (mysqli_query($conn, $delquery)) {
    err("Puntori u shlye me sukses!", 1);
  } else {
    err("Puntori nuk është fshire, provo përseri!");
  }
}

$query = 'SELECT * FROM puntor';

$result = mysqli_query($conn, $query) or err("Ka problem me kyqjen ne databasë!");

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

  <title>XCom - Lista e punëtorve</title>

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

  $active = "pun";

  include("components/topbar.php")

  ?>


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
              <label for="ndemri" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Emri</label>
              <input type="text" id="ndemri" name="ndemri" class="form-control bg-light border-0 small" placeholder="Emri" aria-label="Search" aria-describedby="basic-addon2">
            </div>



            <div style="margin: 8px 0px">
              <label for="ndpaga" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Paga</label>
              <input type="text" id="ndpaga" name="ndpaga" class="form-control bg-light border-0 small" placeholder="Paga" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div class="form-group mt-4 ml-1">
              <div class="custom-control custom-checkbox small">
                <input name="ndlogin" type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label mt-0" for="customCheck1">Qasje ne Website</label>
              </div>
            </div>




            <button style="width: 100%; margin: 15px 0px" onclick="submitMyForm('ndForm')" class="btn btn-primary btn-icon-split" id="ndrysho" type="submit" name="ndrysho" form="ndForm" value="upload">
              <span class="text">Ndrysho</span>
            </button>

          </form>

        </div>
      </div>
    </div>
  </div>




  <div id="up-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
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
                <button onclick="closeUpload()" name="close" class="btn btn-sm btn-danger btn-circle">
                  <i class='fa fa-times'></i>
                </button>
              </td>
            </tr>
          </table>


        </div>

        <!-- Card Body -->
        <div class="card-body">

          <form id="upForm" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">

            <div style="margin: 8px 0px">
              <label for="upemri" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Emri</label>
              <input type="text" id="upemri" name="upemri" class="form-control bg-light border-0 small" placeholder="Emri" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div style="margin: 8px 0px">
              <label for="upmail" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Emaili</label>
              <input type="text" id="upmail" name="upmail" class="form-control bg-light border-0 small" placeholder="Emaili" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div style="margin: 8px 0px">
              <label for="uppass" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Passwordi</label>
              <input type="text" id="uppass" name="uppass" class="form-control bg-light border-0 small" placeholder="Passwordi" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div style="margin: 8px 0px">
              <label for="upqmim" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Paga</label>
              <input type="text" id="upqmim" name="upqmim" class="form-control bg-light border-0 small" placeholder="Paga" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div class="form-group mt-4 ml-1">
              <div class="custom-control custom-checkbox small">
                <input name="uplog" type="checkbox" class="custom-control-input" id="customCheck">
                <label class="custom-control-label mt-0" for="customCheck">Qasje ne Website</label>
              </div>
            </div>




            <button style="width: 100%; margin: 15px 0px" onclick="submitMyForm('upForm')" class="btn btn-primary btn-icon-split" id="upload" type="submit" name="upload" form="upForm" value="upload">
              <span class="text">Upload</span>
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

          <p style="margin-top: 5px">Deshironi ta shlyeni punëtorin:</p>
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


  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->



    <div class="row">
      <div class="col-lg-6 mb-4">
        <h1 class="h3 mb-2 text-gray-800">Lista e punëtoreve</h1>
        <p class="mb-4">Lista me të dhënat e punëtorve dhe informacioneve për qasje ne Website.</p>
      </div>

      <div class="col-lg-6 mb-4">
        <button onclick="openUpload()" style="width:100%" class="text-white btn btn-primary">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Shto Punëtor</span></a>

          </div>
        </button>
      </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e punëtoreve</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Emri</th>
                <th>Email</th>
                <th>Paga</th>
                <th>Admin</th>
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Emri</th>
                <th>Email</th>
                <th>Paga</th>
                <th>Admin</th>
                <th>Tjera</th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td><?php echo $item['emri'] ?></td>
                  <td><?php echo $item['email'] ?></td>
                  <td>€ <?php echo $item['paga'] ?></td>
                  <td>
                    <?php if ($item['login'] > 0) : ?>
                      <span class="btn-success btn-sm btn-circle" style="opacity: 80%">
                        <i class="fas fa-check" style="margin-top: 2px"></i>
                      </span>
                    <?php else : ?>
                      <span class="btn-danger btn-sm btn-circle" style="opacity: 80%">
                        <i class="fas fa-times" style="margin-top: 2px"></i>
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>

                    <button onclick="openDel('<?php echo $item['index'] ?>','<?php echo $item['emri'] ?>')" class="btn btn-sm btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
                    </button>

                    <button onclick="openForm('<?php echo $item['emri'] ?>','<?php echo $item['paga'] ?>','<?php echo $item['login'] ?>','<?php echo $item['index'] ?>')" class="btn btn-sm btn-primary btn-circle">
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

            function openForm(emri, paga, login, barkodi) {
              document.getElementById("pop").style.display = "block";
              document.getElementById("ndemri").value = emri;
              document.getElementById("ndpaga").value = paga;

              document.getElementById("ndrysho").value = barkodi;
              document.getElementById("customCheck1").checked = Boolean(Number(login));
            }

            function closeForm() {
              document.getElementById("pop").style.display = "none";
            }

            function openUpload() {
              document.getElementById("up-pop").style.display = "block";
            }

            function closeUpload() {
              document.getElementById("up-pop").style.display = "none";
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

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>