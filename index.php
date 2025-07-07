<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Laporan Kerusakan Mesin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .message.success {
            background-color: #e6f9e6;
            color: #155724;
            border: 1px solid #a3e6a3;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .message.error {
            background-color: #ffe0e0;
            color: #b30000;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .upload-button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #b30000;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-button:hover {
            background-color: #800000;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Formulir Laporan Kerusakan Mesin</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message <?= $_SESSION['error'] ? 'error' : 'success' ?>">
            <?= $_SESSION['message']; ?>
        </p>
        <?php unset($_SESSION['message'], $_SESSION['error']); ?>
    <?php endif; ?>

    <form action="submit_report.php" method="POST" enctype="multipart/form-data">
        <label for="id_teknisi">ID Teknisi (4 digit):</label>
        <input type="text" id="id_teknisi" name="id_teknisi" maxlength="4" required>

        <label for="nama_teknisi">Nama Teknisi:</label>
        <input type="text" id="nama_teknisi" name="nama_teknisi" required>

        <label for="nomor_telpon">Nomor Telepon:</label>
        <input type="text" id="nomor_telpon" name="nomor_telpon" required>

        <label for="email_teknisi">Email Teknisi:</label>
        <input type="text" id="email_teknisi" name="email_teknisi" required>

        <label for="nama_mesin">Nama Mesin:</label>
        <input type="text" id="nama_mesin" name="nama_mesin" required>

        <label for="lokasi">Lokasi Mesin:</label>
        <input type="text" id="lokasi" name="lokasi" required>

        <label for="jenis_kerusakan">Jenis Kerusakan:</label>
        <input type="text" id="jenis_kerusakan" name="jenis_kerusakan" required>

        <label for="deskripsi_kerusakan">Deskripsi Kerusakan:</label>
        <textarea id="deskripsi_kerusakan" name="deskripsi_kerusakan" rows="5"></textarea>

        <label for="foto_kerusakan">Upload Foto Kerusakan:</label>
        <input type="file" id="foto_kerusakan" name="foto_kerusakan" accept="image/*" hidden>
        <label for="foto_kerusakan" class="upload-button">Upload Foto</label>
        <span id="file-name" style="display:block; margin-bottom:20px; font-size:0.9rem;"></span>

        <button type="submit">Kirim Laporan</button>
    </form>
    <p><a href="login.php">Lihat Riwayat Laporan</a></p>
</div>

<script>
    document.getElementById('foto_kerusakan').addEventListener('change', function () {
        const fileName = this.files[0] ? this.files[0].name : "Tidak ada file dipilih";
        document.getElementById('file-name').textContent = fileName;
    });
</script>
</body>
</html>
