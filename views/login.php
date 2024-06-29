<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png" />
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
                            <a href="./index.html"><img src="../assets/images/logo.jpg" alt="" width="100"
                                    height="100" /></a>
                            <h4 class="card-title mt-5">Sign in to Klinik Hewan</h4>
                        </div>
                        <div class="auth-form card">
                            <div class="card-body">
                                <form id="loginForm" class="signin_validate row g-3">
                                    <div class="col-12">
                                        <input type="email" class="form-control" placeholder="hello@example.com"
                                            name="email" id="email" />
                                    </div>
                                    <div class="col-12">
                                        <input type="password" class="form-control" placeholder="Password"
                                            name="password" id="password" />
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            Sign in
                                        </button>
                                    </div>
                                </form>
                                <p class="mt-3 mb-0">
                                    Don't have an account?
                                    <a class="text-primary" href="signup.html">Sign up</a>
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
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('proses_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // change to text to inspect the raw response
            .then(data => {
                console.log('Raw response:', data); // Log the raw response
                const jsonData = JSON.parse(data); // Parse the JSON data
                if (jsonData.success) {
                    Swal.fire({
                        title: 'Login Berhasil',
                        icon: 'success'
                    }).then(() => {
                        location = 'dashboard.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Login Gagal',
                        text: jsonData.message,
                        icon: 'error'
                    }).then(() => {
                        window.location.href = 'login.php'; // Mengarahkan langsung ke halaman login
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