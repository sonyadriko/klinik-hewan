<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // / Fetch invoice details with user and hewan names based on the ID
    $invoice_query = "SELECT invoice.*, 
                             reservasi.id_reservasi, 
                             reservasi.user_id, 
                             users.nama AS nama_user, 
                             hewan.nama_hewan AS nama_hewan
                      FROM invoice 
                      LEFT JOIN reservasi ON invoice.id_reservasi = reservasi.id_reservasi 
                      LEFT JOIN users ON reservasi.user_id = users.id_users
                      LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan
                      WHERE invoice.id_invoice = '$invoice_id'";
    $invoice_result = mysqli_query($conn, $invoice_query);
    $invoice = mysqli_fetch_array($invoice_result);

    if (!$invoice) {
        echo "Invoice tidak ditemukan.";
        exit;
    }

    // Fetch invoice details (previously invoice_items) based on the new table name
    $items_query = "SELECT * FROM detail_invoice WHERE id_invoice = '$invoice_id'";
    $items_result = mysqli_query($conn, $items_query);
} else {
    echo "ID Invoice tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Invoice</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <link rel="stylesheet" href="../assets/css/style.css" />
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
                                <strong> Lihat Invoice</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Detail Invoice</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Invoice Kepada:</strong>
                                    <?php echo $invoice['nama_hewan'] . ' - ' . $invoice['nama_user']; ?></p>
                                <p><strong>ID Invoice:</strong> INV-0<?php echo $invoice['id_invoice']; ?></p>
                                <p><strong>Tanggal Invoice:</strong> <?php echo $invoice['tanggal_invoice']; ?></p>
                                <p><strong>Tanggal Jatuh Tempo:</strong> <?php echo $invoice['jatuh_tempo']; ?></p>
                                <!-- <p><strong>ID Reservasi:</strong> <?php echo $invoice['id_reservasi']; ?></p> -->
                                <h5>Items:</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($item = mysqli_fetch_array($items_result)): ?>
                                        <tr>
                                            <td><?php echo $item['item']; ?></td>
                                            <td><?php echo $item['jumlah']; ?></td>
                                            <td><?php echo number_format($item['harga'], 2); ?></td>
                                            <td><?php echo number_format($item['total'], 2); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <p><strong>Total Keseluruhan:</strong> <?php 
                                    $total_query = "SELECT SUM(total) AS total_harga FROM detail_invoice WHERE id_invoice = '$invoice_id'";
                                    $total_result = mysqli_query($conn, $total_query);
                                    $total_data = mysqli_fetch_array($total_result);
                                    echo number_format($total_data['total_harga'], 2);
                                ?></p>
                            </div>
                            <div class="card-footer">
                                <a href="reservasi.php" class="btn btn-secondary">Kembali</a>
                                <a href="cetak_invoice.php?id=<?php echo $invoice['id_invoice']; ?>"
                                    class="btn btn-primary">Cetak Invoice</a>

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
</body>

</html>