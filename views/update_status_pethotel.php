<?php
include '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Update the status of the reservation
        $update_query = "UPDATE reservasi SET status = 'check out' WHERE id_reservasi = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            // Redirect back to the main page with a success message
            header("Location: rekam-medis-pethotel.php?message=checkout_success");
            exit();
        } else {
            $stmt->close();
            // Redirect back to the main page with an error message
            header("Location: rekam-medis-pethotel.php?message=checkout_error");
            exit();
        }
    } else {
        // Redirect back to the main page with an error message
        header("Location: rekam-medis-pethotel.php?message=checkout_error");
        exit();
    }
} else {
    // Redirect back to the main page if the request method is not POST
    header("Location: rekam-medis-pethotel.php");
    exit();
}
?>