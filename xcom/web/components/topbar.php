<?php

if (isset($_POST["log"])) {
  $_SESSION["loggedin"] = false;
  $_SESSION["name"] = "";
  header("location: ../../index.php");
  exit();
}

?>


<!-- Page Wrapper -->
<div id="wrapper">

  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
      <div class="sidebar-brand-icon">
        <img class="img-fluid" style="width:2.2rem" src="img/logo.svg" alt="xcom" />
      </div>
      <div class="sidebar-brand-text mx-3">XCOM</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo $active == "dash" ? 'active' : ''; ?>">
      <a class="nav-link" href="index.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
    </li>

    <!-- Divider -->

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Produktet
    </div>

    

    <li class="nav-item <?php echo $active == "pro" ? 'active' : ''; ?>">
      <a class="nav-link" href="produktet.php">
        <i class="fas fa-fw fa-table"></i>
        <span>Produktet</span></a>
    </li>


    <li class="nav-item <?php echo $active == "lloj" ? 'active' : ''; ?>">
      <a class="nav-link" href="llojet.php">
        <i class="fas fa-fw fa-tag"></i>
        <span>Llojet</span></a>
    </li>


    <li class="nav-item <?php echo $active == "kat" ? 'active' : ''; ?>">
      <a class="nav-link" href="kategorit.php">
        <i class="fas fa-fw fa-columns"></i>
        <span>Kategoritë</span></a>
    </li>

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Menagjim
    </div>

    <li class="nav-item <?php echo $active == "pun" ? 'active' : ''; ?>">
      <a class="nav-link" href="puntor.php">
        <i class="fas fa-fw fa-briefcase"></i>
        <span>Puntort</span></a>
    </li>

    <li class="nav-item <?php echo $active == "ads" ? 'active' : ''; ?>">
      <a class="nav-link" href="ads.php">
        <i class="fas fa-fw fa-ad"></i>
        <span>Reklamat</span></a>
    </li>
    

    <li class="nav-item <?php echo $active == "img" ? 'active' : ''; ?>">
      <a class="nav-link" href="images.php">
        <i class="fas fa-fw fa-image"></i>
        <span>Fotot</span></a>
      </li>
      <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
      Te dhenat
    </div>
      <li class="nav-item <?php echo $active == "user" ? 'active' : ''; ?>">
        <a class="nav-link" href="users.php">
          <i class="fas fa-fw fa-users"></i>
          <span>Perdoruesit</span></a>
      </li>
    <li class="nav-item <?php echo $active == "rate" ? 'active' : ''; ?>">
      <a class="nav-link" href="rate.php">
        <i class="fas fa-fw fa-star"></i>
        <span>Vlersimet</span></a>
    </li>

    


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

  </ul>
  <!-- End of Sidebar -->

  <!-- Content Wrapper -->
  <div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

      <!-- Topbar -->
      <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
          <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Search -->
        

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

          <!-- Nav Item - Search Dropdown (Visible Only XS) -->
          <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
              <form class="form-inline mr-auto w-100 navbar-search">
                <div class="input-group">
                  <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                      <i class="fas fa-search fa-sm"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li>

          

          <!-- Nav Item - Messages -->
          





          <li class="nav-item dropdown no-arrow show">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
              <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["name"]; ?></span>
              <img class="img-profile rounded-circle" src="img/avatar1.png">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

              <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Çkyquni
              </a>
            </div>
          </li>
        </ul>

      </nav>
      <!-- End of Topbar -->


      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Çkyquni</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">Deshironi te çkyqëni?</div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
              <form class="user" id="Form1" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                <button name="log" form="Form1" class="btn btn-primary" href="login.html">Çkyquni</button>
              </form>
            </div>
          </div>
        </div>
      </div>


      <div id="error-pop" class="modal" style="background-color:rgba(0,0,0,0.3)">
        <div class="modal-dialog" role="document">
          <div class="modal-content border-0">


            <!-- Card Header - Dropdown -->
            <div style="z-index: 1" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <table style="width: 100%">
                <tr>
                  <td style="width: 100%">
                    <h6 class="m-0 font-weight-bold text-primary">Mesazhet</h6>
                  </td>

                  <td>
                    <button onclick="closeErr()" class="btn btn-sm btn-danger btn-circle">
                      <i class='fa fa-times'></i>
                    </button>
                  </td>
                </tr>
              </table>


            </div>

            <!-- Card Body -->
            <div style="z-index: 1" class="card-body">

              <p style="margin-top: 5px" id="error">Mesazhi</p>

            </div>
          </div>
        </div>
      </div>