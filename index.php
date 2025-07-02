<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Laporan Kerusakan Mesin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Formulir Laporan Kerusakan Mesin</h2>
        <?php
        session_start();
        if (isset($_SESSION['message'])) {
            echo '<p class="message">' . $_SESSION['message'] . '</p>';
            unset($_SESSION['message']);
        }
        ?>
        <form action="submit_report.php" method="POST" enctype="multipart/form-data">
            <label for="nama_mesin">Nama Mesin:</label>
            <input type="text" id="nama_mesin" name="nama_mesin" required>

            <label for="lokasi">Lokasi Mesin:</label>
            <input type="text" id="lokasi" name="lokasi" required>

            <label for="jenis_kerusakan">Jenis Kerusakan:</label>
            <input type="text" id="jenis_kerusakan" name="jenis_kerusakan" required>

            <label for="deskripsi_kerusakan">Deskripsi Kerusakan:</label>
            <textarea id="deskripsi_kerusakan" name="deskripsi_kerusakan" rows="5"></textarea>

            <label for="foto_kerusakan">Upload Foto Kerusakan:</label>
            <input type="file" id="foto_kerusakan" name="foto_kerusakan" accept="image/*">

            <button type="submit">Kirim Laporan</button>
        </form>
        <p><a href="dashboard.php">Lihat Dashboard Laporan</a></p>
    </div>
</body>
</html>