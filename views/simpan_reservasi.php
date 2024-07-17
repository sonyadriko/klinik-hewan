<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/database.php';

$response = ['status' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_reservasi = $_POST['tanggal_reservasi'];
    $slot_reservasi = $_POST['slot_reservasi'];
    $hewan_id = $_POST['hewan_id'];
    $service_type = $_POST['service_type'];
    $user_id = $_SESSION['id_users'];
    $status = 'pending';

    // Check slot availability
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reservasi WHERE tanggal_reservasi = ? AND slot_reservasi = ? AND jenis_layanan = ?");
    if ($stmt === false) {
        $response['status'] = 'error';
        $response['message'] = "Error preparing statement: " . htmlspecialchars($conn->error);
        echo json_encode($response);
        exit;
    }
    $stmt->bind_param("sss", $tanggal_reservasi, $slot_reservasi, $service_type);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        $response['status'] = 'error';
        $response['message'] = "Error executing statement: " . htmlspecialchars($stmt->error);
        echo json_encode($response);
        exit;
    }
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['count'] < 4) { // Assuming 4 slots per time period
        // Slot is available, proceed with reservation
        $stmt = $conn->prepare("INSERT INTO reservasi (tanggal_reservasi, slot_reservasi, hewan_id, jenis_layanan, user_id, status) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            $response['status'] = 'error';
            $response['message'] = "Error preparing statement: " . htmlspecialchars($conn->error);
            echo json_encode($response);
            exit;
        }
        $stmt->bind_param("ssssis", $tanggal_reservasi, $slot_reservasi, $hewan_id, $service_type, $user_id, $status);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Reservasi Berhasil!";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error executing statement: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    } else {
        // Slot is not available
        $response['status'] = 'error';
        $response['message'] = "Slot yang dipilih sudah penuh. Silakan pilih slot lain.";
    }

    $conn->close();
    echo json_encode($response);
}
?>