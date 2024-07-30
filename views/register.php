<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Registrasi</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.ico" />
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>

<body class="@@dashboard">
    <div id="preloader"><i>.</i><i>.</i><i>.</i></div>

    <div id="main-wrapper">
        <div class="authincation section-padding">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-xl-5 col-md-6">
                        <div class="mini-logo text-center my-4">
                            <img src="../assets/images/logo.jpg" alt="" width="100" height="100" />
                            <h4 class="card-title mt-5">Buat akun Anda</h4>
                        </div>
                        <div class="auth-form card">
                            <div class="card-body">
                                <form id="registerForm" class="signin_validate row g-3">
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Nama" name="name" id="name"
                                            required />
                                    </div>
                                    <div class="col-12">
                                        <input type="email" class="form-control" placeholder="Email" name="email"
                                            id="email" required />
                                    </div>
                                    <div class="col-12">
                                        <input type="password" class="form-control" placeholder="Kata Sandi"
                                            name="password" id="password" required />
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="Alamat" name="alamat"
                                            id="alamat" required />
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="No telepon"
                                            name="notelepon" id="notelepon" required />
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            Buat akun
                                        </button>
                                    </div>
                                </form>
                                <p class="mt-3 mb-0">
                                    Sudah memiliki akun?
                                    <a class="text-primary" href="login.php">Masuk</a>
                                </p>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('proses_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // change to text to inspect the raw response
            .then(data => {
                // console.log('Raw response:', data); // Log the raw response
                const jsonData = JSON.parse(data); // Parse the JSON data
                if (jsonData.success) {
                    Swal.fire({
                        title: 'Registrasi Berhasil!',
                        icon: 'success'
                    }).then(() => {
                        location = 'login.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Registration Gagal',
                        text: jsonData.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
    </script>
</body>

</html>