<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Jakarta');
$id_reservasi = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT reservasi.*, users.nama AS nama_pemilik, hewan.nama_hewan, hewan.jenis_hewan, rekam_medis.* 
          FROM reservasi 
          LEFT JOIN users ON reservasi.user_id = users.id_users 
          LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan 
          LEFT JOIN rekam_medis ON reservasi.id_reservasi = rekam_medis.reservasi_id 
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $berat_badan = $_POST['berat_badan'];
    $suhu_badan = $_POST['suhu_badan'];
    $anamnesa = $_POST['anamnesa'];
    $pemeriksaan_fisik = $_POST['pemeriksaan_fisik'];
    $diagnosa = $_POST['diagnosa'];
    $terapi_obat = $_POST['terapi_obat'];
    $rawat_inap = isset($_POST['rawat_inap']) ? 1 : 0;

    $update_query = "UPDATE rekam_medis SET berat_badan = ?, suhu_badan = ?, anamnesa = ?, pemeriksaan_fisik = ?, diagnosa = ?, terapi_obat = ?, rawat_inap = ? WHERE reservasi_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssii", $berat_badan, $suhu_badan, $anamnesa, $pemeriksaan_fisik, $diagnosa, $terapi_obat, $rawat_inap, $id_reservasi);

    if ($stmt->execute()) {
        header("Location: lihat-rekam-medis.php?id=" . $id_reservasi);
        exit();
    } else {
        echo "Gagal memperbarui data!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Rekam Medis Grooming</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
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
                            <p>Edit <strong>Rekam Medis Pemeriksaan</strong></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Laporan Pemeriksaan</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label>Berat Badan</label>
                                        <input type="text" name="berat_badan" class="form-control" value="<?php echo $data['berat_badan']; ?>" required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Suhu Badan</label>
                                        <input type="text" name="suhu_badan" class="form-control" value="<?php echo $data['suhu_badan']; ?>" required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Anamnesa</label>
                                        <textarea name="anamnesa" class="form-control" required><?php echo $data['anamnesa']; ?></textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Pemeriksaan Fisik</label>
                                        <textarea name="pemeriksaan_fisik" class="form-control" required><?php echo $data['pemeriksaan_fisik']; ?></textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Diagnosa</label>
                                        <input type="text" name="diagnosa" class="form-control" value="<?php echo $data['diagnosa']; ?>" required>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Terapi Obat</label>
                                        <input type="text" name="terapi_obat" class="form-control" value="<?php echo $data['terapi_obat']; ?>" required>
                                    </div>

                                    <div class="form-group mt-3 mb-3">
                                        <label>Rawat Inap</label>
                                        <input type="checkbox" name="rawat_inap" <?php echo $data['rawat_inap'] ? 'checked' : ''; ?> />
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <a href="lihat-rekam-medis.php?id=<?php echo $data['id_reservasi']; ?>" class="btn btn-info">Batal</a>
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
