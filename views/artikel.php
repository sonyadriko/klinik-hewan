<?php 
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Artikel</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
</head>

<body class="dashboard">
    <!-- <div id="preloader"><i>.</i><i>.</i><i>.</i></div> -->

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
                                <strong> Artikel</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Artikel</h4>
                                <a href="tambah-artikel.php" class="btn btn-primary">
                                    Tambah Data Artikel
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="areaTable" class="table table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul</th>
                                                <th>Isi</th>
                                                <th>Penulis</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            $get_data = mysqli_query($conn, "SELECT * FROM artikel");
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $id = $display['id_artikel'];
                                                $judul = $display['judul'];                                            
                                                $isi = $display['isi'];
                                                $penulis = $display['penulis'];
                                                 // Memotong isi menjadi 100 kata
                                                $isi_potong = substr($isi, 0, 500);
                                                $isi_potong = rtrim($isi_potong); // Menghapus spasi ekstra di akhir
                                                
                                                // Menambahkan titik elipsis (...) jika teks dipotong
                                                if (strlen($isi) > 500) {
                                                    $isi_potong .= '...';
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $judul; ?></td>
                                                <td><?php echo $isi_potong; ?></td>
                                                <td><?php echo $penulis; ?></td>
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
    </div>
    </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script src="../assets/vendor/basic-table/jquery.basictable.min.js"></script>
    <script src="../assets/js/plugins/basic-table-init.js"></script>

    <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar-init.js"></script>

    <script src="../assets/js/dashboard.js"></script>

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
            const artikelID = $(this).data('id');
            console.log("Artikel ID:", artikelID); // Debug log

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
                        url: 'delete_artikel.php',
                        type: 'POST',
                        data: {
                            id_artikel: artikelID
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