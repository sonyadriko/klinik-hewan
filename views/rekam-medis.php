<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Rekam Medis</title>
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
                                <strong> Rekam Medis</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Rekam Medis</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="reservasiTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pemilik</th>
                                                <th>Nama Hewan</th>
                                                <th>Jenis Hewan</th>
                                                <th>Layanan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $get_data = mysqli_query($conn, "SELECT * FROM reservasi LEFT JOIN users ON reservasi.user_id = users.id_users LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan WHERE reservasi.status = 'proses'");
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama'];                                            
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $waktu = $display['waktu_reservasi'];
                                                $layanan = $display['jenis_layanan'];
                                                $jenis_hewan = $display['jenis_hewan'];
                                                if ($layanan === 'pet_hotel') {
                                                    $layanan = 'Pet Hotel';
                                                }
                                                $slot = $display['slot_reservasi'];

                                                $check_query = "SELECT * FROM rekam_medis WHERE reservasi_id = ?";
                                                $stmt = $conn->prepare($check_query);
                                                $stmt->bind_param("i", $id);
                                                $stmt->execute();
                                                $record_result = $stmt->get_result();
                                                $record_exists = $record_result->num_rows > 0;
                                                $stmt->close();

                                            ?>


                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $pasien; ?></td>
                                                <td><?php echo $hewan; ?></td>
                                                <td><?php echo $jenis_hewan; ?></td>
                                                <td><?php echo ucwords($layanan); ?></td>
                                                <td>
                                                    <div class="card-body">
                                                        <div class="action-buttons">
                                                            <?php if ($record_exists): ?>
                                                            <a href="lihat-rekam-medis.php?id=<?php echo urlencode($id); ?>"
                                                                class="btn btn-success btn-user">Lihat Laporan</a>
                                                            <?php else: ?>
                                                            <a href="tambah-rekam-medis.php?id=<?php echo urlencode($id); ?>"
                                                                class="btn btn-info btn-user">Isi Laporan</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                $no++;
            }
            // $previous_hewan = $hewan; // Simpan nama hewan sebelumnya untuk perbandingan selanjutnya
        // }
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