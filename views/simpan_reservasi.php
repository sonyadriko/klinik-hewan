<?php
include '../config/database.php';
session_start();

// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');

// Initialize variables
$tanggal_reservasi = $waktu_reservasi = $keluhan = $service_type = "";
$error_message = "";
$success = false;

// Proses tambah reservasi jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tanggal_reservasi'], $_POST['waktu_reservasi'], $_POST['hewan_id'], $_POST['service_type'])) {
        $tanggal_reservasi = $_POST['tanggal_reservasi'];
        $waktu_reservasi = $_POST['waktu_reservasi'];
        $hewan_id = $_POST['hewan_id'];
        $service_type = $_POST['service_type'];
        $user_id = $_SESSION['id_users']; // Menggunakan session untuk mendapatkan id pengguna

        $insert_query = "INSERT INTO reservasi (user_id, hewan_id, tanggal_reservasi, waktu_reservasi, jenis_layanan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iisss", $user_id, $hewan_id, $tanggal_reservasi, $waktu_reservasi, $service_type);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    } else {
        $error_message = "All fields are required.";
    }
}

echo json_encode(array("success" => $success));
?>