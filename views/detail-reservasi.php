<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}
// Check if the ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch reservation details based on the ID
    $query = "SELECT reservasi.*, users.nama AS nama_pemilik, hewan.nama_hewan, users.notelp
              FROM reservasi 
              LEFT JOIN users ON reservasi.user_id = users.id_users 
              LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan 
              WHERE id_reservasi = '$id'";

    $result = mysqli_query($conn, $query);
    $detail = mysqli_fetch_array($result);

    if (!$detail) {
        echo "Reservasi tidak ditemukan.";
        exit;
    }
    
    // Fetch grooming data based on the reservation ID
    $grooming_query = "SELECT * FROM rekam_medis_grooming WHERE reservasi_id = '$id'";
    $grooming_result = mysqli_query($conn, $grooming_query);
    $grooming_data = mysqli_fetch_array($grooming_result);

    // Check if an invoice already exists for this reservation
    $check_invoice_query = "SELECT * FROM invoice WHERE id_reservasi = '$id'";
    $invoice_result = mysqli_query($conn, $check_invoice_query);
    $invoice_exists = mysqli_num_rows($invoice_result) > 0; // True if an invoice exists

} else {
    echo "ID Reservasi tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS for modal -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                                <strong> Detail Reservasi</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Data Detail Reservasi</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Nama Pemilik:</strong> <?php echo $detail['nama_pemilik']; ?></p>
                                <p><strong>Nama Hewan:</strong> <?php echo $detail['nama_hewan']; ?></p>
                                <p><strong>Tanggal Reservasi:</strong> <?php echo $detail['tanggal_reservasi']; ?></p>
                                <p><strong>Jenis Layanan:</strong> <?php echo ucwords($detail['jenis_layanan']); ?></p>
                                <?php if ($grooming_data): ?>
                                <p><strong>Data Grooming:</strong></p>
                                <ul>
                                    <li>Mandi: <?php echo $grooming_data['mandi'] ? 'Ya' : 'Tidak'; ?></li>
                                    <li>Perawatan Bulu: <?php echo $grooming_data['perawatan_bulu'] ? 'Ya' : 'Tidak'; ?>
                                    </li>
                                    <li>Trimming: <?php echo $grooming_data['trimming'] ? 'Ya' : 'Tidak'; ?></li>
                                    <li>Pembersihan Telinga:
                                        <?php echo $grooming_data['pembersihan_telinga'] ? 'Ya' : 'Tidak'; ?></li>
                                    <li>Inspeksi Kutu: <?php echo $grooming_data['inspeksi_kutu'] ? 'Ya' : 'Tidak'; ?>
                                    </li>
                                </ul>
                                <?php endif; ?>
                                <p><strong>Status:</strong> <?php echo ucwords($detail['status']); ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="reservasi.php" class="btn btn-secondary">Kembali</a>
                                <?php if ($invoice_exists): ?>
                                <?php $invoice_data = mysqli_fetch_array($invoice_result); // Get the existing invoice data ?>
                                <a href="lihat_invoice.php?id=<?php echo $invoice_data['id_invoice']; ?>"
                                    class="btn btn-primary">
                                    Lihat Invoice
                                </a>
                                <!-- WhatsApp Chat Button -->
                                <?php if (!empty($detail['notelp'])): // Check if notelp is available ?>
                                <?php 
                                    // Create the message for WhatsApp including service type and animal name
                                    $serviceType = ucfirst($detail['jenis_layanan']);
                                    $animalName = ucfirst($detail['nama_hewan']);
                                    $message = "Halo, layanan $serviceType untuk hewan $animalName Anda sudah selesai dan bisa diambil. Terima kasih!";
                                ?>
                                <a href="https://wa.me/62<?php echo $detail['notelp']; ?>?text=<?php echo urlencode($message); ?>"
                                    target="_blank" class="btn btn-success">
                                    Chat WA
                                </a>
                                <?php else: ?>
                                <span class="text-danger">Nomor telepon tidak tersedia.</span>
                                <?php endif; ?>
                                <?php else: ?>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#invoiceModal">
                                    Buat Invoice
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for creating invoice -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Buat Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="invoiceForm" action="simpan_invoice.php" method="POST">
                        <input type="hidden" name="reservasi_id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <label for="tanggal_invoice">Tanggal Invoice</label>
                            <input type="date" class="form-control" id="tanggal_invoice" name="tanggal_invoice"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="jatuh_tempo">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo" required>
                        </div>
                        <div id="invoiceItems">
                            <h5>Items</h5>
                            <div class="form-group item-row">
                                <input type="text" class="form-control" name="item[]" placeholder="Nama Item" required>
                                <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required
                                    oninput="calculateTotal(this)">
                                <input type="number" class="form-control" name="harga[]" placeholder="Harga" required
                                    oninput="calculateTotal(this)">
                                <input type="number" class="form-control" name="total[]" placeholder="Total" readonly>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" id="addItemButton">Tambah Item</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" form="invoiceForm">Simpan Invoice</button>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>

    <script>
    $(document).ready(function() {
        let itemCount = 0;

        $('#addItemButton').click(function() {
            itemCount++;
            $('#invoiceItems').append(`
                <div class="form-group item-row">
                    <input type="text" class="form-control" name="item[]" placeholder="Nama Item" required>
                    <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah" required oninput="calculateTotal(this)">
                    <input type="number" class="form-control" name="harga[]" placeholder="Harga" required oninput="calculateTotal(this)">
                    <input type="number" class="form-control" name="total[]" placeholder="Total" readonly>
                </div>
            `);
        });

        // Function to calculate the total based on quantity and price
        // function calculateTotal(element) {
        //     const itemRow = $(element).closest('.item-row');
        //     const jumlah = itemRow.find('input[name="jumlah[]"]').val();
        //     const harga = itemRow.find('input[name="harga[]"]').val();
        //     const total = itemRow.find('input[name="total[]"]');
        //     total.val(jumlah * harga);
        // }

        // Function to calculate the total based on quantity and price
        window.calculateTotal = function(element) {
            const itemRow = $(element).closest('.item-row');
            const jumlah = itemRow.find('input[name="jumlah[]"]').val() || 0;
            const harga = itemRow.find('input[name="harga[]"]').val() || 0;
            const total = jumlah * harga;
            itemRow.find('input[name="total[]"]').val(total);
        };
    });
    </script>
</body>

</html>