<?php 
	include 'include/js.php';
	
	session_start();
	
	date_default_timezone_set("Asia/Jakarta");

	$host		= "localhost";
	$username	= "root";
	$password	= "";
	$database	= "tif23subang_native";

	$conn 		= mysqli_connect($host, $username, $password, $database, 3306);
	if ($conn) {
		// echo "berhasil terkoneksi!";
	} else {
		echo "gagal terkoneksi!";
	}

// ======================================== FUNCTION ========================================
function setAlert($title='', $text='', $type='', $buttons='') {
	$_SESSION["alert"]["title"]		= $title;
	$_SESSION["alert"]["text"] 		= $text;
	$_SESSION["alert"]["type"] 		= $type;
	$_SESSION["alert"]["buttons"]	= $buttons; 
}

if (isset($_SESSION['alert'])) {
	$title 		= $_SESSION["alert"]["title"];
	$text 		= $_SESSION["alert"]["text"];
	$type 		= $_SESSION["alert"]["type"];
	$buttons	= $_SESSION["alert"]["buttons"];

	echo"
		<div id='msg' data-title='".$title."' data-type='".$type."' data-text='".$text."' data-buttons='".$buttons."'></div>
		<script>
			let title 		= $('#msg').data('title');
			let type 		= $('#msg').data('type');
			let text 		= $('#msg').data('text');
			let buttons		= $('#msg').data('buttons');

			if(text != '' && type != '' && title != '') {
				Swal.fire({
					title: title,
					text: text,
					icon: type,
				});
			}
		</script>
	";
	unset($_SESSION["alert"]);
}

function checkLogin() {
	if (!isset($_SESSION['id_user'])) {
		setAlert("Access Denied!", "Login First!", "error");
		header('Location: login.php');
	} 
}

function checkLoginAtLogin() {
	if (isset($_SESSION['id_user'])) {
		setAlert("You has been logged!", "Welcome!", "success");
		header('Location: index.php');
	}
}

// Function Logout

function logout() {
	setAlert("You has been logout!", "Success Logout!", "success");
	header("Location: login.php");
}

if (isset($_SESSION['id_user'])) {
	function dataUser() {
		global $conn;
		$id_user = $_SESSION['id_user'];
		return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user INNER JOIN jabatan ON user.id_jabatan = jabatan.id_jabatan WHERE id_user = '$id_user'"));
	}
}

// Function Edit User

function editUser($data) {
	global $conn;
	$id_user = htmlspecialchars($data['id_user']);
	$nama_lengkap = htmlspecialchars(addslashes($data['nama_lengkap']));
  	$username = htmlspecialchars($data['username']);
  	$id_jabatan = htmlspecialchars($data['id_jabatan']);
  	$query = mysqli_query($conn, "UPDATE user SET nama_lengkap = '$nama_lengkap', username = '$username', id_jabatan = '$id_jabatan' WHERE id_user = '$id_user'");
  	return mysqli_affected_rows($conn);
}

// Function Edit Jabatan

function editJabatan($data) {
	global $conn;
	$id_jabatan = htmlspecialchars($data['id_jabatan']);
  	$nama_jabatan = htmlspecialchars($data['nama_jabatan']);
  	$query = mysqli_query($conn, "UPDATE jabatan SET nama_jabatan = '$nama_jabatan' WHERE id_jabatan = '$id_jabatan'");
  	return mysqli_affected_rows($conn);
}

// Function Cek Jabatan

function checkJabatan() {
	$id_jabatan = $_SESSION['id_jabatan'];
	if ($id_jabatan !== '1' && $id_jabatan !== '2' && $id_jabatan !== '3') {
		setAlert("Akses ditolak!", "You cannot delete data except administrator!", "error");
     	header("Location: index.php");
	} else {
		return true;
	}
}

// Function Hapus Jabatan

