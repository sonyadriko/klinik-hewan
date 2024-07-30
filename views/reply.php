<?php
include '../config/database.php';
session_start();

$question_id = $_GET['id_diskusi'] ?? 0;
$error_message = "";
$success = false;

// Fetch question details
$stmt = $conn->prepare("SELECT * FROM diskusi LEFT JOIN users ON diskusi.user_id = users.id_users WHERE id_diskusi = ?");
$stmt->bind_param("i", $question_id);
$stmt->execute();
$question = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$question) {
    header("Location: forum.php");
    exit();
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['content']) && !empty(trim($_POST['content']))) {
        $content = trim($_POST['content']);
        $user_id = $_SESSION['id_users'];

        $insert_query = "INSERT INTO jawaban (diskusi_id, isi_jawaban, user_id, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("isi", $question_id, $content, $user_id);

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

// Fetch replies for the question
$query = "SELECT * FROM jawaban LEFT JOIN users ON jawaban.user_id = users.id_users WHERE diskusi_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$replies = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Balas Pertanyaan</title>
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
                <h1>Balas Pertanyaan</h1>

                <?php if ($success) { ?>
                <div class="alert alert-success" role="alert">
                    Balasan berhasil ditambahkan!
                </div>
                <?php } ?>

                <?php if ($error_message) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
                <?php } ?>

                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($question['isi_diskusi'])); ?></p>
                        <small class="text-muted">Dibuat oleh <?php echo $question['nama'] ?>, pada:
                            <?php echo date("d M Y, H:i", strtotime($question['created_at'])); ?></small>
                    </div>
                </div>

                <?php if($_SESSION['role'] == 'dokter' || $_SESSION['role'] == 'admin'){?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id_diskusi=' . $question_id; ?>"
                    method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Balasan</label>
                        <textarea class="form-control" id="content" name="content" rows="4"
                            placeholder="Masukkan balasan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                </form>

                <?php } ?>

                <h2>Balasan</h2>
                <?php while ($reply = $replies->fetch_assoc()) { ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($reply['isi_jawaban'])); ?></p>
                        <small class="text-muted">Dibalas oleh <?php echo $reply['nama'] ?>, pada:
                            <?php echo date("d M Y, H:i", strtotime($reply['created_at'])); ?></small>
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
            text: 'Balasan berhasil ditambahkan!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        <?php } ?>
    });
    </script>
</body>

</html>