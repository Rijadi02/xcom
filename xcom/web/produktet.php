<?php

include("components/logincheck.php");

require('../db.php');

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

if (isset($_POST['upload'])) {

  $upemri = $_POST['upemri'];

  $upbar = $_POST['upbar'];

  $uplloji = $_POST['uplloj'];

  $upqmimi = $_POST['upqmim'];

  $upvendi = "";

  $querya = "SELECT prefix, name FROM flags";

  $resulta = mysqli_query($conn, $querya) or err("Ka problem me kyqjen ne databasë!");

  $itemsa = mysqli_fetch_all($resulta, MYSQLI_ASSOC);

  mysqli_free_result($resulta);

  $errorflag = false;
  foreach ($itemsa as $currentItem) {
    $errorflag = false;
    if ($currentItem['prefix'] == substr($_POST['upbar'], 0, 1)) {
      $upvendi = $currentItem['name'];
      break;
    } elseif ($currentItem['prefix'] == substr($_POST['upbar'], 0, 2)) {

      $upvendi = $currentItem['name'];
      break;
    } elseif ($currentItem['prefix'] == substr($_POST['upbar'], 0, 3)) {

      $upvendi = $currentItem['name'];
      break;
    }else
    {
      $errorflag = true;
    }
  }

  if($errorflag)
  {
    err("Vendi nuk arriti te vendoset për shkak te barkodit!",2);
  }

  $query = "INSERT INTO xcom(emri,barkodi,lloji,qmimi,vendi) VALUES('$upemri','$upbar','$uplloji','$upqmimi','$upvendi')";

  if (mysqli_query($conn, $query)) {
    err("Produkti u regjistrua me sukses!", 1);
  } else {
    err("Produkti nuk është regjistrar, provo përseri!");
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

$query = 'SELECT * FROM xcom';

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

  <title>XCom - Produktet</title>

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

  $active = "pro";

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
              <label for="ndqmimi" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Emri</label>
              <input type="text" id="ndemri" name="ndemri" class="form-control bg-light border-0 small" value="" aria-label="Search" aria-describedby="basic-addon2">
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
              <input type="text" id="ndqmim" name="ndqmim" class="form-control bg-light border-0 small" value="" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <button style="width: 100%; margin: 15px 0px" class="btn btn-primary btn-icon-split" id="ndrysho-edit-btn" type="submit" name="ndrysho" form="ndForm" value="ndrysho">
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
              <label for="upbar" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Barkodi</label>
              <input type="text" id="upbar" name="upbar" class="form-control bg-light border-0 small" placeholder="Barkodi" aria-label="Search" aria-describedby="basic-addon2">
            </div>

            <div style="margin: 8px 0px">

              <label for="uplloj" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Lloji</label>

              <select name="uplloj" class="browser-default custom-select">
                <option id="uplloj" selected>Lloji</option>
                
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
              <label for="upqmim" class="mx-2" style="font-size: 0.7rem; margin:0 5px">Qmimi</label>
              <input type="text" id="upqmim" name="upqmim" class="form-control bg-light border-0 small" placeholder="Qmimi" aria-label="Search" aria-describedby="basic-addon2">
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


  <!-- Begin Page Content -->
  <div class="container-fluid">

    <!-- Page Heading -->



    <div class="row">
      <div class="col-lg-6 mb-4">
        <h1 class="h3 mb-2 text-gray-800">Produktet</h1>
        <p class="mb-4">Ketu mund ti shikoni tabelen e produkteve dhe te beni ndryshime ne produkte.</p>
      </div>

      <div class="col-lg-6 mb-4">
        <button onclick="openUpload()" style="width:100%" class="text-white btn btn-primary">
          <div class="card-body">
            <i class="fas fa-fw fa-upload"></i>
            <span>Regjistro Produkt</span></a>

          </div>
        </button>
      </div>
    </div>
    <!-- DataTales Example -->
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
                <th>Lloji</th>
                <th>Vendi</th>
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
                <th>Lloji</th>
                <th>Vendi</th>
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
                  <td><?php echo $item['lloji'] ?></td>
                  <td><?php echo $item['vendi'] ?></td>
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

                    <button onclick="openForm('<?php echo $item['emri'] ?>','<?php echo $item['barkodi'] ?>','<?php echo $item['lloji'] ?>','<?php echo $item['qmimi'] ?>')" class="btn btn-sm btn-primary btn-circle">
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

            function openForm(emri, barkodi, lloji, qmimi) {
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