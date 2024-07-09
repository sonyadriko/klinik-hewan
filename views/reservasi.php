<?php
include '../config/database.php';
session_start();

// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');

// Initialize variables
$tanggal_reservasi = $waktu_reservasi = $keluhan = $service_type = "";
$error_message = "";
$success = false;

// Proses tambah reservasi jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tanggal_reservasi'], $_POST['waktu_reservasi'], $_POST['keluhan'], $_POST['hewan_id'], $_POST['service_type'])) {
        $tanggal_reservasi = $_POST['tanggal_reservasi'];
        $waktu_reservasi = $_POST['waktu_reservasi'];
        $keluhan = $_POST['keluhan'];
        $hewan_id = $_POST['hewan_id'];
        $service_type = $_POST['service_type'];
        $user_id = $_SESSION['id_users']; // Menggunakan session untuk mendapatkan id pengguna

        $insert_query = "INSERT INTO reservasi (user_id, hewan_id, tanggal_reservasi, waktu_reservasi, keluhan, jenis_layanan) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iissss", $user_id, $hewan_id, $tanggal_reservasi, $waktu_reservasi, $keluhan, $service_type);

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
    <title>Reservasi</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <?php if($_SESSION['role'] == 'pasien'){ ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Reservasi</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Reservasi</h4>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="mb-3">
                                        <label for="tanggal_reservasi" class="form-label">Tanggal Reservasi</label>
                                        <input type="date" class="form-control" id="tanggal_reservasi"
                                            name="tanggal_reservasi" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="waktu_reservasi" class="form-label">Waktu Reservasi</label>
                                        <input type="time" class="form-control" id="waktu_reservasi"
                                            name="waktu_reservasi" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keluhan" class="form-label">Keluhan</label>
                                        <textarea class="form-control" id="keluhan" name="keluhan" rows="3"
                                            required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hewan_id" class="form-label">Pilih Hewan</label>
                                        <select class="form-control" id="hewan_id" name="hewan_id" required>
                                            <?php
                                            $user_id = $_SESSION['id_users'];
                                            $stmt = $conn->prepare("SELECT id_hewan, nama_hewan FROM hewan WHERE users_id = ?");
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['id_hewan'] . "'>" . htmlspecialchars($row['nama_hewan']) . "</option>";
                                            }
                                            $stmt->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="service_type" class="form-label">Jenis Layanan</label>
                                        <select class="form-control" id="service_type" name="service_type" required>
                                            <option value="pemeriksaan">Pemeriksaan</option>
                                            <option value="grooming">Grooming</option>
                                            <option value="pet_hotel">Pet Hotel</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Reservasi</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Reservasi</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="mb-3">
                                        <div class="col-md-2">
                                            <label for="layananFilter">Filter Layanan:</label>
                                            <select id="layananFilter" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="pemeriksaan">Pemeriksaan</option>
                                                <option value="grooming">Grooming</option>
                                                <option value="pet_hotel">Pet Hotel</option>
                                            </select>

                                        </div>
                                    </div>
                                    <table id="reservasiTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemilik</th>
                                                <th>Nama Hewan</th>
                                                <th>Tanggal, Waktu</th>
                                                <th>Jenis Layanan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $get_data = mysqli_query($conn, "SELECT * FROM reservasi JOIN users ON reservasi.user_id = users.id_users JOIN hewan ON reservasi.user_id = hewan.users_id");
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama'];                                            
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $waktu = $display['waktu_reservasi'];
                                                $layanan = $display['jenis_layanan'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $pasien; ?></td>
                                                <td><?php echo $hewan; ?></td>
                                                <td><?php echo $tanggal . ', ' . $waktu; ?></td>
                                                <td><?php echo $layanan; ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href='ubah-artikel.php?id=<?php echo $id; ?>'
                                                            class="btn btn-primary btn-user">Ubah</a>
                                                        <button class="btn btn-danger btn-user delete-btn"
                                                            data-id="<?php echo $id; ?>">Hapus</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $no++;
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <?php } ?>

    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($success) { ?>
        Swal.fire({
            title: 'Sukses',
            text: 'Reservasi berhasil dibuat!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'dashboard.php';
            }
        });
        <?php } ?>
    });
    </script>
    <script>
    $(document).ready(function() {
        var table = $('#reservasiTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "language": {
                "paginate": {
                    "previous": "<i class='bi bi-arrow-left'></i>",
                    "next": "<i class='bi bi-arrow-right'></i>"
                }
            }
        });

        $('#layananFilter').change(function() {
            var layanan = $(this).val();
            table.column(4).search(layanan).draw();
        });
    });
    </script>
</body>

</html>