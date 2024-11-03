<?php
include '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $reservasi_id = $_POST['reservasi_id'];
    $tanggal_invoice = $_POST['tanggal_invoice'];
    $jatuh_tempo = $_POST['jatuh_tempo'];
    $items = $_POST['item'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];
    $total = $_POST['total'];

    // Insert into the invoice table
    $query = "INSERT INTO invoice (id_reservasi, tanggal_invoice, jatuh_tempo) VALUES ('$reservasi_id', '$tanggal_invoice', '$jatuh_tempo')";
    if (mysqli_query($conn, $query)) {
        // Get the last inserted invoice ID
        $id_invoice = mysqli_insert_id($conn);

        // Insert items into the detail_invoice table
        for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $jumlah_item = $jumlah[$i];
            $harga_item = $harga[$i];
            $total_item = $total[$i];

            $query_detail = "INSERT INTO detail_invoice (id_invoice, item, jumlah, harga, total) VALUES ('$id_invoice', '$item', '$jumlah_item', '$harga_item', '$total_item')";
            mysqli_query($conn, $query_detail);
        }

        // Success message
        echo "<script>alert('Invoice berhasil dibuat!'); window.location.href = 'reservasi.php';</script>";
    } else {
        // Error handling
        echo "<script>alert('Gagal membuat invoice!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href = 'reservasi.php';</script>";
}