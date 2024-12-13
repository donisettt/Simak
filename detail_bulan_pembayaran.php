<?php 
  require 'connection.php';
  checkLogin();
  $id_bulan_pembayaran = $_GET['id_bulan_pembayaran'];
  $detail_bulan_pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bulan_pembayaran WHERE id_bulan_pembayaran = '$id_bulan_pembayaran'"));
  $siswa = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nama_siswa ASC");
  $siswa_baru = mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa NOT IN (SELECT id_siswa FROM uang_kas) ORDER BY nama_siswa ASC");
  $uang_kas = mysqli_query($conn, "SELECT * FROM uang_kas INNER JOIN siswa ON uang_kas.id_siswa = siswa.id_siswa INNER JOIN bulan_pembayaran ON uang_kas.id_bulan_pembayaran = bulan_pembayaran.id_bulan_pembayaran WHERE uang_kas.id_bulan_pembayaran = '$id_bulan_pembayaran' ORDER BY nama_siswa ASC");

  if (isset($_POST['btnEditPembayaranUangKas'])) {
    if (editPembayaranUangKas($_POST) > 0) {
      setAlert("Berhasil Diubah", "Pembayaran telah diubah!", "success");
      header("Location: detail_bulan_pembayaran.php?id_bulan_pembayaran=$id_bulan_pembayaran");
    }
  }

  if (isset($_POST['btnTambahSiswa'])) {
    if (tambahSiswaUangKas($_POST) > 0) {
      setAlert("Berhasil Menambahkan", "Mahasiswa telah ditambahkan!", "success");
      header("Location: detail_bulan_pembayaran.php?id_bulan_pembayaran=$id_bulan_pembayaran");
    }
  }

?>

