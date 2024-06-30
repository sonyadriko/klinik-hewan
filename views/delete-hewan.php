<?php
include '../config/database.php';

header('Content-Type: application/json'); // Tambahkan header JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_hewan']) && is_numeric($_POST['id_hewan'])) {
        $id_hewan = intval($_POST['id_hewan']); // Validasi dan konversi ke integer

        // Debug log
        error_log("Received ID: " . $id_hewan);

        // Prepare the SQL statement to avoid SQL injection
        if ($stmt = $conn->prepare("DELETE FROM hewan WHERE id_hewan = ?")) {
            $stmt->bind_param("i", $id_hewan);

            // Execute the statement
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Hewan berhasil dihapus.']);
            } else {
                // Debug log
                error_log("Error: " . $stmt->error);
                echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
            }

            // Close the statement
            $stmt->close();
        } else {
            // Debug log
            error_log("Prepare failed: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid artikel ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the connection
$conn->close();