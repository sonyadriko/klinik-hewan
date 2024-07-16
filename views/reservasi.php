<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
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
                                <form id="reservasiForm" action="simpan_reservasi.php" method="POST">
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
                                <div class="history-button mt-4">
                                    <a href="history-reservasi.php" class="btn btn-info btn-user">History</a>
                                </div>
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
                                    <table id="reservasiTable" class="table table-hover table-bordered">
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
                                            $get_data = mysqli_query($conn, "SELECT * FROM reservasi LEFT JOIN users ON reservasi.user_id = users.id_users LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan WHERE reservasi.status = 'pending'");
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
                                                        <a href='layani_reservasi.php?id=<?php echo $id; ?>'
                                                            class="btn btn-primary btn-user">Layani</a>
                                                        <a href='delete_reservasi.php?id=<?php echo $id; ?>'
                                                            class="btn btn-danger btn-user delete-btn">Hapus</a>
                                                        <!-- <button class="btn btn-danger btn-user delete-btn"
                                                            data-id="<?php echo $id; ?>">Hapus</button> -->
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
    <script>
    // Handle form submission with AJAX
    $(document).ready(function() {
        $('#reservasiForm').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.success) {
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
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan reservasi.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan reservasi.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>
</body>

</html>