<?php

include("components/logincheck.php");

require('../db.php');


if (isset($_POST['submit'])) {

  $lloji = $_POST['lloji'];
  $kategoria = $_POST['kategoria'];


  $query = "INSERT INTO lloji(lloji,kategoria) VALUES('$lloji','$kategoria')";

  if (mysqli_query($conn, $query)) {
    err("Lloji u regjistrua me sukses!", 1);
  } else {
    err("Lloji nuk është regjistruar, provo përseri!");
  }
}

if (isset($_POST['delete'])) {

  $delquery = "DELETE FROM lloji WHERE lloji ='" . $_POST['delete'] . "'";
  if (mysqli_query($conn, $delquery)) {
    err("Lloji u shlye me sukses!", 1);
  } else {
    err("Lloji nuk është fshirë, provo përseri!");
  }
}

if (isset($_POST['ndrysho'])) {

  $ndlloji = $_POST['ndlloji'];

  $ndkategoria = $_POST['ndkategoria'];

  $query = "UPDATE lloji SET lloji = '" . $ndlloji . "', kategoria = '" . $ndkategoria . "' WHERE lloji = '" . $_POST["ndrysho"] . "'";

  if (mysqli_query($conn, $query)) {
    $uquery = "UPDATE xcom SET lloji = '" . $ndlloji . "' WHERE lloji = '" . $_POST["ndrysho"] . "'";
    if (mysqli_query($conn, $uquery)) {
      err("Lloji u ndryshua me sukses!", 1);
    } else {
      err("Lloji nuk arriti te ndryshohet tek produktet!", 2);
    }
  } else {

    err("Lloji nuk është ndryshuar, provo përseri!");
  }
}

$query1 = 'SELECT * FROM lloji';

$result = mysqli_query($conn, $query1);

$items = mysqli_fetch_all($result, MYSQLI_ASSOC);



mysqli_free_result($result);


$kquery = 'SELECT * FROM kategoria';

$kresult = mysqli_query($conn, $kquery);

$kategorit = mysqli_fetch_all($kresult, MYSQLI_ASSOC);

mysqli_free_result($kresult);



?>



<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>XCom - Llojet</title>

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

  $active = "lloj";

  include("components/topbar.php")

  ?>

  <!-- Begin Page Content -->



  <div id="pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
    <div class="modal-dialog" role="document">
      <div class="modal-content border-0">
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
              <label for="ndrysho-input" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Lloji</label>
              <input type="text" id="ndrysho-input" name="ndlloji" class="form-control bg-light border-0 small" placeholder="Lloji" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div style="margin: 8px 0px">
              <label for="nd_option" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Kategoria</label>
              <select name="ndkategoria" class="browser-default custom-select">
                <option id="nd_option" selected>Kategoria</option>
                <?php foreach ($kategorit as $kategori) : ?>
                  <option value="<?php echo $kategori["kategoria"] ?>"><?php echo $kategori["kategoria"] ?></option>
                <?php endforeach; ?>
              </select>
            </div>



            <button style="width: 100%; margin-top:15px" class="btn btn-primary btn-icon-split" id="ndrysho-edit-btn" type="submit" name="ndrysho" form="ndForm" value="ndrysho">
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
        <div style="z-index: 1" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
        <div style="z-index: 1" class="card-body">

          <p style="margin-top: 5px">Deshironi ta shlyeni llojin:</p>
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






  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Llojet</h1>
    <p class="mb-4">Llojet e produkteve.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e llojeve</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Lloji</th>
                <th>Kategoria</th>

                <th>Regjistro Produktet</th>
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Lloji</th>
                <th>Kategoria</th>

                <th>Regjistro Produktet</th>
                <th>Tjera</th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td><?php echo $item['lloji'] ?></td>
                  <td><?php echo $item['kategoria'] ?></td>

                  <td><a href="llojet_pro.php?lloji=<?php echo $item['lloji'] ?>"> Regjistro Produktet ></a></td>
                  <td>

                    <a href="llojet_info.php?lloji=<?php echo $item['lloji'] ?>" class="btn btn-sm btn-info btn-circle">
                      <i class="fas fa-info-circle"></i>
                    </a>

                    <button onclick="openDel('<?php echo $item['lloji'] ?>')" class="btn btn-sm btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
                    </button>

                    <button onclick="openForm('<?php echo $item['lloji'] ?>','<?php echo $item['kategoria'] ?>')" class="btn btn-sm btn-primary btn-circle">
                      <i class='fa fa-pen'></i>
                    </button>

                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


        <script>
          function submitMyForm(form) {
            document.getElementById(form).submitForm();
          }

          function openForm(value, kategoria) {
            document.getElementById("pop").style.display = "block";
            document.getElementById("ndrysho-edit-btn").value = value;
            document.getElementById("ndrysho-input").value = value;
            document.getElementById("nd_option").innerHTML = kategoria;
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



      </div>
    </div>


    <div class="col-md-6 mb-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Krijo lloj</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">

            <form id="form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
              <table class="table" width="100%" cellspacing="0" style="margin-top: 20px;">
                <tbody>
                  <tr>

                    <td>
                      <input type="text" name="lloji" class="form-control bg-light border-0 small" placeholder="Lloji" aria-label="Search" aria-describedby="basic-addon2">
                    </td>

                    <td>
                      <select name="kategoria" class="browser-default custom-select">
                        <option selected>Kategoria</option>
                        <?php foreach ($kategorit as $kategori) : ?>
                          <option value="<?php echo $kategori["kategoria"] ?>"><?php echo $kategori["kategoria"] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>

                    <td>
                      <button class="btn btn-secondary btn-icon-split" name="submit" type="submit" id="submit" form="form">
                        <span class="icon text-white-50">
                          <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text">Krijo lloj</span>
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