<?php
include '../config/database.php';
session_start();

// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');

// Initialize variables
$nama = $email = $alamat = $notelp = "";
$error_message = "";
$success = false;

// Fetch user details if session is set
if (isset($_SESSION['id_users'])) {
    $id_users = $_SESSION['id_users'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id_users = ?");
    $stmt->bind_param("i", $id_users);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nama = $row['nama'];
        $email = $row['email'];
        $alamat = $row['alamat'];
        $notelp = $row['notelp'];
    } else {
        $error_message = "Data pengguna tidak ditemukan.";
    }
    $stmt->close();
}

// Proses ubah profile jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nama'], $_POST['email'], $_POST['alamat'], $_POST['notelp'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $alamat = $_POST['alamat'];
        $notelp = $_POST['notelp'];

        $update_query = "UPDATE users SET nama = ?, email = ?, alamat = ?, notelp = ? WHERE id_users = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssii", $nama, $email, $alamat, $notelp, $id_users);

        if ($stmt->execute()) {
            $success = true;
            $stmt->close();
            $conn->close();
            // Update session variables
            $_SESSION['nama'] = $nama;
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Profile</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Edit Profile</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($error_message) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                                <?php } ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="<?php echo htmlspecialchars($nama); ?>"
                                            placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>"
                                            placeholder="Masukkan email Anda" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            value="<?php echo htmlspecialchars($alamat); ?>"
                                            placeholder="Masukkan alamat lengkap" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="notelp" class="form-label">Nomor Telepon</label>
                                        <input type="text" class="form-control" id="notelp" name="notelp"
                                            value="<?php echo htmlspecialchars($notelp); ?>"
                                            placeholder="Masukkan nomor telepon" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($success) { ?>
        Swal.fire({
            title: 'Sukses',
            text: 'Data profil berhasil diubah!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'profile.php';
            }
        });
        <?php } ?>
    });
    </script>
</body>

</html>