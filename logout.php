<?php 
require 'connection.php';
session_start(); // Pastikan sesi dimulai

// Ambil username dari sesi
$username = $_SESSION['username'] ?? ''; // Menggunakan null coalescing operator untuk mencegah error jika tidak ada username

// Hapus sesi
session_destroy(); 

// Cek apakah logout berhasil
if (logout()) {
    setAlert("Berhasil Keluar", "Sampai Jumpa $username", "success");
} else {
    // Anda bisa menambahkan penanganan jika logout gagal
    setAlert("Logout Gagal", "Silakan coba lagi!", "error");
}

// Redirect ke halaman login setelah logout
header("Location: login.php");
exit(); // Hentikan eksekusi setelah redirect
?>
