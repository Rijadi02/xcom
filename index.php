<?php
require('xcom/db.php');

session_start();
if (!isset($_SESSION["loggedin"])) {
  $_SESSION["loggedin"] = false;
}
if ($_SESSION["loggedin"] === true) {
  header("location: xcom/web/index.php");
  exit();
}

$msg = "";
$_SESSION['mail'] = "";

if (isset($_POST["submit"])) {

  $mail = $_POST['mail'];
  $_SESSION['mail'] = $_POST['mail'];
  $pass = $_POST['pass'];

  $idcheckquery = "SELECT email, hash, salt, login, emri FROM puntor WHERE email ='" . $mail . "'";

  $idcheck = mysqli_query($conn, $idcheckquery);

  if (mysqli_num_rows($idcheck) != 1) {
    $msg = "Emaili nuk egziston";
  } else {
    $existinginfo = mysqli_fetch_assoc($idcheck);

    $salt = $existinginfo["salt"];

    $hash = $existinginfo["hash"];

    //$salt = "\$5\$rounds=5000\$" . "steamedhams" . $emri . "\$";
    $loginhash = crypt($pass, $salt);
    if ($hash != $loginhash) {
      $msg = "Keni gabuar fjalekalimin";
    } else {
      if ($existinginfo["login"] != 1) {
        $msg = "Nuk ke qasje ne Web";
      } else {
        $_SESSION["name"] = $existinginfo['emri'];
        $_SESSION["loggedin"] = true;
        header("location: xcom/web/index.php");
        exit();
      }
    }
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

  <title>XCom</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="xcom/web/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Mirësevini!</h1>
                  </div>
                  <form class="user" id="Form" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                      <input type="email" value="<?php echo $_SESSION['mail']; ?>" name="mail" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email Adresa...">
                    </div>
                    <div class="form-group">
                      <input type="password" name="pass" class="form-control form-control-user" id="exampleInputPassword" placeholder="Fjalëkalimi">
                    </div>
                    
                    <button form="Form" name="submit" value="submit" class="btn btn-primary btn-user btn-block">
                      Kyquni
                    </button>
                  </form>
                  <hr>
                  <div class="form-group">
                      <div class="text-center">

                        <label class="text-danger" for="customCheck"><?php echo $msg; ?></label>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>