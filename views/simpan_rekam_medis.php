<?php
include '../config/database.php';
session_start();
// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['berat_badan'], $_POST['suhu_badan'], $_POST['anamnesa'], $_POST['pemeriksaan_fisik'], $_POST['diagnosa'], $_POST['terapi_obat'], $_POST['reservasi_id'])) {
        $berat_badan = $_POST['berat_badan'];
        $suhu_badan = $_POST['suhu_badan'];
        $anamnesa = $_POST['anamnesa'];
        $pemeriksaan_fisik = $_POST['pemeriksaan_fisik'];
        $diagnosa = $_POST['diagnosa'];
        $terapi_obat = $_POST['terapi_obat'];
        $created_at = date('Y-m-d H:i:s'); // Get the current date and time
        $reservasi_id = $_POST['reservasi_id'];
        $rawatinap = isset($_POST['rawat_inap']) ? 1 : 0; // Set value based on checkbox

        // Insert data into database
        $insert_query = "INSERT INTO rekam_medis (reservasi_id, berat_badan, suhu_badan, anamnesa, pemeriksaan_fisik, diagnosa, terapi_obat, rawat_inap, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("issssssss", $reservasi_id, $berat_badan, $suhu_badan, $anamnesa, $pemeriksaan_fisik, $diagnosa, $terapi_obat, $rawatinap, $created_at);

        if ($stmt->execute()) {
            $update_query = "UPDATE reservasi SET status = 'selesai' WHERE id_reservasi = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $reservasi_id); // Assuming id is an integer
            $update_stmt->execute();
            $update_stmt->close();
            
            $stmt->close();
            $conn->close();
            // Redirect to the page where the medical records are listed
            header("Location: rekam-medis.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All fields are required.";
    }
}
?>