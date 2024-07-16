<?php
include '../config/database.php'; // Adjust the path as needed

if (isset($_GET['id'])) {
    $id_reservasi = $_GET['id'];
    
    // Update the status to 'proses'
    $update_status = mysqli_query($conn, "UPDATE reservasi SET status='proses' WHERE id_reservasi='$id_reservasi'");
    
    if ($update_status) {
        echo "Status updated successfully";
        header('Location: reservasi.php'); // Redirect back to the reservation page
    } else {
        echo "Failed to update status";
    }
} else {
    echo "Invalid request";
}
?>