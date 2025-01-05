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
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
    .mb-3 {
        margin-bottom: 1rem;
    }

    .img-equal-size {
        width: 100%;
        height: auto;
        max-height: 250px;
        /* Sesuaikan tinggi maksimum sesuai kebutuhan */
    }
    </style>
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include 'includes/header.php'; ?>
        <?php include 'includes/sidebar.php'; ?>

        <?php if ($_SESSION['role'] == 'pasien') { ?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <p>
                                Halaman
                                <strong>Reservasi</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Pilih Jenis Layanan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <div class="text-center">
                                            <img src="../assets/images/pemeriksaan.webp" alt="Pemeriksaan"
                                                class="img-fluid rounded mb-3 img-equal-size">
                                            <br>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#reservasiModal"
                                                onclick="setServiceType('pemeriksaan')">Pemeriksaan</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="text-center">
                                            <img src="../assets/images/grooming.webp" alt="Grooming"
                                                class="img-fluid rounded mb-3 img-equal-size">
                                            <br>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#reservasiModal"
                                                onclick="setServiceType('grooming')">Grooming</button>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="text-center">
                                            <img src="../assets/images/pethotel.webp" alt="Pet Hotel"
                                                class="img-fluid rounded mb-3 img-equal-size">
                                            <br>
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#reservasiModal" onclick="setServiceType('pet_hotel')">Pet
                                                Hotel</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="history-button mt-4">
                                    <p class="text-dark">Cek riwayat reservasi sebelumnya:</p>
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
                                            $query = "SELECT reservasi.*, users.nama, hewan.nama_hewan 
                                                      FROM reservasi 
                                                      LEFT JOIN users ON reservasi.user_id = users.id_users 
                                                      LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan 
                                                      ORDER BY reservasi.tanggal_reservasi DESC";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($display = $result->fetch_assoc()) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama'];
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $slot = $display['slot_reservasi'];
                                                $layanan = $display['jenis_layanan'];
                                                $status = $display['status'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo ucwords(htmlspecialchars($pasien)); ?></td>
                                                <td><?php echo ucwords(htmlspecialchars($hewan)); ?></td>
                                                <td><?php echo htmlspecialchars(date("d F Y", strtotime($tanggal)) . ', ' . ($slot === 'pet_hotel' ? 'Pet Hotel' : ($slot === 'grooming_pagi' ? 'Grooming Pagi' : ($slot === 'grooming_sore' ? 'Grooming Sore' : ($slot === 'sore' ? 'Sore' : ($slot === 'pagi' ? 'Pagi' : $slot)))))); ?>
                                                </td>
                                                <td><?php echo ucwords($layanan); ?></td>
                                                <td>
                                                    <?php if ($status == "pending") { ?>
                                                    <div class="action-buttons">
                                                        <a href='layani_reservasi.php?id=<?php echo $id; ?>'
                                                            class="btn btn-primary btn-user">Layani</a>
                                                        <a href='delete_reservasi.php?id=<?php echo $id; ?>'
                                                            class="btn btn-danger btn-user delete-btn">Hapus</a>
                                                    </div>
                                                    <?php } elseif ($status == "proses") { ?>
                                                    <?php echo $status; ?>
                                                    <?php } else { ?>
                                                    <a href='detail-reservasi.php?id=<?php echo $id; ?>'
                                                        class="btn btn-primary btn-user">Detail</a>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>

    <div class="modal fade" id="reservasiModal" tabindex="-1" role="dialog" aria-labelledby="reservasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservasiModalLabel">Form Reservasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reservasiForm" action="simpan_reservasi.php" method="POST">
                        <div class="mb-3">
                            <label for="tanggal_reservasi" class="form-label">Tanggal Reservasi</label>
                            <?php
                            // Mendapatkan tanggal saat ini dalam format YYYY-MM-DD
                            $today = date("Y-m-d");
                            ?>
                            <input type="date" class="form-control" id="tanggal_reservasi" name="tanggal_reservasi"
                                min="<?php echo $today; ?>" required>
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
                                    echo "<option value='" . htmlspecialchars($row['id_hewan']) . "'>" . htmlspecialchars($row['nama_hewan']) . "</option>";
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="service_type" class="form-label">Jenis Layanan</label>
                            <input type="text" class="form-control" id="service_type" name="service_type"
                                value="pemeriksaan" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="slot_reservasi" class="form-label">Pilih Slot</label>
                            <select class="form-control" id="slot_reservasi" name="slot_reservasi" required>
                                <!-- Options will be populated by JavaScript -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
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
    function setServiceType(serviceType) {
        document.getElementById('service_type').value = serviceType;
    }

    document.getElementById('tanggal_reservasi').addEventListener('change', fetchAvailableSlots);
    document.getElementById('service_type').addEventListener('change', fetchAvailableSlots);

    function fetchAvailableSlots() {
        var tanggal_reservasi = document.getElementById('tanggal_reservasi').value;
        var service_type = document.getElementById('service_type').value;

        if (tanggal_reservasi && service_type) {
            $.ajax({
                url: 'get_available_slots.php',
                type: 'POST',
                data: {
                    tanggal_reservasi: tanggal_reservasi,
                    service_type: service_type
                },
                success: function(response) {
                    var slots = JSON.parse(response);
                    var slotSelect = document.getElementById('slot_reservasi');
                    slotSelect.innerHTML = '';

                    // Populate options based on service type
                    if (service_type === 'pemeriksaan') {
                        var optionPagi = document.createElement('option');
                        optionPagi.value = 'pemeriksaan_pagi';
                        optionPagi.text = `Pemeriksaan Pagi - ${slots['pemeriksaan_pagi']}/4`;
                        slotSelect.appendChild(optionPagi);

                        var optionSore = document.createElement('option');
                        optionSore.value = 'pemeriksaan_sore';
                        optionSore.text = `Pemeriksaan Sore - ${slots['pemeriksaan_sore']}/4`;
                        slotSelect.appendChild(optionSore);
                    } else if (service_type === 'grooming') {
                        var optionPagi = document.createElement('option');
                        optionPagi.value = 'grooming_pagi';
                        optionPagi.text = `Grooming Pagi - ${slots['grooming_pagi']}/4`;
                        slotSelect.appendChild(optionPagi);

                        var optionSore = document.createElement('option');
                        optionSore.value = 'grooming_sore';
                        optionSore.text = `Grooming Sore - ${slots['grooming_sore']}/4`;
                        slotSelect.appendChild(optionSore);
                    } else if (service_type === 'pet_hotel') {
                        var optionPetHotel = document.createElement('option');
                        optionPetHotel.value = 'pet_hotel';
                        optionPetHotel.text = `Pet Hotel - ${slots['pet_hotel']}/30`;
                        slotSelect.appendChild(optionPetHotel);
                    }
                },
                error: function() {
                    alert('Error fetching available slots.');
                }
            });
        }
    }

    document.getElementById('reservasiForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        fetch('simpan_reservasi.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'dashboard.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong. Please try again later.',
                    icon: 'error'
                });
            });
    });

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