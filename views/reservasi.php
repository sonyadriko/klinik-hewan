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

        <?php if($_SESSION['role'] == 'pasien'){ ?>


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
                                            $get_data = mysqli_query($conn, "SELECT * FROM reservasi LEFT JOIN users ON reservasi.user_id = users.id_users LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan WHERE reservasi.status = 'pending'");
                                            while($display = mysqli_fetch_array($get_data)) {
                                                $id = $display['id_reservasi'];
                                                $pasien = $display['nama'];                                            
                                                $hewan = $display['nama_hewan'];
                                                $tanggal = $display['tanggal_reservasi'];
                                                $waktu = $display['waktu_reservasi'];
                                                $layanan = $display['jenis_layanan'];
                                                if ($layanan === 'pet_hotel') {
                                                    $layanan = 'Pet Hotel';
                                                }
                                                $slot = $display['slot_reservasi'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $pasien; ?></td>
                                                <td><?php echo $hewan; ?></td>
                                                <td><?php echo $tanggal . ', ' . $slot; ?></td>
                                                <td><?php echo ucwords($layanan); ?></td>
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

    <!-- Modal -->
    <!-- <div class="modal fade" id="reservasiModal" tabindex="-1" role="dialog" aria-labelledby="reservasiModalLabel"
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
                            <input type="date" class="form-control" id="tanggal_reservasi" name="tanggal_reservasi"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_reservasi" class="form-label">Waktu Reservasi</label>
                            <input type="time" class="form-control" id="waktu_reservasi" name="waktu_reservasi"
                                required>
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
                            <input type="text" class="form-control" id="service_type" name="service_type"
                                value="pemeriksaan" disabled readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal -->
    <!-- <div class="modal fade" id="reservasiModal" tabindex="-1" role="dialog" aria-labelledby="reservasiModalLabel"
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
                            <input type="date" class="form-control" id="tanggal_reservasi" name="tanggal_reservasi"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="waktu_reservasi" class="form-label">Waktu Reservasi</label>
                            <select class="form-control" id="waktu_reservasi" name="waktu_reservasi" required>
                                <option value="morning">Pagi</option>
                                <option value="afternoon">Sore</option>
                            </select>
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
                            <input type="text" class="form-control" id="service_type" name="service_type" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="slot_reservasi" class="form-label">Slot Reservasi</label>
                            <select class="form-control" id="slot_reservasi" name="slot_reservasi" required>
                                <!-- Slots will be populated dynamically based on time of day
    </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
    </div>
    </div>
    </div> -->

    <!-- <div class="modal fade" id="reservasiModal" tabindex="-1" role="dialog" aria-labelledby="reservasiModalLabel"
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
                            <input type="date" class="form-control" id="tanggal_reservasi" name="tanggal_reservasi"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="slot_reservasi" class="form-label">Pilih Slot</label>
                            <select class="form-control" id="slot_reservasi" name="slot_reservasi" required>
                                <option value="pagi">Pagi</option>
                                <option value="sore">Sore</option>
                            </select>
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
                            <input type="text" class="form-control" id="service_type" name="service_type"
                                value="pemeriksaan" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

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
                                    echo "<option value='" . $row['id_hewan'] . "'>" . htmlspecialchars($row['nama_hewan']) . "</option>";
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
    </script>


    <!-- <script>
    $('#reservasiModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var serviceType = button.data('service-type'); // Extract info from data-* attribute
        var modal = $(this);
        modal.find('.modal-body input#service_type').val(serviceType); // Update the modal's content

        var timeSelect = modal.find('.modal-body select#waktu_reservasi');
        var slotSelect = modal.find('.modal-body select#slot_reservasi');

        timeSelect.change(function() {
            var timeOfDay = $(this).val();
            populateSlots(timeOfDay);
        });

        // Populate slots initially based on the selected time of day
        populateSlots(timeSelect.val());
    });

    function populateSlots(timeOfDay) {
        var slots = [];
        if (timeOfDay === 'morning') {
            slots = ['08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00'];
        } else if (timeOfDay === 'afternoon') {
            slots = ['13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00'];
        }

        var slotSelect = $('#slot_reservasi');
        slotSelect.empty();
        slots.forEach(function(slot) {
            slotSelect.append('<option value="' + slot + '">' + slot + '</option>');
        });
    }
    </script>
 -->

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
    // $(document).ready(function() {
    //     $('#reservasiForm').submit(function(event) {
    //         event.preventDefault();
    //         var form = $(this);
    //         var url = form.attr('action');

    //         $.ajax({
    //             type: "POST",
    //             url: url,
    //             data: form.serialize(),
    //             dataType: "json",
    //             success: function(data) {
    //                 if (data.success) {
    //                     Swal.fire({
    //                         title: 'Sukses',
    //                         text: 'Reservasi berhasil dibuat!',
    //                         icon: 'success',
    //                         confirmButtonText: 'OK'
    //                     }).then((result) => {
    //                         if (result.isConfirmed) {
    //                             window.location.href = 'dashboard.php';
    //                         }
    //                     });
    //                 } else {
    //                     Swal.fire({
    //                         title: 'Gagal',
    //                         text: 'Terjadi kesalahan saat menyimpan reservasi.',
    //                         icon: 'error',
    //                         confirmButtonText: 'OK'
    //                     });
    //                 }
    //             },
    //             error: function() {
    //                 Swal.fire({
    //                     title: 'Gagal',
    //                     text: 'Terjadi kesalahan saat menyimpan reservasi.',
    //                     icon: 'error',
    //                     confirmButtonText: 'OK'
    //                 });
    //             }
    //         });
    //     });
    // });
    </script>
</body>

</html>