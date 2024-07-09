<?php 
include '../config/database.php';
session_start();
$get_articles = mysqli_query($conn, "SELECT * FROM artikel");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <style>
    .card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Jumlah baris teks yang ingin ditampilkan */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 3em;
        /* Sesuaikan dengan tinggi yang diinginkan */
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .card a {
        display: inline-block;
        /* Make the link take only the width of its content */
        text-decoration: underline;
        /* Ensure underline style */
        text-decoration-color: initial;
        /* Reset underline color if needed */
        text-decoration-thickness: initial;
        /* Reset underline thickness if needed */
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
                                Selamat Datang,
                                <strong><?php echo $_SESSION['nama'] ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="row">
                            <?php
                            $counter = 0;
                            while($article = mysqli_fetch_array($get_articles)) {
                                // Menampilkan artikel dalam grid dengan 3 kolom per baris
                                if ($counter % 3 == 0 && $counter != 0) {
                                    echo '</div><div class="row">';
                                }
                            ?>
                            <div class="col-xl-4 col-lg-4 col-md-6">
                                <div class="blog-grid">
                                    <div class="card">
                                        <img class="img-fluid card-img-top" src="<?php echo $article['image']; ?>"
                                            alt="" />
                                        <div class="card-body">
                                            <!-- <a href="detail-artikel.php?id=<?php echo $article['id_artikel']; ?>"> -->
                                            <h4 class="card-title"><?php echo $article['judul']; ?></h4>
                                            <!-- </a> -->
                                            <a href="detail-artikel.php?id=<?php echo $article['id_artikel']; ?>">Read
                                                More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $counter++;
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

    <script src="../assets/vendor/apexchart/apexcharts.min.js"></script>
    <script src="../assets/js/plugins/apex-price.js"></script>

    <script src="../assets/vendor/basic-table/jquery.basictable.min.js"></script>
    <script src="../assets/js/plugins/basic-table-init.js"></script>

    <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar-init.js"></script>

    <script src="../assets/js/dashboard.js"></script>

    <script src="../assets/js/scripts.js"></script>
</body>

</html>