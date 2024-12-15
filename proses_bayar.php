<?php
// Koneksi ke database
include 'koneksi.php';

if (isset($_POST['btnBayar'])) {
    $id_pendapatan = $_POST['id_pendapatan'];
    $kd_invoice = $_POST['kd_invoice'];
    $jumlah_bayar = $_POST['jumlah_bayar'];

    // Ambil total pendapatan
    $query = mysqli_query($koneksi, "SELECT jumlah_pendapatan FROM pendapatan WHERE id_pendapatan = '$id_pendapatan'");
    $data = mysqli_fetch_assoc($query);
    $total_pendapatan = $data['jumlah_pendapatan'];

    // Update pembayaran di database
    mysqli_query($koneksi, "UPDATE pendapatan SET status = IF($jumlah_bayar >= $total_pendapatan, 'Lunas', 'Belum Lunas') WHERE id_pendapatan = '$id_pendapatan'");

    // Redirect ke halaman awal dengan notifikasi
    echo "<script>alert('Pembayaran berhasil!'); window.location='halaman_pendapatan.php';</script>";
}
?>
