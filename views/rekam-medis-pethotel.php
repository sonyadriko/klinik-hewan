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
    <title>Data Pet Hotel</title>
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
        <?php if (isset($_GET['message'])) {
            if ($_GET['message'] == 'checkout_success') {
                echo '<script>Swal.fire("Success", "Check out berhasil!", "success");</script>';
            } elseif ($_GET['message'] == 'checkout_error') {
                echo '<script>Swal.fire("Error", "Check out gagal!", "error");</script>';
            }
        } ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong> Data Pet Hotel</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Pet Hotel</h4>
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
                                                SELECT r.id_reservasi, r.tanggal_reservasi, u.nama AS nama_pemilik, h.nama_hewan, h.jenis_hewan, r.status
                                                FROM reservasi r
                                                INNER JOIN users u ON r.user_id = u.id_users
                                                INNER JOIN hewan h ON r.hewan_id = h.id_hewan
                                                WHERE r.status IN ('proses', 'check out') AND r.jenis_layanan = 'pet_hotel'
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
                                                $status = $display['status'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo htmlspecialchars($tanggal); ?></td>
                                                <td><?php echo htmlspecialchars($pasien); ?></td>
                                                <td><?php echo htmlspecialchars($hewan); ?></td>
                                                <td><?php echo htmlspecialchars($jenis_hewan); ?></td>
                                                <td>
                                                    <?php if ($status == 'proses') { ?>
                                                    <button type="button" class="btn btn-info btn-user"
                                                        onclick="confirmCheckout(<?php echo $id; ?>)">Check out</button>
                                                    <?php } else { ?>
                                                    <?php echo 'Selesai'; ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                                $no++;
                                            }
                                            $stmt->close();
                                            ?>
                                        </tbody>
                                    </table>
                                    <form id="checkoutForm" action="update_status_pethotel.php" method="POST"
                                        style="display:none;">
                                        <input type="hidden" name="id" id="checkoutId">
                                    </form>
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
    function confirmCheckout(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, check out!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('checkoutId').value = id;
                document.getElementById('checkoutForm').submit();
            }
        });
    }

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