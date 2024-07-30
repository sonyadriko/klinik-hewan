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
    <title>Produk</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">\
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <style>
    .card-title {
        height: 50px;
        /* Adjust this value as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .card-text {
        height: 50px;
        /* Adjust if needed */
        overflow: hidden;
        text-overflow: ellipsis;
    }
    </style>
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
                                <strong> Produk</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php 
                    $get_data = mysqli_query($conn, "SELECT * FROM produk");
                    while($display = mysqli_fetch_array($get_data)) {
                        $id = $display['id_produk'];
                        $nama = $display['nama_produk'];                                            
                        $harga = $display['harga_produk'];
                        $foto = $display['foto_produk'];  
                    ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card">
                            <img src="../uploads/produk/<?php echo $foto; ?>" class="card-img-top img-fluid"
                                alt="<?php echo $nama; ?>">
                            <div class="card-body">
                                <a href="produk-detail.php?id=<?php echo $id; ?>">
                                    <h5 class="card-title"><?php echo $nama; ?></h5>
                                </a>
                                <p class="card-text">Harga: <?php echo $harga; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
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


</body>

</html>