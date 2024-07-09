<?php
include '../config/database.php';
session_start();
// Set timezone to WIB (UTC+7)
date_default_timezone_set('Asia/Jakarta');
// Proses tambah artikel jika form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['judul'], $_POST['isi'])) {
        $judul = $_POST['judul'];
        $isi = $_POST['isi'];
        $penulis = $_SESSION['nama']; // Menggunakan session untuk mendapatkan nama penulis
        $tanggal = date('Y-m-d H:i:s'); // Mendapatkan tanggal saat ini\

        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

         // Check if image file is a actual image or fake image
         $check = getimagesize($_FILES["gambar"]["tmp_name"]);
         if($check !== false) {
             if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                 // Gambar berhasil diunggah, simpan path gambar ke database
                 $gambar_path = $target_file;
 
                 $insert_query = "INSERT INTO artikel (image, judul, isi, penulis, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)";
                 $stmt = $conn->prepare($insert_query);
                 $stmt->bind_param("ssssss", $gambar_path, $judul, $isi, $penulis, $tanggal, $tanggal);
 
                 if ($stmt->execute()) {
                     $stmt->close();
                     $conn->close();
                     // Redirect to artikel.php after successfully adding artikel
                     header("Location: artikel.php");
                     exit();
                 } else {
                     echo "Error: " . $stmt->error;
                 }
             } else {
                 echo "Sorry, there was an error uploading your file.";
             }
         } else {
             echo "File is not an image.";
         }
     } else {
         echo "All fields are required.";
     }
 }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Tambah Artikel</title>
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
                            <p>
                                Halaman
                                <strong> Tambah Artikel</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Tambah Artikel</h4>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul</label>
                                        <input type="text" class="form-control" id="judul" name="judul" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="isi" class="form-label">Isi Artikel</label>
                                        <textarea class="form-control" id="isi" name="isi" rows="5" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Unggah Gambar</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar"
                                            accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="../assets/js/scripts.js"></script>
</body>

</html>