<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <!-- <span class="nama"><?php echo $_SESSION['username']; ?></span><br>_<tr>Selamat Datang</tr> -->
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
    <a href="#" class="nav-link" data-toggle="dropdown">
        <i class="far fa-user"></i>
        <!-- <span class="username"><?php echo $_SESSION['username']; ?></span> -->
    </a>
    <tr>
      <!-- <a> <h8> ID </h8> <?php echo $_SESSION['nama']; ?></a> -->
  </tr>
      <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
        <a href="<?php echo site_url('auth/logout') ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
      </div>
    </li>
  </ul>
 
</nav>
<!-- /.navbar -->