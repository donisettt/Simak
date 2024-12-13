<?php 
  require 'connection.php';
  checkLogin();
  
  // Menghapus semua riwayat jika tombol diklik
  if (isset($_POST['btnHapusSemua'])) {
    $hapusRiwayat = mysqli_query($conn, "DELETE FROM riwayat");
    if ($hapusRiwayat) {
      setAlert("Berhasil Dihapus", "Semua riwayat berhasil dihapus!", "success");
      header("Location: riwayat.php");
      exit;
    } else {
      setAlert("Gagal menghapus riwayat!", "Terjadi kesalahan saat menghapus data.", "error");
    }
  }

  // Mengambil data riwayat
  $riwayat = mysqli_query($conn, "SELECT * FROM riwayat 
    INNER JOIN user ON riwayat.id_user = user.id_user 
    INNER JOIN uang_kas ON riwayat.id_uang_kas = uang_kas.id_uang_kas 
    INNER JOIN siswa ON uang_kas.id_siswa = siswa.id_siswa 
    INNER JOIN bulan_pembayaran ON uang_kas.id_bulan_pembayaran = bulan_pembayaran.id_bulan_pembayaran 
    ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Riwayat Uang Kas</title>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include 'include/navbar.php'; ?>
  <?php include 'include/sidebar.php'; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm">
            <h1 class="m-0 text-dark">Riwayat Uang Kas</h1>
          </div>
          <div class="col-sm text-right">
            <!-- Tombol Hapus Semua Riwayat -->
            <form method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua riwayat?');">
              <button type="submit" name="btnHapusSemua" class="btn btn-danger">Hapus Semua Riwayat</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="table-responsive">
          <table class="table table-striped table-hover" id="table_id">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Mahasiswa</th>
                <th>Bulan & Tahun</th>
                <th>Username</th>
                <th>Pesan</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php foreach ($riwayat as $dr): ?>
                <tr>
                  <td><?= $i++; ?></td>
                  <td><?= ucwords($dr['nama_siswa']); ?></td>
                  <td><?= ucwords($dr['nama_bulan']); ?> | <?= $dr['tahun']; ?></td>
                  <td><?= $dr['username']; ?></td>
                  <td><?= $dr['aksi']; ?></td>
                  <td><?= date('d-m-Y, H:i:s', $dr['tanggal']); ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> TIF 223S</strong>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>
</body>
</html>