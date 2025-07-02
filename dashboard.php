<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan Kerusakan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard Laporan Kerusakan Mesin</h2>
        <p><a href="index.php">Buat Laporan Baru</a></p>

        <form action="" method="GET" class="filter-form">
            <label for="status_filter">Filter Status:</label>
            <select name="status_filter" id="status_filter" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Menunggu" <?php if(isset($_GET['status_filter']) && $_GET['status_filter'] == 'Menunggu') echo 'selected'; ?>>Menunggu</option>
                <option value="Ditangani" <?php if(isset($_GET['status_filter']) && $_GET['status_filter'] == 'Ditangani') echo 'selected'; ?>>Ditangani</option>
                <option value="Selesai" <?php if(isset($_GET['status_filter']) && $_GET['status_filter'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
            </select>
        </form>

        <div class="reports-list">
            <?php
            include 'config.php';

            $where_clause = "";
            if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
                $status_filter = $conn->real_escape_string($_GET['status_filter']);
                $where_clause = " WHERE status_perbaikan = '$status_filter'";
            }

            $sql = "SELECT * FROM laporan_kerusakan_mesin" . $where_clause . " ORDER BY tanggal_laporan DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="report-item">';
                    echo '<h3>Laporan ID: ' . $row["id"] . '</h3>';
                    echo '<p><strong>Nama Mesin:</strong> ' . htmlspecialchars($row["nama_mesin"]) . '</p>';
                    echo '<p><strong>Lokasi:</strong> ' . htmlspecialchars($row["lokasi"]) . '</p>';
                    echo '<p><strong>Jenis Kerusakan:</strong> ' . htmlspecialchars($row["jenis_kerusakan"]) . '</p>';
                    echo '<p><strong>Deskripsi:</strong> ' . nl2br(htmlspecialchars($row["deskripsi_kerusakan"])) . '</p>';
                    echo '<p><strong>Tanggal Laporan:</strong> ' . $row["tanggal_laporan"] . '</p>';
                    echo '<p class="status ' . strtolower($row["status_perbaikan"]) . '"><strong>Status Perbaikan:</strong> ' . $row["status_perbaikan"] . '</p>';
                    if ($row["foto_path"]) {
                        echo '<p><strong>Foto:</strong> <a href="' . htmlspecialchars($row["foto_path"]) . '" target="_blank">Lihat Foto</a></p>';
                        echo '<img src="' . htmlspecialchars($row["foto_path"]) . '" alt="Foto Kerusakan" class="report-image">';
                    }
                    echo '<form action="update_status.php" method="POST" class="status-form">';
                    echo '<input type="hidden" name="report_id" value="' . $row["id"] . '">';
                    echo '<label for="status_' . $row["id"] . '">Ubah Status:</label>';
                    echo '<select name="new_status" id="status_' . $row["id"] . '">';
                    echo '<option value="Menunggu" ' . ($row["status_perbaikan"] == "Menunggu" ? "selected" : "") . '>Menunggu</option>';
                    echo '<option value="Ditangani" ' . ($row["status_perbaikan"] == "Ditangani" ? "selected" : "") . '>Ditangani</option>';
                    echo '<option value="Selesai" ' . ($row["status_perbaikan"] == "Selesai" ? "selected" : "") . '>Selesai</option>';
                    echo '</select>';
                    echo '<button type="submit">Ubah</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>Belum ada laporan kerusakan.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>