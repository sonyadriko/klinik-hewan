<?php
include '../config/database.php';
session_start();

// Initialize variables
$success = false;
$error_message = "";

// Fetch discussion questions from the database
$query = "SELECT * FROM diskusi JOIN users on diskusi.user_id = users.id_users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Handle new question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content'])) {
        $content = $_POST['content'];
        $user_id = $_SESSION['id_users'];

        $insert_query = "INSERT INTO diskusi (isi_diskusi, user_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("si", $content, $user_id);

        if ($stmt->execute()) {
            $success = true;
            $stmt->close();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    } else {
        $error_message = "Content is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Forum Diskusi</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="dashboard">
    <div id="preloader"><i>.</i><i>.</i><i>.</i></div>


    <div id="main-wrapper">
        <?php include 'includes/header.php' ?>
        <?php include 'includes/sidebar.php' ?>

        <div class="content-body">
            <div class="container mt-4">
                <?php if($_SESSION['role'] == 'pasien'){?>
                <h1>Forum Diskusi</h1>
                <?php } ?>

                <?php if ($success) { ?>
                <div class="alert alert-success" role="alert">
                    Pertanyaan berhasil ditambahkan!
                </div>
                <?php } ?>

                <?php if ($error_message) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
                <?php } ?>

                <?php if($_SESSION['role'] == 'pasien'){?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="content" class="form-label">Pertanyaan</label>
                        <textarea class="form-control" id="content" name="content" rows="4"
                            placeholder="Masukkan pertanyaan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pertanyaan</button>
                </form>

                <?php } ?>

                <h2>Pertanyaan Diskusi</h2>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['isi_diskusi'])); ?></p>
                        <small class="text-muted">Dibuat oleh <?php echo $row['nama'] ?>, pada:
                            <?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?></small>
                        <?php if($_SESSION['role'] == 'dokter' || $_SESSION['role'] == 'admin'){?>

                        <a href="reply.php?id_diskusi=<?php echo $row['id_diskusi']; ?>"
                            class="btn btn-secondary mt-2">Balas</a>
                        <?php } ?>
                        <?php if($_SESSION['role'] == 'pasien'){?>
                        <a href="reply.php?id_diskusi=<?php echo $row['id_diskusi']; ?>"
                            class="btn btn-secondary mt-2">Lihat</a>
                        <?php } ?>


                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($success) { ?>
        Swal.fire({
            title: 'Sukses',
            text: 'Pertanyaan berhasil ditambahkan!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        <?php } ?>
    });
    </script>
</body>

</html>