<!DOCTYPE html>
<html>
<head>
  <?php include 'include/css.php'; ?>
  <title>Detail Bulan Pembayaran : <?= ucwords($detail_bulan_pembayaran['nama_bulan']); ?> <?= $detail_bulan_pembayaran['tahun']; ?></title>
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
                    <h1 class="m-0 text-dark">Detail Bulan Pembayaran: <?= ucwords($detail_bulan_pembayaran['nama_bulan']); ?> <?= $detail_bulan_pembayaran['tahun']; ?></h1>
                    <h4>Rp. <?= number_format($detail_bulan_pembayaran['pembayaran_perminggu']); ?> / minggu</h4>
                </div><!-- /.col -->
                <div class="col-sm text-right">
                    <?php if ($_SESSION['id_jabatan'] === '1'): ?> <!-- Hanya untuk id_jabatan 1 -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahSiswaModal">
                            <i class="fas fa-fw fa-plus"></i> Tambah Mahasiswa
                        </button>
                    <?php endif; ?>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid bg-white p-3 rounded">
        <div class="table-responsive">
          <table class="table table-hover table-striped table-bordered" id="table_id">
            <thead>
              <tr>
                <th>No.</th>
                <th>Nama Siswa</th>
                <th>Minggu ke 1</th>
                <th>Minggu ke 2</th>
                <th>Minggu ke 3</th>
                <th>Minggu ke 4</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php foreach ($uang_kas as $duk): ?>
                <?php if ($_SESSION['id_jabatan'] == '3'): ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= ucwords(htmlspecialchars_decode($duk['nama_siswa'])); ?></td>
                    <?php if ($duk['minggu_ke_1'] == $duk['pembayaran_perminggu']): ?>
                      <td class="text-success"><?= number_format($duk['minggu_ke_1']); ?></td>
                    <?php else: ?>
                      <td class="text-danger"><?= number_format($duk['minggu_ke_1']); ?></td>
                    <?php endif ?>

                    <?php if ($duk['minggu_ke_2'] == $duk['pembayaran_perminggu']): ?>
                      <td class="text-success"><?= number_format($duk['minggu_ke_2']); ?></td>
                    <?php else: ?>
                      <td class="text-danger"><?= number_format($duk['minggu_ke_2']); ?></td>
                    <?php endif ?>

                    <?php if ($duk['minggu_ke_3'] == $duk['pembayaran_perminggu']): ?>
                      <td class="text-success"><?= number_format($duk['minggu_ke_3']); ?></td>
                    <?php else: ?>
                      <td class="text-danger"><?= number_format($duk['minggu_ke_3']); ?></td>
                    <?php endif ?>

                    <?php if ($duk['minggu_ke_4'] == $duk['pembayaran_perminggu']): ?>
                      <td class="text-success"><?= number_format($duk['minggu_ke_4']); ?></td>
                    <?php else: ?>
                      <td class="text-danger"><?= number_format($duk['minggu_ke_4']); ?></td>
                    <?php endif ?>
                  </tr>
                <?php else: ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $duk['nama_siswa']; ?></td>

                    <?php
                    // Function to generate a payment cell
                    function paymentCell($week, $duk, $weekIndex) {
                        $isPaid = $duk[$week] == $duk['pembayaran_perminggu'];
                        $nextWeekPaid = isset($duk["minggu_ke_" . ($weekIndex + 1)]) && $duk["minggu_ke_" . ($weekIndex + 1)] !== "0";

                        if ($isPaid) {
                            if ($nextWeekPaid) {
                                // Can't edit if the next week is already paid
                                return "<td><button type='button' class='badge badge-success' data-container='body' data-toggle='popover' data-placement='top' data-content='Tidak bisa mengubah minggu ke {$weekIndex}, jika minggu ke " . ($weekIndex + 1) . " dan seterusnya sudah lunas, ubahlah minggu berikutnya terlebih dahulu menjadi 0.'><i class='fas fa-fw fa-check'></i> Sudah bayar</button></td>";
                            } else {
                                // Can edit the current week payment
                                return "<td><a href='' data-toggle='modal' data-target='#editMingguKe{$weekIndex}{$duk['id_uang_kas']}' class='badge badge-success'><i class='fas fa-fw fa-check'></i> Sudah bayar</a></td>";
                            }
                        } else {
                            // Payment is not complete, display the payment amount
                            return "<td><a href='' data-toggle='modal' data-target='#editMingguKe{$weekIndex}{$duk['id_uang_kas']}' class='badge badge-danger'>" . number_format($duk[$week] ?? 0) . "</a></td>";
                        }
                    }

                    // Display the first week
                    echo paymentCell('minggu_ke_1', $duk, 1);

                    // Check the first week payment to decide subsequent week display
                    if ($duk['minggu_ke_1'] !== $duk['pembayaran_perminggu']) {
                        echo "<td><---</td><td><---</td><td><---</td>";
                    } else {
                        // Display the second week
                        echo paymentCell('minggu_ke_2', $duk, 2);

                        if ($duk['minggu_ke_2'] !== $duk['pembayaran_perminggu']) {
                            echo "<td><---</td><td><---</td>";
                        } else {
                            // Display the third week
                            echo paymentCell('minggu_ke_3', $duk, 3);

                            if ($duk['minggu_ke_3'] !== $duk['pembayaran_perminggu']) {
                                echo "<td><---</td>";
                            } else {
                                // Display the fourth week
                                echo paymentCell('minggu_ke_4', $duk, 4);
                            }
                        }
                    }
                    ?>
                </tr>                    
                  <div class="modal fade" id="editMingguKe1<?= $duk['id_uang_kas']; ?>" tabindex="-1" role="dialog" aria-labelledby="editMingguKe1Label<?= $duk['id_uang_kas']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <form method="post">
                        <input type="hidden" name="id_uang_kas" value="<?= $duk['id_uang_kas']; ?>">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editMingguKe1Label<?= $dbp['id_bulan_pembayaran']; ?>">Ubah Minggu Ke-1 : <?= $duk['nama_siswa']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="minggu_ke_1">Minggu Ke-1</label>
                              <input type="hidden" name="uang_sebelum" value="<?= $duk['minggu_ke_1']; ?>">
                              <input max="<?= $duk['pembayaran_perminggu']; ?>" type="number" name="minggu_ke_1" class="form-control" value="<?= $duk['minggu_ke_1']; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                            <button type="submit" name="btnEditPembayaranUangKas" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="modal fade" id="editMingguKe2<?= $duk['id_uang_kas']; ?>" tabindex="-1" role="dialog" aria-labelledby="editMingguKe2Label<?= $duk['id_uang_kas']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <form method="post">
                        <input type="hidden" name="id_uang_kas" value="<?= $duk['id_uang_kas']; ?>">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editMingguKe2Label<?= $dbp['id_bulan_pembayaran']; ?>">Ubah Minggu Ke-2 : <?= $duk['nama_siswa']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="minggu_ke_2">Minggu Ke-2</label>
                              <input type="hidden" name="uang_sebelum" value="<?= $duk['minggu_ke_2']; ?>">
                              <input max="<?= $duk['pembayaran_perminggu']; ?>" type="number" name="minggu_ke_2" class="form-control" value="<?= $duk['minggu_ke_2']; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                            <button type="submit" name="btnEditPembayaranUangKas" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="modal fade" id="editMingguKe3<?= $duk['id_uang_kas']; ?>" tabindex="-1" role="dialog" aria-labelledby="editMingguKe3Label<?= $duk['id_uang_kas']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <form method="post">
                        <input type="hidden" name="id_uang_kas" value="<?= $duk['id_uang_kas']; ?>">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editMingguKe3Label<?= $dbp['id_bulan_pembayaran']; ?>">Ubah Minggu Ke-3 : <?= $duk['nama_siswa']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="minggu_ke_3">Minggu Ke-3</label>
                              <input type="hidden" name="uang_sebelum" value="<?= $duk['minggu_ke_3']; ?>">
                              <input max="<?= $duk['pembayaran_perminggu']; ?>" type="number" name="minggu_ke_3" class="form-control" value="<?= $duk['minggu_ke_3']; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                            <button type="submit" name="btnEditPembayaranUangKas" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="modal fade" id="editMingguKe4<?= $duk['id_uang_kas']; ?>" tabindex="-1" role="dialog" aria-labelledby="editMingguKe4Label<?= $duk['id_uang_kas']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <form method="post">
                        <input type="hidden" name="id_uang_kas" value="<?= $duk['id_uang_kas']; ?>">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editMingguKe4Label<?= $dbp['id_bulan_pembayaran']; ?>">Ubah Minggu Ke-4 : <?= $duk['nama_siswa']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label for="minggu_ke_4">Minggu Ke-4</label>
                              <input type="hidden" name="uang_sebelum" value="<?= $duk['minggu_ke_4']; ?>">
                              <input max="<?= $duk['pembayaran_perminggu']; ?>" type="number" name="minggu_ke_4" class="form-control" value="<?= $duk['minggu_ke_4']; ?>">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
                            <button type="submit" name="btnEditPembayaranUangKas" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                  <!-- <?php
                  // Function to generate modal for editing payments
                  function generateEditModal($duk, $weekIndex) {
                      $weekField = "minggu_ke_{$weekIndex}";
                      $modalId = "editMingguKe{$weekIndex}{$duk['id_uang_kas']}";
                      $modalLabelId = "editMingguKe{$weekIndex}Label{$duk['id_uang_kas']}";
                      $studentName = $duk['nama_siswa'];
                      $currentValue = $duk[$weekField];
                      $paymentLimit = $duk['pembayaran_perminggu'];
                      $idUangKas = $duk['id_uang_kas'];

                      return "
                      <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-labelledby='{$modalLabelId}' aria-hidden='true'>
                          <div class='modal-dialog' role='document'>
                              <form method='post'>
                                  <input type='hidden' name='id_uang_kas' value='{$idUangKas}'>
                                  <div class='modal-content'>
                                      <div class='modal-header'>
                                          <h5 class='modal-title' id='{$modalLabelId}'>Ubah Minggu Ke-{$weekIndex} : {$studentName}</h5>
                                          <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                          </button>
                                      </div>
                                      <div class='modal-body'>
                                          <div class='form-group'>
                                              <label for='{$weekField}'>Minggu Ke-{$weekIndex}</label>
                                              <input type='hidden' name='uang_sebelum' value='{$currentValue}'>
                                              <input max='{$paymentLimit}' type='number' name='{$weekField}' class='form-control' value='{$currentValue}' required>
                                          </div>
                                      </div>
                                      <div class='modal-footer'>
                                          <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fas fa-fw fa-times'></i> Close</button>
                                          <button type='submit' name='btnEditPembayaranUangKas' class='btn btn-primary'><i class='fas fa-fw fa-save'></i> Save</button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                      ";
                  }

                  // Generate modals for minggu ke-3 and minggu ke-4
                  echo generateEditModal($duk, 3);
                  echo generateEditModal($duk, 4);
                  ?> -->

                <?php endif ?>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <div class="modal fade" id="tambahSiswaModal" tabindex="-1" role="dialog" aria-labelledby="tambahSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="post">
        <input type="hidden" name="id_bulan_pembayaran" value="<?= $id_bulan_pembayaran; ?>">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahSiswaModalLabel">Tambah Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="id_siswa">Nama Mahasiswa</label>
              <select name="id_siswa" id="id_siswa" class="form-control">
                <?php foreach ($siswa_baru as $dsb): ?>
                  <option value="<?= $dsb['id_siswa']; ?>"><?= $dsb['nama_siswa']; ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-fw fa-times"></i> Close</button>
            <button type="submit" name="btnTambahSiswa" class="btn btn-primary"><i class="fas fa-fw fa-save"></i> Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; <?= date("Y") ?> TIF 223S</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <script>
  // JavaScript validation to ensure the value does not exceed the limit
  document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('input[type="number"]').forEach(function(input) {
          input.addEventListener('input', function() {
              const max = parseInt(this.getAttribute('max'), 10);
              if (parseInt(this.value, 10) > max) {
                  alert('Nilai tidak boleh lebih dari ' + max);
                  this.value = max;
              }
          });
      });
  });
  </script>

</div>
</body>
</html>
