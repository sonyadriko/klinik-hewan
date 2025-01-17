<?php
include '../config/database.php';
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Fetch invoice details with user and hewan names
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

    // Fetch invoice items
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
    <title>Cetak Invoice<?php echo $invoice['id_invoice']; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-details {
        margin-top: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        text-align: center;
        /* Center the text inside the div */
    }

    .invoice-footer {
        text-align: center;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .invoice-table th,
    .invoice-table td {
        border: 1px solid #000;
        padding: 10px;
        text-align: left;
    }

    .logo {
        max-width: 100px;
        /* Adjust the size of the logo */
    }

    .signature {
        text-align: right;
        margin-top: 50px;
        padding-right: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="invoice-header">
            <img src="../assets/images/logo.jpg" alt="Logo" class="logo">
            <h1>Invoice</h1>
        </div>
        <!-- New div for invoice details -->
        <div class="invoice-details">
            <p><strong>Invoice Kepada:</strong> <?php echo $invoice['nama_hewan'] . ' - ' . $invoice['nama_user']; ?>
            </p>
            <p><strong>ID Invoice:</strong> INV-0<?php echo $invoice['id_invoice']; ?></p>
            <p><strong>Tanggal Invoice:</strong> <?php echo $invoice['tanggal_invoice']; ?></p>
            <p><strong>Tanggal Jatuh Tempo:</strong> <?php echo $invoice['jatuh_tempo']; ?></p>
        </div>

        <h5>Items:</h5>
        <table class="invoice-table">
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
                    <td><?php  echo "Rp. " .  number_format($item['harga'], 2); ?></td>
                    <td><?php  echo "Rp. " .  number_format($item['total'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <p class="mt-2"><strong>Total Keseluruhan:</strong> <?php 
            $total_query = "SELECT SUM(total) AS total_harga FROM detail_invoice WHERE id_invoice = '$invoice_id'";
            $total_result = mysqli_query($conn, $total_query);
            $total_data = mysqli_fetch_array($total_result);
            echo "Rp. " .  number_format($total_data['total_harga'], 2);
        ?></p>

        <div class="invoice-footer">
            <p>Terima kasih atas kepercayaan Anda!</p>
            <div class="signature">
                <!-- <p>Hormat Kami,</p> -->
                <br><br>
                <p>______________________</p>
                <p><strong>Admin/Dokter</strong></p>
            </div>
        </div>


    </div>

    <script>
    window.onload = function() {
        window.print();
    };
    </script>
</body>

</html>