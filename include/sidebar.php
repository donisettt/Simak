<?php 
$dataUser = dataUser();
$jml_pengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_pengeluaran) AS jml_pengeluaran FROM pengeluaran"));
$jml_pengeluaran = $jml_pengeluaran['jml_pengeluaran'];

$jml_uang_kas = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        SUM(IFNULL(minggu_ke_1, 0) + IFNULL(minggu_ke_2, 0) + IFNULL(minggu_ke_3, 0) + IFNULL(minggu_ke_4, 0)) 
        AS jml_uang_kas 
    FROM uang_kas
"));
$jml_uang_kas = $jml_uang_kas['jml_uang_kas'];

$jml_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_pendapatan) AS jml_pendapatan FROM pendapatan"));
$jml_pendapatan = $jml_pendapatan['jml_pendapatan'];
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <img src="assets/img/tif-logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">TIF 223S</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- <li class="nav-item has-treeview menu-open">
          <a href="profile.php" class="nav-link"><i class="nav-icon fas fa-fw fa-user"></i> <p><?= $dataUser['username']; ?></p></a>
        </li> -->
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->   

        <li class="nav-item has-treeview menu-open">
          <a href="index.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <?php 
        if (isset($dataUser['id_jabatan'], $_SESSION['id_jabatan']) && ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '4')): 
        ?>
          <div class="bg-success nav-link text-white">
            <i class="nav-icon fas fa-fw fa-wallet"></i>
            <p>
              Sisa Saldo: <?= number_format(($jml_uang_kas ?? 0) + ($jml_pendapatan ?? 0) - ($jml_pengeluaran ?? 0)); ?>
            </p>
          </div>
        <?php endif ?>

        <!-- Data Master Hanya untuk admin -->
          <h6 class="mt-3" style="color: white;">Menu</h6>
          <li class="nav-item">
            <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '3' || $_SESSION['id_jabatan'] == '5' || $_SESSION['id_jabatan'] == '6'): ?>
            <a class="nav-link dropdown-toggle" href="#" id="dropdownDataMaster" role="button" data-toggle="collapse" data-target="#dataMasterMenu" aria-expanded="false" aria-controls="dataMasterMenu">
              <i class="fas fa-fw fa-database nav-icon"></i> Data Master
            </a>
            <?php endif ?>
            <div class="collapse" id="dataMasterMenu">
              <ul class="nav flex-column ml-3">
                <?php if ($dataUser['id_jabatan'] == '1'): ?>
                <li class="nav-item">
                  <a href="jabatan.php" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-briefcase"></i>
                    <p>Jabatan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="user.php" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>User</p>
                  </a>
                </li>
                <?php endif ?>

                <!-- Data master untuk admin, wakil km dan sekretaris -->
                <?php if ($dataUser['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3'): ?>
                  <li class="nav-item">
                    <a href="siswa.php" class="nav-link">
                      <i class="fas fa-fw fa-graduation-cap nav-icon"></i>
                      <p>Mahasiswa</p>
                    </a>
                  </li>
                <?php endif ?>
                <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '3' || $_SESSION['id_jabatan'] == '5' || $_SESSION['id_jabatan'] == '6'): ?>
                  <li class="nav-item">
                    <a href="agenda.php" class="nav-link">
                    <i class="fas fa-fw fa-calendar nav-icon"></i>
                      <p>Agenda</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="aspirasi.php" class="nav-link">
                    <i class="fas fa-envelope nav-icon"></i>
                      <p>Aspirasi Mahasiswa</p>
                    </a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </li>

        <!-- Keuangan Hanya untuk admin, wakil km, dan bendahara -->
        <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '4'): ?> 
        <li class="nav-item">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownKeuangan" role="button" data-toggle="collapse" data-target="#keuanganMenu" aria-expanded="false" aria-controls="keuanganMenu">
            <i class="fas fa-fw fa-wallet nav-icon"></i> Keuangan
          </a>
          <div class="collapse" id="keuanganMenu">
            <ul class="nav flex-column ml-3">
            <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '4'): ?> 
              <li class="nav-item">
                <a href="uang_kas.php" class="nav-link">
                  <i class="fas fa-fw fa-money-bill nav-icon"></i>
                  <p>Uang Kas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pendapatan.php" class="nav-link">
                  <i class="fa fa-hand-holding-usd nav-icon"></i>
                  <p>Pendapatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pengeluaran.php" class="nav-link">
                  <i class="fas fa-fw fa-shopping-cart nav-icon"></i>
                  <p>Pengeluaran</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fas fa-university nav-icon"></i>
                  <p>Pinjaman Loan</p>
                </a>
              </li>
              <?php endif ?>

              <!-- Next Rilis -->
              <!-- <?php if ($dataUser['id_jabatan'] == '6'): ?> 
                <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="fas fas fa-university nav-icon"></i>
                  <p>Pinjaman Saya</p>
                </a>
              </li>
              <?php endif ?> -->

            </ul>
          </div>
        </li>
        <?php endif; ?>

        <!-- Report hanya untuk admin, wakil km dan bendahara -->
        <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '4'): ?> 
        <h6 class="mt-2" style="color: white;">Report</h6>
        <li class="nav-item">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownReport" role="button" data-toggle="collapse" data-target="#reportMenu" aria-expanded="false" aria-controls="reportMenu">
          <i class="fas fa-file-invoice nav-icon"></i> Laporan
          </a>
          <div class="collapse" id="reportMenu">
            <ul class="nav flex-column ml-3">
            <li class="nav-item">
                <a href="laporan.php" class="nav-link">
                  <i class="fas fa-fw fa-file-alt nav-icon"></i>
                  <p>Laporan Keuangan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="riwayat.php" class="nav-link">
                  <i class="fas fa-fw fa-history nav-icon"></i>
                  <p>Riwayat Uang Kas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="riwayat_pengeluaran.php" class="nav-link">
                  <i class="fas fa-fw fa-receipt nav-icon"></i>
                  <p>Riwayat Pengeluaran</p>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <?php endif ?>

    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>