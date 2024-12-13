<?php 
	require 'connection.php';
	$id_agenda = $_GET['id_agenda'];
	if (isset($id_agenda)) {
		if (deleteAgenda($id_agenda) > 0) {
			setAlert("Berhasil dihapus", "Agenda berhasil dihapus!", "success");
		    header("Location: agenda.php");
	    }
	} else {
	   header("Location: agenda.php");
	}
?>