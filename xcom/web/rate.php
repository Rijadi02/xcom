<?php

include("components/logincheck.php");

require('../db.php');


$query1 = 'SELECT * FROM rating';

$result = mysqli_query($conn, $query1);

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

  <title>XCom - Vlersimet</title>

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

  $active = "rate";

  include("components/topbar.php")

  ?>

  <!-- Begin Page Content -->









  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Vlersimet</h1>
    <p class="mb-4">Lista e vlersimeve dhe komenteve për produkte.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e vlersimeve</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Produkti</th>
                <th>Vlersimi</th>
                <th>Data</th>
                <th>Komenti</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>ID</th>
                <th>Produkti</th>
                <th>Vlersimi</th>
                <th>Data</th>
                <th>Komenti</th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach ($items as $item) : ?>
                <tr>
                  <td><?php echo $item['ID'] ?></td>
                  <td><a href="#"> <?php echo $item['barkodi'] ?> </a></td>
                  <td>
                    <?php for ($i = 0; $i < 5; $i++) {
                      if ($i < $item['rate']) {
                        echo  '<span class="fa fa-star text-primary"></span>';
                      } else {
                        echo '<span class="fa fa-star"></span>';
                      }
                    } ?>
                    <span style="margin-left: 10px"><?php echo $item['rate'] ?></span>
                  </td>
                  <td><?php echo $item['date'] ?></td>
                  <td><?php echo $item['comment'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>



      </div>
    </div>



  </div>
  <!-- /.container-fluid -->


  <!-- End of Main Content -->

  <!-- Footer -->



  <!-- End of Content Wrapper -->


  <!-- End of Page Wrapper -->

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