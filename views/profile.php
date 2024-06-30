<?php 
include '../config/database.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Profile</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
</head>

<body class="@@dashboard">
    <div id="preloader"><i>.</i><i>.</i><i>.</i></div>

    <div id="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/sidebar.php'; ?>
        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Informasi</h4>
                                <a href="edit-profile.php" class="btn btn-primary">Edit</a>
                            </div>
                            <div class="card-body">
                                <form class="row">
                                    <?php
                                        if (isset($_SESSION['id_users'])) {
                                            $id_users = $_SESSION['id_users'];
                                            $stmt = $conn->prepare("SELECT * FROM users WHERE id_users = ?");
                                            $stmt->bind_param("i", $id_users);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows == 1) {
                                                $row = $result->fetch_assoc();
                                                echo '
                                                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                                        <div class="user-info">
                                                            <span>Nama</span>
                                                            <h4>' . ($row['nama'] ? htmlspecialchars($row['nama']) : 'Kosong') . '</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                                        <div class="user-info">
                                                            <span>Email Address</span>
                                                            <h4>' . ($row['email'] ? htmlspecialchars($row['email']) : 'Kosong') . '</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                                        <div class="user-info">
                                                            <span>Alamat</span>
                                                            <h4>' . ($row['alamat'] ? htmlspecialchars($row['alamat']) : 'Kosong') . '</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                                        <div class="user-info">
                                                            <span>Nomor Telepon</span>
                                                            <h4>' . ($row['notelp'] ? htmlspecialchars($row['notelp']) : 'Kosong') . '</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                                                        <div class="user-info">
                                                            <span>Role</span>
                                                            <h4>' . ($row['role'] ? htmlspecialchars($row['role']) : 'Kosong') . '</h4>
                                                        </div>
                                                    </div>
                                                ';
                                            } else {
                                                echo '
                                                    <div class="col-12">
                                                        <p>Data tidak ditemukan</p>
                                                    </div>
                                                ';
                                            }
                                            $stmt->close();
                                        }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Hewan</h4>
                                <a href="tambah-hewan.php" class="btn btn-primary">
                                    Tambah Data Hewan
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="areaTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Hewan</th>
                                                <th>Jenis Hewan</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $stmt = $conn->prepare("SELECT * FROM hewan WHERE users_id = ?");
                                            $stmt->bind_param("i", $_SESSION['id_users']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                echo '
                                                <tr>
                                                    <td>' . $no . '</td>
                                                    <td>' . htmlspecialchars($row['nama_hewan']) . '</td>
                                                    <td>' . htmlspecialchars($row['jenis_hewan']) . '</td>
                                                    <td>' . htmlspecialchars($row['jenis_kelamin']) . '</td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="ubah-hewan.php?id=' . $row['id_hewan'] . '" class="btn btn-primary btn-user">Ubah</a>
                                                            <button class="btn btn-danger btn-user delete-btn" data-id="' . $row['id_hewan'] . '">Hapus</button>
                                                        </div>
                                                    </td>
                                                </tr>';
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
            $('#areaTable').DataTable({
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

            // Delete button click event
            $('.delete-btn').on('click', function() {
                const hewanID = $(this).data('id');
                console.log("Hewan ID:", hewanID); // Debug log

                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete-hewan.php',
                            type: 'POST',
                            data: {
                                id_hewan: hewanID
                            },
                            success: function(response) {
                                console.log("Response:", response); // Debug log
                                Swal.fire(
                                    'Dihapus!',
                                    'Data berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                console.log("Error:", error); // Debug log
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
        </script>
</body>

</html>