<?php 
  require 'connection.php';
  checkLogin();
  $pendapatan = mysqli_query($conn, "SELECT * FROM pendapatan ORDER BY nama_pendapatan ASC");
  if (isset($_POST['btnEditPendapatan'])) {
    if (editPendapatan($_POST) > 0) {
      setAlert("Berhasil Diubah", "Pendapatan telah diubah!", "success");
      header("Location: pendapatan.php");
    }
  }

  if (isset($_POST['btnTambahPendapatan'])) {
    if (addPendapatan($_POST) > 0) {
      setAlert("Berhasil Menambahkan", "Pendapatan berhasil ditambahkan!", "success");
      header("Location: pendapatan.php");
    }
  }
  if (isset($_GET['toggle_modal'])) {
    $toggle_modal = $_GET['toggle_modal'];
    echo "
    <script>
      $(document).ready(function() {
        $('#$toggle_modal').modal('show');
      });
    </script>
    ";
  }
?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Pendapatan</title>
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
        <div class="row justify-content-center mb-2">
          <div class="col-sm text-left">
            <h1 class="m-0 text-dark">Pendapatan</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahPendapatanModal"><i class="fas fa-fw fa-plus"></i> Tambah Pendapatan</button>
              <!-- Modal -->
              <div class="modal fade text-left" id="tambahPendapatanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPendapatanModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahPendapatanModalLabel">Tambah Pendapatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="nama_pendapatan">Nama Pendapatan</label>
                          <input type="text" id="nama_pendapatan" name="nama_pendapatan" class="form-control" required>
                        </div>
                        <div class="form-group">
                          <label for="keterangan">Keterangan</label>
                          <input type="text" id="keterangan" name="keterangan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pendapatan">Jumlah Pendapatan</label>
                            <input type="number" id="jumlah_pendapatan" name="jumlah_pendapatan" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="tanggal_pendapatan">Tanggal Pendapatan</label>
                          <input type="date" name="tanggal_pendapatan" id="tanggal_pendapatan" class="form-control">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-primary" name="btnTambahPendapatan"><i class="fas fa-fw fa-save"></i> Save</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endif ?>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered" id="table_id">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pendapatan</th>
                    <th>Keterangan</th>
                    <th>Jumlah Pendapatan</th>
                    <th>Tanggal Pendapatan</th>
                    <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
                      <th>Aksi</th>
                    <?php endif ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($pendapatan as $p): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= ucwords(htmlspecialchars_decode($p['nama_pendapatan'])); ?></td>
                      <td><?= $p['keterangan']; ?></td>
                      <td><?= $p['jumlah_pendapatan']; ?></td>
                      <td><?= $p['tanggal_pendapatan']; ?></td>
                      <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
                        <td>
                          <!-- Button trigger modal -->
                          <a href="ubah_pendapatan.php?id_pendapatan=<?= $p['id_pendapatan']; ?>" class="badge badge-success" data-toggle="modal" data-target="#editPendapatan<?= $p['id_pendapatan']; ?>">
                            <i class="fas fa-fw fa-edit"></i> Edit
                          </a>
                          <!-- Modal -->
                          <div class="modal fade" id="editPendapatan<?= $p['id_pendapatan']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPendapatan<?= $p['id_pendapatan']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post">
                                <input type="hidden" name="id_pendapatan" value="<?= $p['id_pendapatan']; ?>">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editPendapatanModalLabel<?= $p['id_pendapatan']; ?>">Ubah Pendapatan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label for="nama_pendapatan<?= $p['id_pendapatan']; ?>">Nama Pendapatan</label>
                                      <input type="text" id="nama_pendapatan<?= $p['id_pendapatan']; ?>" value="<?= $p['nama_pendapatan']; ?>" name="nama_pendapatan" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                      <label for="keterangan<?= $p['id_pendapatan']; ?>">Keterangan</label>
                                      <input type="text" name="keterangan" value="<?= $p['keterangan']; ?>" id="keterangan<?= $p['id_pendapatan']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                      <label for="jumlah_pendapatan<?= $p['id_pendapatan']; ?>">Jumlah Pendapatan</label>
                                      <input type="number" name="jumlah_pendapatan" value="<?= $p['jumlah_pendapatan']; ?>" id="jumlah_pendapatan<?= $p['id_pendapatan']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                      <label for="tanggal_pendapatan<?= $p['id_pendapatan']; ?>">Tanggal Pendapatan</label>
                                      <input type="date" name="tanggal_pendapatan" value="<?= $p['tanggal_pendapatan']; ?>" id="tanggal_pendapatan<?= $p['id_pendapatan']; ?>" class="form-control">
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                                    <button type="submit" class="btn btn-primary" name="btnEditPendapatan"><i class="fas fa-fw fa-save"></i> Save</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2'): ?>
                            <a data-nama="<?= $p['nama_pendapatan']; ?>" class="btn-delete badge badge-danger" href="hapus_pendapatan.php?id_pendapatan=<?= $p['id_pendapatan']; ?>"><i class="fas fa-fw fa-trash"></i> Hapus</a>
                          <?php endif ?>
                        </td>
                      <?php endif ?>
                    </tr>
                  <?php endforeach ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  

</div>
</body>
</html>