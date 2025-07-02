<?php
// update_status.php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $new_status = $_POST['new_status'];

    // Validasi status baru
    $allowed_statuses = ['Menunggu', 'Ditangani', 'Selesai'];
    if (!in_array($new_status, $allowed_statuses)) {
        $_SESSION['message'] = "Status tidak valid.";
        header("Location: dashboard.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE laporan_kerusakan_mesin SET status_perbaikan = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $report_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status laporan berhasil diperbarui.";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>