<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_teknisi = $_POST['id_teknisi'];
    $nama_teknisi = $_POST['nama_teknisi'];
    $nomor_telpon = $_POST['nomor_telpon'];
    $email_teknisi = $_POST['email_teknisi'];
    $nama_mesin = $_POST['nama_mesin'];
    $lokasi = $_POST['lokasi'];
    $jenis_kerusakan = $_POST['jenis_kerusakan'];
    $deskripsi_kerusakan = $_POST['deskripsi_kerusakan'];
    $foto_path = NULL;

    // Validasi apakah teknisi terdaftar
    $stmt = $conn->prepare("SELECT id_teknisi FROM teknisi WHERE id_teknisi = ? AND nama_teknisi = ? AND nomor_telepon_teknisi = ? AND email_teknisi = ?");
    $stmt->bind_param("ssss", $id_teknisi, $nama_teknisi, $nomor_telpon, $email_teknisi);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $_SESSION['message'] = "Nama anda tidak terdaftar, coba ulangi lagi.";
        $_SESSION['error'] = true;
        header("Location: index.php");
        exit();
    }
    $stmt->close();

    // Cek apakah mesin terdaftar
    $stmtMesin = $conn->prepare("SELECT id_mesin FROM mesin WHERE nama_mesin = ? AND lokasi = ?");
    $stmtMesin->bind_param("ss", $nama_mesin, $lokasi);
    $stmtMesin->execute();
    $stmtMesin->store_result();

    if ($stmtMesin->num_rows == 0) {
        $_SESSION['message'] = "Mesin tidak terdaftar, coba ulangi lagi.";
        $_SESSION['error'] = true;
        header("Location: index.php");
        exit();
    }

    $stmtMesin->bind_result($id_mesin);
    $stmtMesin->fetch();
    $stmtMesin->close();

    // Proses upload foto jika ada
    if (isset($_FILES['foto_kerusakan']) && $_FILES['foto_kerusakan']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $imageFileType = strtolower(pathinfo($_FILES["foto_kerusakan"]["name"], PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES["foto_kerusakan"]["size"] <= 5000000) {
            $new_file_name = uniqid() . "." . $imageFileType;
            $foto_path = $target_dir . $new_file_name;
            move_uploaded_file($_FILES["foto_kerusakan"]["tmp_name"], $foto_path);
        }
    }

    // Simpan ke tabel laporan_kerusakan
    $stmt2 = $conn->prepare("INSERT INTO laporan_kerusakan (id_teknisi, nama_teknisi, nomor_telpon, email_teknisi, id_mesin, nama_mesin, lokasi, jenis_kerusakan, deskripsi_kerusakan, foto_path) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt2->bind_param("ssssssssss", $id_teknisi, $nama_teknisi, $nomor_telpon, $email_teknisi, $id_mesin, $nama_mesin, $lokasi, $jenis_kerusakan, $deskripsi_kerusakan, $foto_path);

    if ($stmt2->execute()) {
        $_SESSION['message'] = "Laporan berhasil dikirim!";
        $_SESSION['error'] = false;
    } else {
        $_SESSION['message'] = "Gagal menyimpan laporan: " . $stmt2->error;
        $_SESSION['error'] = true;
    }

    $stmt2->close();
    $conn->close();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
