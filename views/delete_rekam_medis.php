<?php
include '../config/database.php'; // Include your database configuration
session_start();

if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the DELETE statement
    $delete_query = "DELETE FROM reservasi WHERE id_reservasi = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    
    // Execute the query
    if ($stmt->execute()) {
        // If successful, redirect to the page with the table
        header("Location: rekam-medis.php?status=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    $stmt->close();
} else {
    // Redirect to the page if no ID is found
    header("Location: rekam-medis.php");
}

$conn->close();
?>