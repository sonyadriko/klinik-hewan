<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}
date_default_timezone_set('Asia/Jakarta');
$id_reservasi = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT reservasi.*, users.nama AS nama_pemilik, hewan.nama_hewan, hewan.jenis_hewan, rekam_medis_grooming.* 
          FROM reservasi 
          LEFT JOIN users ON reservasi.user_id = users.id_users 
          LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan 
          LEFT JOIN rekam_medis_grooming ON reservasi.id_reservasi = rekam_medis_grooming.reservasi_id 
          WHERE reservasi.id_reservasi = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_reservasi);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update query
    $mandi = isset($_POST['mandi']) ? 1 : 0;
    $perawatan_bulu = isset($_POST['perawatan_bulu']) ? 1 : 0;
    $trimming = isset($_POST['trimming']) ? 1 : 0;
    $pembersihan_telinga = isset($_POST['pembersihan_telinga']) ? 1 : 0;
    $inspeksi_kutu = isset($_POST['inspeksi_kutu']) ? 1 : 0;

    $update_query = "UPDATE rekam_medis_grooming 
                     SET mandi = ?, perawatan_bulu = ?, trimming = ?, pembersihan_telinga = ?, inspeksi_kutu = ? 
                     WHERE reservasi_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iiiiii", $mandi, $perawatan_bulu, $trimming, $pembersihan_telinga, $inspeksi_kutu, $id_reservasi);
    if ($update_stmt->execute()) {
        echo "<script>
                alert('Data berhasil diperbarui');
                window.location.href = 'rekam-medis-grooming.php';
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Data Grooming</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                                <strong>Edit Data Grooming</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Laporan Grooming</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <input type="checkbox" name="mandi" <?php echo $data['mandi'] ? 'checked' : ''; ?> >
                                        <label for="mandi">Mandi</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="perawatan_bulu" <?php echo $data['perawatan_bulu'] ? 'checked' : ''; ?> >
                                        <label for="perawatan_bulu">Perawatan Bulu</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="trimming" <?php echo $data['trimming'] ? 'checked' : ''; ?> >
                                        <label for="trimming">Trimming atau Potong Kuku</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="pembersihan_telinga" <?php echo $data['pembersihan_telinga'] ? 'checked' : ''; ?> >
                                        <label for="pembersihan_telinga">Pembersihan Telinga</label>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="checkbox" name="inspeksi_kutu" <?php echo $data['inspeksi_kutu'] ? 'checked' : ''; ?> >
                                        <label for="inspeksi_kutu">Inspeksi Kutu</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="rekam-medis-grooming.php" class="btn btn-info">Kembali</a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>
