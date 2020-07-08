<?php

include("components/logincheck.php");

require('../db.php');



include("../../addTarget.php");

if (isset($_POST['delete'])) {
  include '../../EasyARCloudSdk.php';

 

  $sdk = new EasyARClientSdkCRS($appKey, $appSecret, $appHost);

  $rs = $sdk->similar($_POST['delete']);
  if ($rs->statusCode == 0) {
    $rs = $sdk->delete($rs->result->results[0]->targetId);
    if ($rs->statusCode == 0) {
      $delquery = "DELETE FROM image WHERE barkodi='" . $_POST['p2'] . "'";
      if (mysqli_query($conn, $delquery)) {
        err("Foto u shlye me sukses!",1);
      } else {
        err("Foto nuk u shlye, provo përseri!");
      }
    } else {
      err("Foto nuk u shlye, provo përseri!");
    };
  } else {
    err("Foto nuk u shlye, provo përseri!");
  }
}

$query = 'SELECT * FROM image';

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

  <title>SB Admin 2 - Tables</title>

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

  $active = "img";

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
                  <h1 class="h4 text-gray-900 mb-5">Shto Foto</h1>
                </div>

                <?php if ($msg != '') : ?>
                  <div class="alert <?php echo $msgClass ?>">
                    <?php echo $msg ?>
                  </div>
                <?php endif; ?>

                <form class="user" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">

                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="bar" value="<?php echo isset($_POST['bar']) ? $bar : ""; ?>" type="number" class="form-control text-center form-control-user" id="exampleFirstName" placeholder="Barkodi">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="lloji" value="<?php echo isset($_POST['lloji']) ? $lloji : ""; ?>" type="text" class="form-control text-center form-control-user" id="exampleFirstName" placeholder="Lloji">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12 mb-3 mb-sm-0">
                      <input name="image" type="file" class="form-control form-control-user hide-file" id="exampleFirstName">
                    </div>
                  </div>
                  <input type="submit" name="submit" value="Shto Foto" class="btn btn-primary btn-user btn-block">
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

          <p style="margin-top: 5px">Deshironi ta shlyeni foton:</p>
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
    <h1 class="h3 mb-2 text-gray-800">Fotot</h1>
    <p class="mb-4">Fotot te cilat do te skanohen nga aplikacioni <b>XCom</b>.</p>
    <div class="row">
      <div class="col-lg-6 mb-4">
        <button onclick="openForm()" style="width:100%" class="text-white btn btn-primary">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Shto foto</span></a>

          </div>
        </button>
      </div>

      <div class="col-lg-6 mb-4">
        <a href="images_multi.php" style="width:100%" class="text-white btn btn-info">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i><i class="fas fa-fw fa-upload"></i><i class="fas fa-fw fa-upload"></i>
            <span>Shto disa foto</span>
          </div>
        </a>
      </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tabela e fotove</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Barkodi</th>
                
                <th>Tjera</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Barkodi</th>
                
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
                  <td>
                    <button onclick="openImg('<?php echo $item['img'] ?>')" class="btn btn-primary btn-circle btn-sm">
                      <span>
                        <i class="fas fa-fw fa-image"></i>
                      </span>
                    </button>
                  
                    <button onclick="openDel('<?php echo $item['img'] ?>','<?php echo $item['barkodi'] ?>')" class="btn btn-sm btn-danger btn-circle">
                      <i class="fas fa-trash"></i>
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
              document.getElementById("img-src").src = "data:image/gif;base64,"+value;
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