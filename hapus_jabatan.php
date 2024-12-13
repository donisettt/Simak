<?php 
	require 'connection.php';
	$id_jabatan = $_GET['id_jabatan'];
	if (isset($id_jabatan)) {
		if (deleteJabatan($id_jabatan) > 0) {
			setAlert("Berhasil Dihapus", "Jabatan berhasil dihapus!", "success");
		    header("Location: jabatan.php");
	    }
	} else {
	   header("Location: jabatan.php");
	}