<?php
session_start();
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    // Redirect to some error page or display an error message
    echo "Invalid request. No reservation ID provided.";
    exit();
}

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tambah Rekam Medis Pasien</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include 'includes/header.php' ?>
        <?php include 'includes/sidebar.php' ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Tambah Rekam Medis Pasien</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Tambah Rekam Medis Pasien</h4>
                            </div>
                            <div class="card-body">
                                <form action="simpan_rekam_medis.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="reservasi_id"
                                        value="<?php echo htmlspecialchars($id); ?>">
                                    <!-- <div class="mb-3">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                    </div> -->
                                    <div class="mb-3">
                                        <label for="berat_badan" class="form-label">Berat Badan</label>
                                        <input type="text" class="form-control" id="berat_badan" name="berat_badan"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="suhu_badan" class="form-label">Suhu Badan</label>
                                        <input type="text" class="form-control" id="suhu_badan" name="suhu_badan"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="anamnesa" class="form-label">Anamnesa</label>
                                        <input type="text" class="form-control" id="anamnesa" name="anamnesa" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pemeriksaan_fisik" class="form-label">Pemeriksaan Fisik</label>
                                        <textarea class="form-control" id="pemeriksaan_fisik" name="pemeriksaan_fisik"
                                            rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="diagnosa" class="form-label">Diagnosa</label>
                                        <textarea class="form-control" id="diagnosa" name="diagnosa" rows="4"
                                            required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="terapi_obat" class="form-label">Terapi/Obat</label>
                                        <textarea class="form-control" id="terapi_obat" name="terapi_obat" rows="4"
                                            required></textarea>
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
</body>

</html>