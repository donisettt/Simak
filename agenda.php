<?php 
  require 'connection.php';
  checkLogin();
  $agenda = mysqli_query($conn, "SELECT * FROM agenda ORDER BY nama_agenda ASC");
  if (isset($_POST['btnEditAgenda'])) {
    if (editAgenda($_POST) > 0) {
      setAlert("Berhasil Diubah", "Agenda telah diubah!", "success");
      header("Location: agenda.php");
    }
  }

  if (isset($_POST['btnTambahAgenda'])) {
    if (addAgenda($_POST) > 0) {
      setAlert("Berhasil Menambahkan", "Agenda berhasil ditambahkan!", "success");
      header("Location: agenda.php");
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
  <title>Agenda</title>
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
            <h1 class="m-0 text-dark">Agenda</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3'): ?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahAgendaModal"><i class="fas fa-fw fa-plus"></i> Tambah Agenda</button>
              <!-- Modal -->
              <div class="modal fade text-left" id="tambahAgendaModal" tabindex="-1" role="dialog" aria-labelledby="tambahAgendaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahAgendaModalLabel">Tambah Agenda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="nama_agenda">Nama Agenda</label>
                          <input type="text" id="nama_agenda" name="nama_agenda" class="form-control" required>
                        </div>
                        <div class="form-group">
                          <label for="keterangan">Keterangan</label>
                          <input type="text" id="keterangan" name="keterangan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tgl_kegiatan">Tanggal Kegiatan</label>
                            <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="lokasi">Lokasi</label>
                          <input type="text" name="lokasi" id="lokasi" class="form-control">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-primary" name="btnTambahAgenda"><i class="fas fa-fw fa-save"></i> Save</button>
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
                    <th>Nama Agenda</th>
                    <th>Keterangan</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Lokasi</th>
                    <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3'): ?>
                      <th>Aksi</th>
                    <?php endif ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($agenda as $agd): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= ucwords(htmlspecialchars_decode($agd['nama_agenda'])); ?></td>
                      <td><?= $agd['keterangan']; ?></td>
                      <td><?= $agd['tgl_kegiatan']; ?></td>
                      <td><?= $agd['lokasi']; ?></td>
                      <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '3'): ?>
                        <td>
                          <!-- Button trigger modal -->
                          <a href="ubah_agenda.php?id_agenda=<?= $agd['id_agenda']; ?>" class="badge badge-success" data-toggle="modal" data-target="#editAgenda<?= $agd['id_agenda']; ?>">
                            <i class="fas fa-fw fa-edit"></i>
                          </a>
                          <!-- Modal -->
                          <div class="modal fade" id="editAgenda<?= $agd['id_agenda']; ?>" tabindex="-1" role="dialog" aria-labelledby="editAgenda<?= $agd['id_agenda']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form method="post">
                                <input type="hidden" name="id_agenda" value="<?= $agd['id_agenda']; ?>">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="editAgendaModalLabel<?= $agd['id_agenda']; ?>">Ubah Agenda</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label for="nama_agenda<?= $agd['id_agenda']; ?>">Nama Agenda</label>
                                      <input type="text" id="nama_agenda<?= $agd['id_agenda']; ?>" value="<?= $agd['nama_agenda']; ?>" name="nama_agenda" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                      <label for="keterangan<?= $agd['id_agenda']; ?>">Keterangan</label>
                                      <input type="text" name="keterangan" value="<?= $agd['keterangan']; ?>" id="keterangan<?= $agd['id_agenda']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                      <label for="tgl_kegiatan<?= $agd['id_agenda']; ?>">Tanggal Kegiatan</label>
                                      <input type="date" name="tgl_kegiatan" value="<?= $agd['tgl_kegiatan']; ?>" id="tgl_kegiatan<?= $agd['id_agenda']; ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                      <label for="lokasi<?= $agd['id_agenda']; ?>">Lokasi</label>
                                      <input type="text" name="lokasi" value="<?= $agd['lokasi']; ?>" id="lokasi<?= $agd['id_agenda']; ?>" class="form-control">
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                                    <button type="submit" class="btn btn-primary" name="btnEditAgenda"><i class="fas fa-fw fa-save"></i> Save</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          <?php if ($_SESSION['id_jabatan'] == '1' || $_SESSION['id_jabatan'] == '2' || $_SESSION['id_jabatan'] == '3'): ?>
                            <a data-nama="<?= $agd['nama_agenda']; ?>" class="btn-delete badge badge-danger" href="hapus_agenda.php?id_agenda=<?= $agd['id_agenda']; ?>"><i class="fas fa-fw fa-trash"></i></a>
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
  <?php include 'include/footer.php'; ?>

</div>
</body>
</html>