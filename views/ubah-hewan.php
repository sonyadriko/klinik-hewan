<?php
include '../config/database.php';
session_start();

// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
// Initialize variables
$nama_hewan = $jenis_kelamin = $jenis_hewan = "";
$error_message = "";
$success = false;

// Fetch animal details if ID is provided
if (isset($_GET['id'])) {
    $id_hewan = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM hewan WHERE id_hewan = ? AND users_id = ?");
    $stmt->bind_param("ii", $id_hewan, $_SESSION['id_users']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nama_hewan = $row['nama_hewan'];
        $jenis_kelamin = $row['jenis_kelamin'];
        $jenis_hewan = $row['jenis_hewan'];
        $ras_hewan = $row['ras_hewan'];
    } else {
        $error_message = "Data hewan tidak ditemukan.";
    }
    $stmt->close();
}

// Proses ubah hewan jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nama_hewan'], $_POST['jenis_kelamin'], $_POST['jenis_hewan'], $_POST['id_hewan'], $_POST['ras_hewan'])) {
        $id_hewan = $_POST['id_hewan'];
        $nama_hewan = $_POST['nama_hewan'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $jenis_hewan = $_POST['jenis_hewan'];
        $ras_hewan = $_POST['ras_hewan'];
        $users_id = $_SESSION['id_users']; // Menggunakan session untuk mendapatkan id pengguna

        $update_query = "UPDATE hewan SET nama_hewan = ?, jenis_kelamin = ?, jenis_hewan = ?, ras_hewan = ? WHERE id_hewan = ? AND users_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssii", $nama_hewan, $jenis_kelamin, $jenis_hewan, $ras_hewan, $id_hewan, $users_id);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
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
    <title>Ubah Hewan</title>
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
                                <strong> Ubah Hewan</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Ubah Hewan</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($error_message) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                                <?php } ?>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <input type="hidden" name="id_hewan" value="<?php echo $id_hewan; ?>">
                                    <div class="mb-3">
                                        <label for="nama_hewan" class="form-label">Nama Hewan</label>
                                        <input type="text" class="form-control" id="nama_hewan" name="nama_hewan"
                                            value="<?php echo htmlspecialchars($nama_hewan); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="Jantan"
                                                <?php echo $jenis_kelamin == 'Jantan' ? 'selected' : ''; ?>>Jantan
                                            </option>
                                            <option value="Betina"
                                                <?php echo $jenis_kelamin == 'Betina' ? 'selected' : ''; ?>>Betina
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis_hewan" class="form-label">Jenis Hewan</label>
                                        <select class="form-control" id="jenis_hewam" name="jenis_hewan" required>
                                            <option value="Kucing"
                                                <?php echo $jenis_kelamin == 'Kucing' ? 'selected' : ''; ?>>Kucing
                                            </option>
                                            <option value="Anjing"
                                                <?php echo $jenis_kelamin == 'Anjing' ? 'selected' : ''; ?>>Anjing
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ras_hewan" class="form-label">Ras Hewan</label>
                                        <input type="text" class="form-control" id="ras_hewan" name="ras_hewan"
                                            value="<?php echo htmlspecialchars($ras_hewan); ?>" required>
                                    </div> <button type="submit" class="btn btn-primary">Simpan</button>
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
            text: 'Data hewan berhasil diubah!',
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