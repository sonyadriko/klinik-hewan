<?php
include '../config/database.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

// Check if invoice ID is available in the URL and sanitize the input
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $invoice_id = intval($_GET['id']); // Use intval to prevent SQL injection

    // Query to get the invoice details
    $invoice_query = "SELECT invoice.*, 
                             reservasi.id_reservasi, 
                             reservasi.user_id, 
                             users.nama AS nama_user, 
                             hewan.nama_hewan AS nama_hewan
                      FROM invoice 
                      LEFT JOIN reservasi ON invoice.id_reservasi = reservasi.id_reservasi 
                      LEFT JOIN users ON reservasi.user_id = users.id_users
                      LEFT JOIN hewan ON reservasi.hewan_id = hewan.id_hewan
                      WHERE invoice.id_invoice = ?";
    $stmt = $conn->prepare($invoice_query);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $invoice_result = $stmt->get_result();
    $invoice = $invoice_result->fetch_assoc();

    // Check if the invoice data was found
    if (!$invoice) {
        echo "Invoice tidak ditemukan.";
        exit();
    }

    // Query to get the items from detail_invoice
    $items_query = "SELECT * FROM detail_invoice WHERE id_invoice = ?";
    $stmt_items = $conn->prepare($items_query);
    $stmt_items->bind_param("i", $invoice_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
} else {
    echo "ID Invoice tidak valid.";
    exit();
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
                                    <?php 
                                    if (!empty($invoice['nama_hewan']) && !empty($invoice['nama_user'])) {
                                        echo htmlspecialchars($invoice['nama_hewan'] . ' - ' . $invoice['nama_user']);
                                    } else {
                                        echo "Data Reservasi Tidak Tersedia";
                                    }
                                    ?>
                                </p>
                                <p><strong>ID Invoice:</strong>
                                    INV-0<?php echo htmlspecialchars($invoice['id_invoice']); ?></p>
                                <p><strong>Tanggal Invoice:</strong>
                                    <?php echo htmlspecialchars($invoice['tanggal_invoice']); ?></p>
                                <p><strong>Tanggal Jatuh Tempo:</strong>
                                    <?php echo htmlspecialchars($invoice['jatuh_tempo']); ?></p>
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
                                        <?php 
                                        if ($items_result->num_rows > 0) {
                                            while ($item = $items_result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['item']); ?></td>
                                            <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                            <td><?php echo number_format($item['harga'], 2); ?></td>
                                            <td><?php echo number_format($item['total'], 2); ?></td>
                                        </tr>
                                        <?php }
                                        } else {
                                            echo "<tr><td colspan='4'>Tidak ada item dalam invoice ini.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <p><strong>Total Keseluruhan:</strong>
                                    <?php 
                                    $total_query = "SELECT SUM(total) AS total_harga FROM detail_invoice WHERE id_invoice = ?";
                                    $stmt_total = $conn->prepare($total_query);
                                    $stmt_total->bind_param("i", $invoice_id);
                                    $stmt_total->execute();
                                    $total_result = $stmt_total->get_result();
                                    $total_data = $total_result->fetch_assoc();
                                    echo number_format($total_data['total_harga'], 2);
                                    ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="history-reservasi.php" class="btn btn-secondary">Kembali</a>
                                <!-- <a href="cetak_invoice.php?id=<?php echo htmlspecialchars($invoice['id_invoice']); ?>"
                                    class="btn btn-primary">Cetak Invoice</a> -->
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