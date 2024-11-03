<?php
include '../config/database.php';
session_start();

// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>History Reservasi</title>
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
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> History Reservasi</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data History Reservasi</h4>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $user = $_SESSION['id_users'];
                                            $get_data = mysqli_query($conn, "SELECT * FROM reservasi JOIN users ON reservasi.user_id = users.id_users JOIN hewan ON reservasi.hewan_id = hewan.id_hewan WHERE user_id = $user");
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama'];                                            
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $waktu = $display['waktu_reservasi'];
                                                $slot = $display['slot_reservasi'];
                                                $layanan = $display['jenis_layanan'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $pasien; ?></td>
                                                <td><?php echo $hewan; ?></td>
                                                <td><?php echo $tanggal . ', ' . ($slot === 'pet_hotel' ? 'Pet Hotel' : ($slot === 'grooming_pagi' ? 'Grooming Pagi' : ($slot === 'grooming_sore' ? 'Grooming Sore' : ($slot === 'sore' ? 'Sore' : ($slot === 'pagi' ? 'Pagi' : $slot))))); ?>
                                                </td>
                                                <td><?php echo $layanan === 'pet_hotel' ? 'Pet Hotel' : ($layanan === 'pemeriksaan' ? 'Pemeriksaan' : ($layanan === 'grooming' ? 'Grooming' : $layanan)); ?>
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