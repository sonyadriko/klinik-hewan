<?php
include '../config/database.php';

header('Content-Type: application/json'); // Tambahkan header JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_produk']) && is_numeric($_POST['id_produk'])) {
        $id_produk = intval($_POST['id_produk']); // Validasi dan konversi ke integer

        // Debug log
        error_log("Received ID: " . $id_produk);

        // Fetch the file path before deleting the product
        if ($stmt = $conn->prepare("SELECT foto_produk FROM produk WHERE id_produk = ?")) {
            $stmt->bind_param("i", $id_produk);
            $stmt->execute();
            $stmt->bind_result($foto_produk);
            $stmt->fetch();
            $stmt->close();

            // Debug log for fetched photo path
            error_log("Fetched photo path: " . $foto_produk);

            // Delete the product
            if ($stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?")) {
                $stmt->bind_param("i", $id_produk);

                // Execute the statement
                if ($stmt->execute()) {
                    // Delete the photo file from the server
                    $file_path = '../uploads/produk/' . $foto_produk;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                        error_log("File deleted: " . $file_path);
                    } else {
                        error_log("File not found: " . $file_path);
                    }

                    echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus.']);
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
            // Debug log
            error_log("Prepare failed: " . $conn->error);
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid produk ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the connection
$conn->close();