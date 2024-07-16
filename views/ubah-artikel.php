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

// Ambil data artikel berdasarkan ID yang dikirimkan melalui parameter GET
if (isset($_GET['id'])) {
    $id_artikel = $_GET['id'];
    
    // Query untuk mengambil data artikel berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM artikel WHERE id_artikel = ?");
    $stmt->bind_param("i", $id_artikel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $judul = $row['judul'];
        $isi = $row['isi'];
        $penulis = $row['penulis'];
        $gambar_lama = $row['image']; // Menyimpan path gambar lama
    } else {
        echo "Artikel tidak ditemukan.";
        exit();
    }

    $stmt->close();
} else {
    echo "ID artikel tidak ditemukan.";
    exit();
}

date_default_timezone_set('Asia/Jakarta');
// Proses form jika ada POST request untuk menyimpan perubahan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['judul'], $_POST['isi'])) {
        $judul_baru = $_POST['judul'];
        $isi_baru = $_POST['isi'];
        $updated = date('Y-m-d H:i:s'); 
        $gambar_baru = $gambar_lama; // Inisialisasi dengan gambar lama

        // Periksa apakah ada gambar yang diunggah
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $file_tmp_path = $_FILES['gambar']['tmp_name'];
            $file_name = $_FILES['gambar']['name'];
            $file_size = $_FILES['gambar']['size'];
            $file_type = $_FILES['gambar']['type'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_ext = array("jpg", "jpeg", "png", "gif");
            if (in_array($file_ext, $allowed_ext)) {
                $upload_dir = '../uploads/';
                $dest_path = $upload_dir . $file_name;

                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    $gambar_baru = $dest_path;
                } else {
                    echo "Error saat mengunggah gambar.";
                }
            } else {
                echo "Format file gambar tidak valid.";
            }
        }

        // Query untuk melakukan update artikel berdasarkan ID
        $stmt_update = $conn->prepare("UPDATE artikel SET judul = ?, isi = ?, image = ?, updated_at = ? WHERE id_artikel = ?");
        $stmt_update->bind_param("ssssi", $judul_baru, $isi_baru, $gambar_baru, $updated, $id_artikel);

        if ($stmt_update->execute()) {
            $stmt_update->close();
            $conn->close();
            // Redirect kembali ke halaman artikel setelah berhasil update
            header("Location: artikel.php");
            exit();
        } else {
            echo "Error: " . $stmt_update->error;
        }
    } else {
        echo "Semua kolom harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Artikel</title>
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
                                <strong> Edit Artikel</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Edit Artikel</h4>
                            </div>
                            <div class="card-body">
                                <form action="ubah-artikel.php?id=<?php echo $id_artikel; ?>" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="judul" class="form-label">Judul</label>
                                        <input type="text" class="form-control" id="judul" name="judul" required
                                            value="<?php echo $judul; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="isi" class="form-label">Isi Artikel</label>
                                        <textarea class="form-control" style="height: auto;" id="isi" name="isi"
                                            rows="10"><?php echo $isi; ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label">Gambar</label>
                                        <input type="file" class="form-control" id="gambar" name="gambar">
                                        <?php if ($gambar_lama): ?>
                                        <img src="<?php echo $gambar_lama; ?>" alt="Gambar Artikel" width="200">
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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