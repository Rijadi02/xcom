<?php

include("components/logincheck.php");

require('../db.php');

$lloji = $_GET['lloji'];

if (isset($_POST['submit'])) {
  foreach ($_POST['buy'] as $item) {

    $update = "UPDATE xcom SET lloji='" . $lloji . "' WHERE barkodi = '" . $item . "';";

    if (mysqli_query($conn, $update)) {
      err(", lloji iu vendos me sukses!",1,$item);
    } else {
      err(", lloji nuk arriti te vendoset, provo përseri!",0,$item);
    }
  }
};



$query = 'SELECT * FROM xcom';

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

  <title>XCom - Lloji_<?php echo $lloji ?></title>

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
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?php echo $lloji ?></h1>
    <p class="mb-4">Shto produkte në këtë lloj</p>


    <script language="JavaScript">
      function toggle(source) {
        checkboxes = document.getElementsByName('buy[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
          checkboxes[i].checked = source.checked;
        }
      }
    </script>


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e Produkteve</h6>
      </div>
      <form method="POST" action="<?php $_SERVER['PHP_SELF']; ?>" id="form">
        <div class="card-body">
          <div class="table-responsive">


            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <td>
                    <div class="custom-control custom-checkbox small">
                      <input type="checkbox" name='check' class="chk_boxes custom-control-input" id="customCheck" value='all' onClick="toggle(this)" />
                      <label class="custom-control-label" for="customCheck"></label>
                    </div>
                  <th>Emri</th>
                  <th>Barkodi</th>
                  <th>Lloji</th>
                  <th>Qmimi</th>

                  <th>Tjera</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th></th>
                  <th>Emri</th>
                  <th>Barkodi</th>
                  <th>Lloji</th>
                  <th>Qmimi</th>

                  <th>Tjera</th>

                </tr>
              </tfoot>
              <tbody>
                <?php foreach ($items as $item) : ?>
                  <tr>
                    <td>
                      <div class="custom-control custom-checkbox small">
                        <input type='checkbox' id="customCheck<?php echo $item['barkodi']?>" class="chk_boxes1 custom-control-input" name='buy[]' value="<?php echo $item['barkodi'] ?>" />
                        <label class="custom-control-label" for="customCheck<?php echo $item['barkodi']?>"></label>
                      </div>
                      
                    </td>
                    <td><?php echo $item['emri'] ?></td>
                    <td><?php echo $item['barkodi'] ?></td>
                    <td><?php echo $item['lloji'] ?></td>
                    <td><?php echo $item['qmimi'] ?></td>

                    <td><a href="produktet_info.php?barkodi=<?php echo $item['barkodi'] ?>" target="_blank" class="btn-sm btn-info btn-circle">
                        <i class="fas fa-info-circle"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div style="margin: 20px">
          <button style="width: 100%; height:50px" class="btn btn-primary btn-icon-split" name="submit" type="submit" id="submit" form="form">

            <span class="text">Regjistro Produktet</span>
          </button>
        </div>

      </form>



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