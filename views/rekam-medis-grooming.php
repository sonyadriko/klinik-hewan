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
    <title>Data Grooming</title>
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
                                <strong> Data Grooming</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Grooming</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="reservasiTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nama Pemilik</th>
                                                <th>Nama Hewan</th>
                                                <th>Jenis Hewan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $query = "
                                                SELECT r.id_reservasi, r.tanggal_reservasi, u.nama AS nama_pemilik, h.nama_hewan, h.jenis_hewan
                                                FROM reservasi r
                                                INNER JOIN users u ON r.user_id = u.id_users
                                                INNER JOIN hewan h ON r.hewan_id = h.id_hewan
                                                WHERE r.status = 'proses' OR r.status = 'selesai' AND r.jenis_layanan = 'grooming'
                                                ORDER BY r.tanggal_reservasi DESC
                                            ";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($display = $result->fetch_assoc()) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama_pemilik'];
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $jenis_hewan = $display['jenis_hewan'];

                                                $check_query = "SELECT * FROM rekam_medis_grooming WHERE reservasi_id = ?";
                                                $check_stmt = $conn->prepare($check_query);
                                                $check_stmt->bind_param("i", $id);
                                                $check_stmt->execute();
                                                $record_result = $check_stmt->get_result();
                                                $record_exists = $record_result->num_rows > 0;
                                                $check_stmt->close();
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $tanggal; ?></td>
                                                <td><?php echo $pasien; ?></td>
                                                <td><?php echo $hewan; ?></td>
                                                <td><?php echo $jenis_hewan; ?></td>
                                                <td>
                                                    <div class="card-body">
                                                        <div class="action-buttons">
                                                            <?php if ($record_exists): ?>
                                                            <a href="lihat-rekam-medis-grooming.php?id=<?php echo urlencode($id); ?>"
                                                                class="btn btn-success btn-user">Lihat Laporan</a>
                                                            <?php else: ?>
                                                            <a href="tambah-rekam-medis-grooming.php?id=<?php echo urlencode($id); ?>"
                                                                class="btn btn-info btn-user">Isi Laporan</a>
                                                            <?php endif; ?>
                                                            <!-- <button class="btn btn-danger btn-user"
                                                                onclick="confirmDelete('<?php echo urlencode($id); ?>')">Hapus</button> -->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                    $no++;
                                                }
                                                $stmt->close();
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
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete.php with the specified ID
                window.location.href = 'delete_rekam_medis.php?id=' + id;
            }
        });
    }
    </script>
</body>

</html>