<?php 
  require 'connection.php';
  checkLogin();
  $jml_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_siswa) as jml_siswa FROM siswa"));
  $jml_siswa = $jml_siswa['jml_siswa'];

  $jml_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_user) as jml_user FROM user"));
  $jml_user = $jml_user['jml_user'];

  $jml_jabatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_jabatan) as jml_jabatan FROM jabatan"));
  $jml_jabatan = $jml_jabatan['jml_jabatan'];

  $jml_agenda = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(id_agenda) as jml_agenda FROM agenda"));
  $jml_agenda = $jml_agenda['jml_agenda'];

  $jml_pengeluaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(jumlah_pengeluaran) as jml_pengeluaran FROM pengeluaran"));
  $jml_pengeluaran = $jml_pengeluaran['jml_pengeluaran'];

  $jml_uang_kas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT sum(minggu_ke_1 + minggu_ke_2 + minggu_ke_3 + minggu_ke_4) as jml_uang_kas FROM uang_kas"));
  $jml_uang_kas = $jml_uang_kas['jml_uang_kas'];
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Dashboard</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include 'include/navbar.php'; ?>

  <?php include 'include/sidebar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php if ($_SESSION['id_jabatan'] == '1'): ?> <!-- Hanya untuk admin -->
            <div class="col-lg-3">
              <div class="card shadow">
                <div class="card-body">
                  <h5><i class="fas fa-users"></i> User</h5>
                  <h6 class="text-muted">Jumlah User: <?= $jml_user; ?></h6>
                  <a href="user.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya</a>
                </div>
              </div>
            </div>
          <?php endif ?>
          <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '3'): ?> <!-- Hanya untuk wakil km dan sekretaris -->
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="fas fa-fw fa-graduation-cap"></i></i> Mahasiswa</h5>
                <h6 class="text-muted">Jumlah Mahasiswa: <?= $jml_siswa; ?></h6>
                <a href="siswa.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endif ?>
          <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '4'): ?> <!-- Hanya untuk bendahara -->
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-success fas fa-fw fa-caret-up"></i> <i class="text-success fas fa-fw fa-dollar-sign"></i> Uang Kas</h5>
                <h6 class="text-muted">Jumlah Uang Kas: Rp. <?= number_format($jml_uang_kas ?? 0); ?></h6>
                <a href="uang_kas.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-success fa fa-hand-holding-usd"></i> <i class="text-success"></i> Pendapatan</h5>
                <h6 class="text-muted">Jumlah Income: Rp. <?= number_format($jml_pendapatan ?? 0); ?></h6>
                <a href="pendapatan.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-danger fas fa-fw fa-caret-down"></i><i class="text-danger fas fa-fw fa-dollar-sign"></i> Pengeluaran</h5>
                <h6 class="text-muted">Pengeluaran: Rp. <?= number_format($jml_pengeluaran ?? 0); ?></h6>
                <a href="pengeluaran.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endif ?>
          <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3' || $_SESSION['id_jabatan'] === '5' || $_SESSION['id_jabatan'] == '6'): ?>
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="fas fa-envelope mr-1"></i> Aspirasi Mahasiswa</h5>
                <h6 class="text-muted">Jumlah Aspirasi:</h6>
                <a href="#" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endif ?>
          <?php if ($dataUser['id_jabatan'] == '6'): ?> 
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-success fas fa-fw fa-caret-up"></i> <i class="text-success fas fa-fw fa-dollar-sign"></i> Uang Kas</h5>
                <h6 class="text-muted">Jumlah Uang Kas: Rp.</h6>
                <a href="uang_kas.php" class="btn btn-info btn-xs mt-1"> Cooming Soon
                </a>
              </div>
            </div>
          </div>
          <?php endif ?>
          <?php if ($dataUser['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '4' || $_SESSION['id_jabatan'] == '6'): ?> 
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="text-danger"></i><i class="text-danger fas fa-university"></i> Pinjaman Loan</h5>
                <h6 class="text-muted">Total Pinjaman: Rp. </h6>
                <a href="#" class="btn btn-info btn-xs mt-1"> Cooming Soon</a>
              </div>
            </div>
          </div>
          <?php endif ?>
          <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3' || $_SESSION['id_jabatan'] === '5'): ?> <!-- Hanya untuk admin, wakil km dan sekretaris -->
          <div class="col-lg-3">
            <div class="card shadow">
              <div class="card-body">
                <h5><i class="fas fa-calendar mr-1"></i> Agenda</h5>
                <h6 class="text-muted">Jumlah Agenda: <?= $jml_agenda; ?></h6>
                <a href="agenda.php" class="btn btn-info btn-xs mt-1"><i class="fa fa-info-circle fa-align-justify"></i> Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endif ?>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> TIF 223S</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>

</div>
</body>
</html>
