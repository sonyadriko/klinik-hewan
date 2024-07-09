<?php 
include '../config/database.php';
session_start();

if (isset($_GET['id'])) {
    $id_artikel = $_GET['id'];
    $get_article = mysqli_query($conn, "SELECT * FROM artikel WHERE id_artikel = $id_artikel");

    if (mysqli_num_rows($get_article) == 1) {
        $article = mysqli_fetch_assoc($get_article);
    } else {
        echo "Artikel tidak ditemukan.";
        exit();
    }
} else {
    echo "ID artikel tidak disediakan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Detail Artikel</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
</head>

<body class="dashboard">
    <div id="main-wrapper">
        <?php include 'includes/header.php' ?>
        <?php include 'includes/sidebar.php' ?>

        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="page-title-content">
                            <h2><?php echo $article['judul']; ?></h2>
                            <p>
                                <strong>Penulis:</strong> <?php echo $article['penulis']; ?><br>
                                <strong>Tanggal:</strong>
                                <?php echo date('d-m-Y H:i:s', strtotime($article['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <img class="img-fluid card-img-top" src="<?php echo $article['image']; ?>" alt="" />
                            <div class="card-body">
                                <p style="color: #000;"><?php echo nl2br($article['isi']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar-init.js"></script>

    <script src="../assets/js/scripts.js"></script>
</body>

</html>