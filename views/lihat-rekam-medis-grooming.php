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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Lihat Rekam Medis Grooming</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
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
                                <strong>Lihat Rekam Medis Grooming</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Detail Laporan Grooming</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Tanggal Reservasi</th>
                                        <td><?php echo $data['tanggal_reservasi']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Pemilik</th>
                                        <td><?php echo $data['nama_pemilik']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Hewan</th>
                                        <td><?php echo $data['nama_hewan']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Hewan</th>
                                        <td><?php echo $data['jenis_hewan']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mandi</th>
                                        <td><?php echo $data['mandi'] ? 'Ya' : 'Tidak'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Perawatan Bulu</th>
                                        <td><?php echo $data['perawatan_bulu'] ? 'Ya' : 'Tidak'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Trimming atau Potong Kuku</th>
                                        <td><?php echo $data['trimming'] ? 'Ya' : 'Tidak'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Pembersihan Telinga</th>
                                        <td><?php echo $data['pembersihan_telinga'] ? 'Ya' : 'Tidak'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Inspeksi Kutu</th>
                                        <td><?php echo $data['inspeksi_kutu'] ? 'Ya' : 'Tidak'; ?></td>
                                    </tr>
                                </table>
                                <a href="rekam-medis-grooming.php" class="btn btn-secondary mt-3">Kembali</a>
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