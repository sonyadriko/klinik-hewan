<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}
date_default_timezone_set('Asia/Jakarta');
$id_reservasi = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mandi = isset($_POST['mandi']) ? 1 : 0;
    $perawatan_bulu = isset($_POST['perawatan_bulu']) ? 1 : 0;
    $trimming = isset($_POST['trimming']) ? 1 : 0;
    $pembersihan_telinga = isset($_POST['pembersihan_telinga']) ? 1 : 0;
    $inspeksi_kutu = isset($_POST['inspeksi_kutu']) ? 1 : 0;

    $insert_query = "INSERT INTO rekam_medis_grooming (reservasi_id, mandi, perawatan_bulu, trimming, pembersihan_telinga, inspeksi_kutu) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iiiiii", $id_reservasi, $mandi, $perawatan_bulu, $trimming, $pembersihan_telinga, $inspeksi_kutu);
    
    if ($stmt->execute()) {
        $update_query = "UPDATE reservasi SET status = 'selesai' WHERE id_reservasi = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("i", $id_reservasi);
        $update_stmt->execute();
        $update_stmt->close();
        
        $stmt->close();
        $conn->close();
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Success", "Data berhasil disimpan!", "success").then(() => {
                    window.location.href = "rekam-medis-grooming.php";
                });
            });
        </script>';
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Error", "Gagal menyimpan data!", "error");
            });
        </script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tambah Data Grooming</title>
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
                                <strong>Tambah Data Grooming</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tambah Data Laporan Grooming</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="mandi" id="mandi">
                                        <label class="form-check-label" for="mandi">
                                            Mandi
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="perawatan_bulu"
                                            id="perawatan_bulu">
                                        <label class="form-check-label" for="perawatan_bulu">
                                            Perawatan Bulu
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="trimming" id="trimming">
                                        <label class="form-check-label" for="trimming">
                                            Trimming atau potong kuku
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pembersihan_telinga"
                                            id="pembersihan_telinga">
                                        <label class="form-check-label" for="pembersihan_telinga">
                                            Pembersihan Telinga
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="inspeksi_kutu"
                                            id="inspeksi_kutu">
                                        <label class="form-check-label" for="inspeksi_kutu">
                                            Inspeksi Kutu
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
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