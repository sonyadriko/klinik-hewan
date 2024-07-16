<?php
include '../config/database.php'; // Adjust the path as needed

if (isset($_GET['id'])) {
    $id_reservasi = $_GET['id'];
    
    // Delete the reservation
    $delete_reservasi = mysqli_query($conn, "DELETE FROM reservasi WHERE id_reservasi='$id_reservasi'");
    
    if ($delete_reservasi) {
        echo "Reservation deleted successfully";
        header('Location: reservasi.php'); // Redirect back to the reservation page
    } else {
        echo "Failed to delete reservation";
    }
} else {
    echo "Invalid request";
}
?>