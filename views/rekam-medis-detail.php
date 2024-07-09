<?php
include '../config/database.php';
session_start();

// Mendapatkan nama hewan dari URL
$nama_hewan = urldecode($_GET['hewan']);

// Query untuk mendapatkan semua detail reservasi berdasarkan nama hewan
$get_data = mysqli_query($conn, "SELECT reservasi.*, users.nama AS nama_pemilik, hewan.nama_hewan, hewan.jenis_hewan
                                FROM reservasi 
                                JOIN users ON reservasi.user_id = users.id_users 
                                JOIN hewan ON reservasi.hewan_id = hewan.id_hewan
                                WHERE hewan.nama_hewan = '$nama_hewan'");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Rekam Medis</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
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

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Detail Rekam Medis</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Detail Rekam Medis</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="detailReservasiTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemilik</th>
                                                <th>Nama Hewan</th>
                                                <th>Jenis Hewan</th>
                                                <th>Tanggal Reservasi</th>
                                                <th>Waktu Reservasi</th>
                                                <th>Jenis Layanan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $nama_pemilik = $display['nama_pemilik'];                                            
                                                $nama_hewan = $display['nama_hewan'];
                                                $jenis_hewan = $display['jenis_hewan'];
                                                $tanggal_reservasi = $display['tanggal_reservasi'];
                                                $waktu_reservasi = $display['waktu_reservasi'];
                                                $jenis_layanan = $display['jenis_layanan'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $nama_pemilik; ?></td>
                                                <td><?php echo $nama_hewan; ?></td>
                                                <td><?php echo $jenis_hewan; ?></td>
                                                <td><?php echo $tanggal_reservasi; ?></td>
                                                <td><?php echo $waktu_reservasi; ?></td>
                                                <td><?php echo $jenis_layanan; ?></td>
                                            </tr>
                                            <?php
                                            $no++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="rekam-medis.php" class="btn btn-danger">Kembali</a>
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#detailReservasiTable').DataTable({
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
    });
    </script>
</body>

</html>