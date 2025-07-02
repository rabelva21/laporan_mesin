<?php
// submit_report.php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_mesin = $_POST['nama_mesin'];
    $lokasi = $_POST['lokasi'];
    $jenis_kerusakan = $_POST['jenis_kerusakan'];
    $deskripsi_kerusakan = $_POST['deskripsi_kerusakan'];

    $foto_path = NULL; // Default value if no photo is uploaded

    // Proses unggah foto
    if (isset($_FILES['foto_kerusakan']) && $_FILES['foto_kerusakan']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Buat folder jika belum ada
        }

        $target_file = $target_dir . basename($_FILES["foto_kerusakan"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Cek apakah file gambar asli atau palsu
        $check = getimagesize($_FILES["foto_kerusakan"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['message'] = "File bukan gambar.";
            $uploadOk = 0;
        }

        // Cek ukuran file (maks 5MB)
        if ($_FILES["foto_kerusakan"]["size"] > 5000000) {
            $_SESSION['message'] = "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Izinkan format file tertentu
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $_SESSION['message'] = "Maaf, hanya format JPG, JPEG, PNG & GIF yang diizinkan.";
            $uploadOk = 0;
        }

        // Cek jika $uploadOk adalah 0
        if ($uploadOk == 0) {
            header("Location: index.php");
            exit();
        } else {
            // Ubah nama file untuk menghindari duplikasi
            $new_file_name = uniqid() . "." . $imageFileType;
            $foto_path = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES["foto_kerusakan"]["tmp_name"], $foto_path)) {
                // File berhasil diunggah
            } else {
                $_SESSION['message'] = "Maaf, ada kesalahan saat mengunggah file Anda.";
                header("Location: index.php");
                exit();
            }
        }
    }

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO laporan_kerusakan_mesin (nama_mesin, lokasi, jenis_kerusakan, deskripsi_kerusakan, foto_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_mesin, $lokasi, $jenis_kerusakan, $deskripsi_kerusakan, $foto_path);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Laporan kerusakan berhasil dikirim!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>