function deleteJabatan($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM jabatan WHERE id_jabatan = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

// Function Tambah Jabatan

function addJabatan($data) {
    global $conn;
    $nama_jabatan = htmlspecialchars($data['nama_jabatan']);
    $query = mysqli_query($conn, "INSERT INTO jabatan (nama_jabatan) VALUES ('$nama_jabatan')");
    return mysqli_affected_rows($conn);
}

// Function Tambah User

function addUser($data) {
    global $conn;
    // cek username sudah ada atau belum
    $username = htmlspecialchars($data['username']);
    $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    if (mysqli_fetch_assoc($query)) {
        setAlert("Gagal menambahkan user!", "Username sudah ada!", "error");
        return header("Location: user.php");
    } else {
        $password = htmlspecialchars($data['password']);
        $password_verify = htmlspecialchars($data['password_verify']);
        if ($password !== $password_verify) {
            setAlert("Gagal menambahkan user!", "Password tidak sama!", "error");
            return header("Location: user.php");
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $nama_lengkap = htmlspecialchars($data['nama_lengkap']);
            $id_jabatan = htmlspecialchars($data['id_jabatan']);
            
            $query = "INSERT INTO user (nama_lengkap, username, password, id_jabatan) 
                      VALUES ('$nama_lengkap', '$username', '$password', '$id_jabatan')";
            
            mysqli_query($conn, $query);
            
            return mysqli_affected_rows($conn);
        }
    }
}

// Function Hapus User

function deleteUser($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

// Function Tambah Mahasiswa

function addSiswa($data) {
    global $conn;
    $nama_siswa = htmlspecialchars($data['nama_siswa']);
    $nim = htmlspecialchars($data['nim']);
    $jenis_kelamin = htmlspecialchars($data['jenis_kelamin']);
    $no_telepon = htmlspecialchars($data['no_telepon']);
    $email = htmlspecialchars($data['email']);
    
    $query = "INSERT INTO siswa (nama_siswa, nim, jenis_kelamin, no_telepon, email) 
              VALUES ('$nama_siswa', '$nim', '$jenis_kelamin', '$no_telepon', '$email')";
    
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

// Function Hapus Mahasiswa

function deleteSiswa($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

// Function Edit Mahasiswa

function editSiswa($data) {
	global $conn;
	$id_siswa = htmlspecialchars($data['id_siswa']);
	$nim = htmlspecialchars($data['nim']);
	$nama_siswa = htmlspecialchars($data['nama_siswa']);
	$jenis_kelamin = htmlspecialchars($data['jenis_kelamin']);
	$no_telepon = htmlspecialchars($data['no_telepon']);
	$email = htmlspecialchars($data['email']);
	$query = mysqli_query($conn, "UPDATE siswa SET nama_siswa = '$nama_siswa', nim = '$nim' , jenis_kelamin = '$jenis_kelamin', no_telepon = '$no_telepon', email = '$email' WHERE id_siswa = '$id_siswa'");
  	return mysqli_affected_rows($conn);
}

// Function untuk tambah agenda

function addAgenda($data) {
    global $conn;
    $nama_agenda = htmlspecialchars($data['nama_agenda']);
    $keterangan = htmlspecialchars($data['keterangan']);
    $tgl_kegiatan = htmlspecialchars($data['tgl_kegiatan']);
    $lokasi = htmlspecialchars($data['lokasi']);
	$query = mysqli_query($conn, "INSERT INTO agenda VALUES ('', '$nama_agenda', '$keterangan' , '$tgl_kegiatan', '$lokasi')");
  	return mysqli_affected_rows($conn);
}

// Function Edit Agenda

function editAgenda($data) {
	global $conn;
	$id_agenda = htmlspecialchars($data['id_agenda']);
	$nama_agenda = htmlspecialchars($data['nama_agenda']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$tgl_kegiatan = htmlspecialchars($data['tgl_kegiatan']);
	$lokasi = htmlspecialchars($data['lokasi']);
	$query = mysqli_query($conn, "UPDATE agenda SET nama_agenda = '$nama_agenda', keterangan = '$keterangan', tgl_kegiatan = '$tgl_kegiatan', lokasi = '$lokasi' WHERE id_agenda = '$id_agenda'");
  	return mysqli_affected_rows($conn);
}

// Function Hapus Agenda

function deleteAgenda($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM agenda WHERE id_agenda = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

// Function untuk pendapatan

function addPendapatan($data) {
    global $conn;
    $nama_pendapatan = htmlspecialchars($data['nama_pendapatan']);
    $keterangan = htmlspecialchars($data['keterangan']);
    $jumlah_pendapatan = htmlspecialchars($data['jumlah_pendapatan']);
    $tanggal_pendapatan = htmlspecialchars($data['tanggal_pendapatan']);
    
    // Menghilangkan nilai untuk kolom 'id_pendapatan' karena AUTO_INCREMENT
    $query = "INSERT INTO pendapatan (nama_pendapatan, keterangan, jumlah_pendapatan, tanggal_pendapatan) 
              VALUES ('$nama_pendapatan', '$keterangan', '$jumlah_pendapatan', '$tanggal_pendapatan')";
    
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn); 
}

// Function Untuk Edit Pendapatan

function editPendapatan($data) {
	global $conn;
	$id_pendapatan = htmlspecialchars($data['id_pendapatan']);
	$nama_pendapatan = htmlspecialchars($data['nama_pendapatan']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$jumlah_pendapatan = htmlspecialchars($data['jumlah_pendapatan']);
	$tanggal_pendapatan = htmlspecialchars($data['tanggal_pendapatan']);
	$query = mysqli_query($conn, "UPDATE pendapatan SET nama_pendapatan = '$nama_pendapatan', keterangan = '$keterangan', jumlah_pendapatan = '$jumlah_pendapatan', tanggal_pendapatan = '$tanggal_pendapatan' WHERE id_pendapatan = '$id_pendapatan'");
  	return mysqli_affected_rows($conn);
}

// Function Untuk Hapus Pendapatan

function deletePendapatan($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM pendapatan WHERE id_pendapatan = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

// FUnction Add Pinjaman
function addPinjaman($data) {
    global $conn;
    
    // Validasi dan pemberian nilai default
    $kd_invoice = isset($data['kd_invoice']) ? htmlspecialchars($data['kd_invoice']) : '';
    $nama_mahasiswa = isset($data['nama_mahasiswa']) ? htmlspecialchars($data['nama_mahasiswa']) : '';
    $tujuan_pinjam = isset($data['tujuan_pinjam']) ? htmlspecialchars($data['tujuan_pinjam']) : '';
    $jumlah_pinjaman = isset($data['jumlah_pinjaman']) ? htmlspecialchars($data['jumlah_pinjaman']) : '';
    $tanggal_pinjam = isset($data['tanggal_pinjam']) ? htmlspecialchars($data['tanggal_pinjam']) : '';
    $status = isset($data['status']) ? htmlspecialchars($data['status']) : '';
    
    // Tentukan jatuh_tempo 30 hari setelah tanggal_pinjam
    if (!empty($tanggal_pinjam)) {
        $jatuh_tempo = date('Y-m-d', strtotime($tanggal_pinjam . ' +30 days'));
    } else {
        $jatuh_tempo = NULL; // Atau bisa kosong jika ingin membiarkan nilai NULL
    }

    // Query untuk menyimpan data pinjaman
    $query = "INSERT INTO pinjaman (kd_invoice, nama_mahasiswa, tujuan_pinjam, jumlah_pinjaman, tanggal_pinjam, jatuh_tempo, status) 
              VALUES ('$kd_invoice', '$nama_mahasiswa', '$tujuan_pinjam', '$jumlah_pinjaman', '$tanggal_pinjam', '$jatuh_tempo', '$status')";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_affected_rows($conn); // Mengembalikan jumlah baris yang terpengaruh
    } else {
        // Jika terjadi kesalahan pada query
        echo "Error: " . mysqli_error($conn);
        return 0;
    }
}

// Function Tambah Bulan Pembayaran

function addBulanPembayaran($data) {
    global $conn;
    $nama_bulan = htmlspecialchars($data['nama_bulan']);
    $tahun = htmlspecialchars($data['tahun']);
    $pembayaran_perminggu = htmlspecialchars($data['pembayaran_perminggu']);
    
    // Check the month has been used or NOT
    $check_bulan = mysqli_query($conn, "SELECT * FROM bulan_pembayaran WHERE nama_bulan = '$nama_bulan' AND tahun = '$tahun'");
    
    if (mysqli_fetch_assoc($check_bulan)) {
        setAlert("Failed to add " . ucwords($nama_bulan) . "!", "Because the " . ucwords($nama_bulan) . " has been used!", "error");
        return header("Location: uang_kas.php");
    } else {
        $dataSiswa = mysqli_query($conn, "SELECT * FROM siswa");
        
        if (mysqli_num_rows($dataSiswa) === 0) {
            setAlert("Gagal Menambahkan", "Tidak ada data mahasiswa, hubungi sekretaris!", "error");
            return header("Location: uang_kas.php");
        }

        $query = "INSERT INTO bulan_pembayaran (nama_bulan, tahun, pembayaran_perminggu)
                  VALUES ('$nama_bulan', '$tahun', '$pembayaran_perminggu')";
        
        $result = mysqli_query($conn, $query);

        if (!$result) {
            setAlert("Error", "Failed to add bulan pembayaran.", "error");
            return header("Location: uang_kas.php");
        }

        $id_bulan_pembayaran = mysqli_insert_id($conn);
        
        $siswa = "INSERT INTO uang_kas (id_siswa, id_bulan_pembayaran, minggu_ke_1, minggu_ke_2, minggu_ke_3, minggu_ke_4) VALUES ";
        $values = [];

        while ($ds = mysqli_fetch_assoc($dataSiswa)) {
            $values[] = "('{$ds['id_siswa']}', '{$id_bulan_pembayaran}', NULL, NULL, NULL, NULL)";
        }

        $siswa .= implode(", ", $values);
        $query_siswa = mysqli_query($conn, $siswa);
        
        if ($query_siswa) {
            return mysqli_affected_rows($conn);
        } else {
            mysqli_query($conn, "DELETE FROM bulan_pembayaran WHERE id_bulan_pembayaran = '$id_bulan_pembayaran'");
            setAlert("Gagal", "Gagal menambahkan uang kas!", "error");
            return header("Location: uang_kas.php");
        }
    }
}

// Function edit bulan pembayaran

function editBulanPembayaran($data) {
	global $conn;
	$id_bulan_pembayaran = htmlspecialchars($data['id_bulan_pembayaran']);
	$nama_bulan = htmlspecialchars($data['nama_bulan']);
	$tahun = htmlspecialchars($data['tahun']);
	$pembayaran_perminggu = htmlspecialchars($data['pembayaran_perminggu']);
	$query = mysqli_query($conn, "UPDATE bulan_pembayaran SET nama_bulan = '$nama_bulan', tahun = '$tahun', pembayaran_perminggu = '$pembayaran_perminggu' WHERE id_bulan_pembayaran = '$id_bulan_pembayaran'");
  	return mysqli_affected_rows($conn);
}

// Function Riwayat Uang Kas

function riwayat($id_user, $id_uang_kas, $aksi) {
    global $conn;
    $tanggal = time();
    return mysqli_query($conn, "INSERT INTO riwayat (id_user, id_uang_kas, aksi, tanggal) VALUES ('$id_user', '$id_uang_kas', '$aksi', '$tanggal')");
}

// Function Riwayat Pengeluaran

function riwayatPengeluaran($id_user, $aksi) {
    global $conn;
    $tanggal = time();
    // Ubah query untuk tidak menyertakan id_riwayat_pengeluaran
    return mysqli_query($conn, "INSERT INTO riwayat_pengeluaran (id_user, aksi, tanggal) VALUES ('$id_user', '$aksi', '$tanggal')");
}


// Function Edit Pembayaran

function editPembayaranUangKas($data) {
	global $conn;
	$id_uang_kas = htmlspecialchars($data['id_uang_kas']);
	$id_user = $_SESSION['id_user'];
	if (isset($_POST['minggu_ke_1'])) {
		$uang_sebelum = htmlspecialchars($data['uang_sebelum']);
		$minggu_ke_1 = htmlspecialchars($_POST['minggu_ke_1']);
		$query = mysqli_query($conn, "UPDATE uang_kas SET minggu_ke_1 = '$minggu_ke_1' WHERE id_uang_kas = '$id_uang_kas'");
		riwayat($id_user, $id_uang_kas, "telah mengubah pembayaran minggu ke-1 dari Rp. " . number_format($uang_sebelum !== '' ? floatval($uang_sebelum) : 0) . " menjadi Rp. " . number_format($minggu_ke_1 !== '' ? floatval($minggu_ke_1) : 0));
  		return mysqli_affected_rows($conn);
	} elseif (isset($_POST['minggu_ke_2'])) {
		$uang_sebelum = htmlspecialchars($data['uang_sebelum']);
		$minggu_ke_2 = htmlspecialchars($_POST['minggu_ke_2']);

		// Pastikan $uang_sebelum bukan string kosong
		if (!empty($uang_sebelum)) {
			// Mengkonversi menjadi float untuk pemformatan
			$uang_sebelum_float = (float) $uang_sebelum; 
		} else {
			$uang_sebelum_float = 0; // atau nilai default lainnya
		}

		$query = mysqli_query($conn, "UPDATE uang_kas SET minggu_ke_2 = '$minggu_ke_2' WHERE id_uang_kas = '$id_uang_kas'");

		// Pastikan $minggu_ke_2 juga valid
		if (!empty($minggu_ke_2)) {
			// Mengkonversi menjadi float untuk pemformatan
			$minggu_ke_2_float = (float) $minggu_ke_2;
		} else {
			$minggu_ke_2_float = 0; // atau nilai default lainnya
		}

		// Catatan riwayat dengan pemformatan yang benar
		riwayat($id_user, $id_uang_kas, "telah mengubah pembayaran minggu ke-2 dari Rp. " . number_format($uang_sebelum_float) . " menjadi Rp. " . number_format($minggu_ke_2_float));

  		return mysqli_affected_rows($conn);
	} elseif (isset($_POST['minggu_ke_3'])) {
		$uang_sebelum = htmlspecialchars($data['uang_sebelum']);
		$minggu_ke_3 = htmlspecialchars($_POST['minggu_ke_3']);

		// Pastikan $uang_sebelum bukan string kosong
		if (!empty($uang_sebelum)) {
			// Mengkonversi menjadi float untuk pemformatan
			$uang_sebelum_float = (float) $uang_sebelum; 
		} else {
			$uang_sebelum_float = 0; // atau nilai default lainnya
		}

		$query = mysqli_query($conn, "UPDATE uang_kas SET minggu_ke_3 = '$minggu_ke_3' WHERE id_uang_kas = '$id_uang_kas'");

		// Pastikan $minggu_ke_3 juga valid
		if (!empty($minggu_ke_3)) {
			// Mengkonversi menjadi float untuk pemformatan
			$minggu_ke_3_float = (float) $minggu_ke_3;
		} else {
			$minggu_ke_3_float = 0; // atau nilai default lainnya
		}

		// Catatan riwayat dengan pemformatan yang benar
		riwayat($id_user, $id_uang_kas, "telah mengubah pembayaran minggu ke-3 dari Rp. " . number_format($uang_sebelum_float) . " menjadi Rp. " . number_format($minggu_ke_3_float));

  		return mysqli_affected_rows($conn);

	} elseif (isset($_POST['minggu_ke_4'])) {
		$uang_sebelum = htmlspecialchars($data['uang_sebelum']);
		$minggu_ke_4 = htmlspecialchars($_POST['minggu_ke_4']);

		// Pastikan $uang_sebelum bukan string kosong
		if (!empty($uang_sebelum)) {
			// Mengkonversi menjadi float untuk pemformatan
			$uang_sebelum_float = (float) $uang_sebelum; 
		} else {
			$uang_sebelum_float = 0; // atau nilai default lainnya
		}

		$query = mysqli_query($conn, "UPDATE uang_kas SET minggu_ke_4 = '$minggu_ke_4' WHERE id_uang_kas = '$id_uang_kas'");

		// Pastikan $minggu_ke_4 juga valid
		if (!empty($minggu_ke_4)) {
			// Mengkonversi menjadi float untuk pemformatan
			$minggu_ke_4_float = (float) $minggu_ke_4;
		} else {
			$minggu_ke_4_float = 0; // atau nilai default lainnya
		}

		// Catatan riwayat dengan pemformatan yang benar
		riwayat($id_user, $id_uang_kas, "telah mengubah pembayaran minggu ke-4 dari Rp. " . number_format($uang_sebelum_float) . " menjadi Rp. " . number_format($minggu_ke_4_float));

  		return mysqli_affected_rows($conn);

	}
}

// Function Hapus Bulan Pembayaran
function deleteBulanPembayaran($id_bulan_pembayaran) {
    global $conn;

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // Hapus data dari tabel uang_kas yang terkait dengan id_bulan_pembayaran
        $queryHapusUangKas = "DELETE FROM uang_kas WHERE id_bulan_pembayaran = ?";
        $stmt = mysqli_prepare($conn, $queryHapusUangKas);
        mysqli_stmt_bind_param($stmt, "i", $id_bulan_pembayaran);
        mysqli_stmt_execute($stmt);

        // Hapus data dari tabel bulan_pembayaran
        $queryHapusBulanPembayaran = "DELETE FROM bulan_pembayaran WHERE id_bulan_pembayaran = ?";
        $stmt = mysqli_prepare($conn, $queryHapusBulanPembayaran);
        mysqli_stmt_bind_param($stmt, "i", $id_bulan_pembayaran);
        mysqli_stmt_execute($stmt);

        // Commit transaksi jika semua query berhasil
        mysqli_commit($conn);

        return mysqli_stmt_affected_rows($stmt); // Mengembalikan jumlah baris yang terpengaruh
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($conn);
        return 0; // Mengembalikan 0 jika ada kesalahan
    }
}

// Function Ubah Password User

function changePassword($data) {
	global $conn;
	$id_user = $_SESSION['id_user'];
	$old_password = htmlspecialchars($data['old_password']);
	// cek password lama
	$dataUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user'"));
	if (password_verify($old_password, $dataUser['password'])) {
		$new_password = htmlspecialchars($data['new_password']);
		$new_password_verify = htmlspecialchars($data['new_password_verify']);
		if ($new_password == $new_password_verify) {
			$password = password_hash($new_password, PASSWORD_DEFAULT);
			$query = mysqli_query($conn, "UPDATE user SET password = '$password' WHERE id_user = '$id_user'");
	  		return mysqli_affected_rows($conn);
		} else {
			setAlert("Failed to change password user!", "New Password not Matches with New Password Verify!", "error");
	     	return header("Location: profile.php");
		}
	} else {
		setAlert("Failed to change password user!", "Old Password not Matches!", "error");
     	return header("Location: profile.php");
	}
}

// Function Tambah Mahasiswa Uang Kas

function tambahSiswaUangKas($data) {
    global $conn;
    $id_siswa = htmlspecialchars($data['id_siswa']);
    $id_bulan_pembayaran = htmlspecialchars($data['id_bulan_pembayaran']);

    // Menentukan kolom yang ingin dimasukkan
    $query = mysqli_query($conn, "INSERT INTO uang_kas (id_siswa, id_bulan_pembayaran, minggu_ke_1, minggu_ke_2, minggu_ke_3, minggu_ke_4) VALUES ('$id_siswa', '$id_bulan_pembayaran', '0', '0', '0', '0')");
    
    return mysqli_affected_rows($conn);
}


// Function Tambah Pengeluaran

function addPengeluaran($data) {
    global $conn;

    // Ambil data dari parameter $data
    $id_user = htmlspecialchars($_SESSION['id_user']);
    $jumlah_pengeluaran = htmlspecialchars($data['jumlah_pengeluaran']);
    $keterangan = htmlspecialchars($data['keterangan']);
    $tanggal_pengeluaran = htmlspecialchars($data['tanggal_pengeluaran']);

    // File upload handling
    $fileName = $_FILES['bukti_transaksi']['name'];
    $fileTmpName = $_FILES['bukti_transaksi']['tmp_name'];
    $fileSize = $_FILES['bukti_transaksi']['size'];
    $fileError = $_FILES['bukti_transaksi']['error'];
    $fileType = $_FILES['bukti_transaksi']['type'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png'];

    // Validasi file upload
    if (!in_array($fileExt, $allowedExt)) {
        echo "File yang diunggah harus berupa gambar (JPG, JPEG, PNG).";
        return false;
    }
    if ($fileSize > 3000000) { // Maksimal 2MB
        echo "Ukuran file terlalu besar. Maksimal 3MB.";
        return false;
    }
    if ($fileError !== 0) {
        echo "Terjadi kesalahan saat mengunggah file.";
        return false;
    }

    // Generate nama file unik dan simpan ke folder uploads/
    $newFileName = uniqid('', true) . "." . $fileExt;
    $uploadDir = "assets/img/bukti_transaksi/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadPath = $uploadDir . $newFileName;

    if (!move_uploaded_file($fileTmpName, $uploadPath)) {
        echo "Gagal menyimpan file ke server.";
        return false;
    }

    // Query untuk menyimpan data pengeluaran
    $query = "INSERT INTO pengeluaran (jumlah_pengeluaran, keterangan, tanggal_pengeluaran, id_user, bukti_transaksi) 
              VALUES ('$jumlah_pengeluaran', '$keterangan', '$tanggal_pengeluaran', '$id_user', '$newFileName')";

    mysqli_query($conn, $query);

    // Log riwayat pengeluaran
    // riwayatPengeluaran($id_user, "telah menambahkan pengeluaran '$keterangan' dengan biaya Rp. " . number_format($jumlah_pengeluaran) . " dan foto $newFileName");

    return mysqli_affected_rows($conn);
}


// Function Edit Pengeluaran

function editPengeluaran($data) {
	global $conn;
	$id_user = htmlspecialchars($_SESSION['id_user']);
	$id_pengeluaran = htmlspecialchars($data['id_pengeluaran']);
	$fetch_sql = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id_pengeluaran = '$id_pengeluaran'"));
	$jumlah_pengeluaran = htmlspecialchars($data['jumlah_pengeluaran']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$tanggal_pengeluaran = time();
	$query = mysqli_query($conn, "UPDATE pengeluaran SET jumlah_pengeluaran = '$jumlah_pengeluaran', keterangan = '$keterangan', tanggal_pengeluaran = '$tanggal_pengeluaran', id_user = '$id_user' WHERE id_pengeluaran = '$id_pengeluaran'");
	riwayatPengeluaran($id_user, "telah mengubah pengeluaran " . $keterangan . " dari biaya Rp. " . number_format($fetch_sql['jumlah_pengeluaran']) . " menjadi Rp. " . number_format($jumlah_pengeluaran));
  	return mysqli_affected_rows($conn);
}

// Function Hapus Pengeluaran

function deletePengeluaran($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_pengeluaran = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}