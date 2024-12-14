<?php 
  require 'connection.php';
  checkLogin();
  $pinjaman = mysqli_query($conn, "SELECT * FROM pinjaman ORDER BY nama_mahasiswa ASC");
  $mhs = "SELECT id_siswa, nim, nama_siswa FROM siswa";
  $result = $conn->query($mhs);
  // Fungsi untuk menghasilkan kode unik
  function generateKodeInvoice($nim) {
    $kodeUnik = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)); // Kode unik 6 karakter
    return "PNJTIF-{$nim}-{$kodeUnik}";
  }

  // Periksa apakah form sudah diisi
  $kode_invoice = isset($_POST['nama_mahasiswa']) ? generateKodeInvoice($_POST['nama_mahasiswa']) : '';

  if (isset($_POST['btnEditPendapatan'])) {
    if (editPendapatan($_POST) > 0) {
      setAlert("Berhasil Diubah", "Pinjaman telah diubah!", "success");
      header("Location: pinjaman.php");
    }
  }

  if (isset($_POST['btnTambahPinjaman'])) {
    if (addPinjaman($_POST) > 0) {
      setAlert("Berhasil Menambahkan", "Pinjaman berhasil ditambahkan!", "success");
      header("Location: pinjaman.php");
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
  <title>Pinjaman</title>
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
            <h1 class="m-0 text-dark">Pinjaman</h1>
          </div><!-- /.col -->
          <div class="col-sm text-right">
            <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahPinjamanModal"><i class="fas fa-fw fa-plus"></i> Tambah Pinjaman</button>
              <!-- Modal -->
              <div class="modal fade text-left" id="tambahPinjamanModal" tabindex="-1" role="dialog" aria-labelledby="tambahPinjamanModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahPinjamanModalLabel">Tambah Pinjaman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                            <select id="nama_mahasiswa" name="nama_mahasiswa" class="form-control" required>
                                <option value="">-- Pilih Nama Mahasiswa --</option>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Menyimpan nama_siswa sebagai value dan menampilkan nama_siswa
                                        echo '<option value="' . $row['nama_siswa'] . '" data-nim="' . $row['nim'] . '">' . $row['nama_siswa'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Tidak ada data mahasiswa</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kd_invoice">Kode Invoice</label>
                            <input type="text" id="kd_invoice" name="kd_invoice" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                          <label for="keterangan">Tujuan Pinjaman</label>
                          <input type="text" id="tujuan_pinjam" name="tujuan_pinjam" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="jumlah_pendapatan">Jumlah Pinjaman</label>
                            <input type="number" id="jumlah_pinjaman" name="jumlah_pinjaman" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="tanggal_pinjam">Tanggal Pinjam</label>
                          <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control">
                      </div>
                      <div class="form-group">
                          <label for="tanggal_jatuh_tempo">Jatuh Tempo</label>
                          <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" class="form-control" readonly>
                      </div>
                      <div class="form-group" id="form-group-status">
                          <label for="status">Status</label>
                          <select name="status" id="status" class="form-control">
                              <option value="Lunas">Lunas</option>
                              <option value="Belum Lunas" selected>Belum Lunas</option>
                          </select>
                      </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                        <button type="submit" class="btn btn-primary" name="btnTambahPinjaman"><i class="fas fa-fw fa-save"></i> Save</button>
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
                    <th>Kode Invoice</th>
                    <th>Nama Mahasiswa</th>
                    <th>Tujuan Pinjaman</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
                      <th>Aksi</th>
                    <?php endif ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($pinjaman as $p): ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $p['kd_invoice']; ?></td>
                      <td><?= ucwords(htmlspecialchars_decode($p['nama_mahasiswa'])); ?></td>
                      <td><?= $p['tujuan_pinjam']; ?></td>
                      <td>Rp. <?= $p['jumlah_pinjaman']; ?></td>
                      <td><?= $p['tanggal_pinjam']; ?></td>
                      <td><?= $p['jatuh_tempo']; ?></td>
                      <td>
                          <?php 
                          $status = htmlspecialchars($p['status']); // Asumsi $p adalah data hasil query
                          if ($status == 'Lunas') {
                              echo '<span class="badge badge-success">Lunas</span>';
                          } else {
                              echo '<span class="badge badge-danger">Belum Lunas</span>';
                          }
                          ?>
                      </td>
                      <?php if ($_SESSION['id_jabatan'] === '1' || $_SESSION['id_jabatan'] === '2' || $_SESSION['id_jabatan'] === '4'): ?>
                        <td>
                          <!-- Button trigger modal -->
                          <a href="ubah_pinjaman.php?kd_invoice=<?= $p['kd_invoice']; ?>" class="badge badge-success" data-toggle="modal" data-target="#editPinjaman<?= $p['kd_invoice']; ?>">
                            <i class="fas fa-fw fa-edit"></i>
                          </a>
                          <!-- Modal -->
                          <!-- <div class="modal fade" id="editPendapatan<?= $p['id_pendapatan']; ?>" tabindex="-1" role="dialog" aria-labelledby="editPendapatan<?= $p['id_pendapatan']; ?>" aria-hidden="true">
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
                        </td> -->
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

<script>
    document.getElementById('nama_mahasiswa').addEventListener('change', function () {
        const nim = this.options[this.selectedIndex].getAttribute('data-nim'); // Ambil NIM dari atribut data-nim
        if (nim) {
            const kodeUnik = Math.random().toString(36).substr(2, 6).toUpperCase(); // Kode unik
            document.getElementById('kd_invoice').value = `PNJTIF-${nim}-${kodeUnik}`;
        } else {
            document.getElementById('kd_invoice').value = ''; // Reset jika tidak ada mahasiswa dipilih
        }
    });

    document.getElementById('tanggal_pinjam').addEventListener('change', function () {
        const tanggalPinjam = new Date(this.value); // Ambil nilai tanggal pinjam
        if (!isNaN(tanggalPinjam)) {
            tanggalPinjam.setDate(tanggalPinjam.getDate() + 30); // Tambahkan 30 hari
            const jatuhTempo = tanggalPinjam.toISOString().split('T')[0]; // Format menjadi yyyy-mm-dd
            document.getElementById('tanggal_jatuh_tempo').value = jatuhTempo; // Set nilai jatuh tempo
        } else {
            document.getElementById('tanggal_jatuh_tempo').value = ''; // Kosongkan jika input tidak valid
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        statusSelect.value = 'Belum Lunas'; // Set nilai default menjadi "Belum Lunas"
    });
</script>
