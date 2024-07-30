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
ini_set('display_errors', 1);

$alertMessage = "";
$alertType = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];

    // Handling the uploaded file
    $target_dir = "../uploads/produk/";
    $target_file = $target_dir . basename($_FILES["foto_produk"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["foto_produk"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $alertMessage = "File is not an image.";
        $alertType = "error";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $alertMessage = "Sorry, file already exists.";
        $alertType = "error";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["foto_produk"]["size"] > 500000) {
        $alertMessage = "Sorry, your file is too large.";
        $alertType = "error";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $alertMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $alertType = "error";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // File was not uploaded
    } else {
        if (move_uploaded_file($_FILES["foto_produk"]["tmp_name"], $target_file)) {
            $foto_produk = basename($_FILES["foto_produk"]["name"]);
            $query = "INSERT INTO produk (nama_produk, harga_produk, foto_produk) VALUES ('$nama_produk', '$harga_produk', '$foto_produk')";
            if (mysqli_query($conn, $query)) {
                $alertMessage = "Data produk berhasil ditambahkan.";
                $alertType = "success";
            } else {
                $alertMessage = "Error: " . $query . "<br>" . mysqli_error($conn);
                $alertType = "error";
            }
        } else {
            $alertMessage = "Sorry, there was an error uploading your file.";
            $alertType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Produk</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                <strong>Tambah Produk</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tambah Data Produk</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group mb-4">
                                        <label for="nama_produk">Nama Produk</label>
                                        <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                            placeholder="Masukkan nama produk" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="harga_produk">Harga Produk</label>
                                        <input type="number" class="form-control" id="harga_produk" name="harga_produk"
                                            placeholder="Masukkan harga produk" required>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="foto_produk">Foto Produk</label>
                                        <input type="file" class="form-control" id="foto_produk" name="foto_produk"
                                            required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah Produk</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/scripts.js"></script>

    <?php if (!empty($alertMessage)): ?>
    <script>
    Swal.fire({
        title: '<?php echo $alertType == "success" ? "Berhasil" : "Gagal"; ?>',
        text: '<?php echo $alertMessage; ?>',
        icon: '<?php echo $alertType; ?>'
    }).then((result) => {
        if (result.isConfirmed && '<?php echo $alertType; ?>' === 'success') {
            window.location.href = 'produk.php';
        }
    });
    </script>
    <?php endif; ?>
</body>

</html>