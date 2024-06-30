<?php
include '../config/database.php';
session_start();

// Initialize response array
$response = array('success' => false, 'message' => 'Unknown error occurred.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['alamat'], $_POST['notelepon'])) {
        // Sanitize input data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = md5($_POST['password']); // You should use stronger hashing methods like bcrypt
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $notelepon = mysqli_real_escape_string($conn, $_POST['notelepon']);
        $role = 'pasien';

        // Check if the email is already registered
        $check_query = "SELECT * FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $response['message'] = 'Email is already registered.';
        } else {
            // Insert new user into database
            $insert_query = "INSERT INTO users (nama, email, password, role, notelp, alamat) VALUES ('$name', '$email', '$password', '$role', '$notelepon', '$alamat')";
            if (mysqli_query($conn, $insert_query)) {
                $response['success'] = true;
                $response['message'] = 'User registered successfully.';
            } else {
                $response['message'] = 'Error: ' . mysqli_error($conn);
            }
        }
    } else {
        $response['message'] = 'All fields are required.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Close database connection
mysqli_close($conn);

// Set content type header
header('Content-Type: application/json');

// Return response as JSON
echo json_encode($response);