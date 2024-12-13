<?php 
	require 'connection.php';
	$id_siswa = $_GET['id_siswa'];
	if (isset($id_siswa)) {
		if (deleteSiswa($id_siswa) > 0) {
			setAlert("Berhasil Dihapus", "Mahasiswa berhasil dihapus!", "success");
		    header("Location: siswa.php");
	    }
	} else {
	   header("Location: siswa.php");
	}
?>