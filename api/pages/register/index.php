<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../model/user.php';

$error = '';
$success = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['display_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $retype = $_POST['retype_password'] ?? '';
    $agree = $_POST['terms'] ?? '';

    // Enhanced validation
    if (!$name || !$email || !$password || !$retype) {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $retype) {
        $error = 'Password tidak cocok.';
    } elseif ($agree !== 'agree') {
        $error = 'Anda harus menyetujui terms.';
    } else {
        try {
            add_user($email, $password, $name);
            
            // More secure cookie setting
            // setcookie("user", $email, [
            //     'expires' => time() + 3600,
            //     'path' => '/',
            //     'secure' => isset($_SERVER['HTTPS']),
            //     'httponly' => true,
            //     'samesite' => 'Strict'
            // ]);
            
            // header("Location: /dashboard");
            // exit;
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error = 'Gagal registrasi: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
</head>

<body class="register-page" style="min-height: 570px;">
    <div class="register-box">
        <div class="register-logo">
            <a href="#"><b>Teman</b>Usaha</a>
        </div>
        <?php echo $error?>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Buat Akun Baru</p>

                <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="display_name" class="form-control" placeholder="Full name" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="retype_password" class="form-control" placeholder="Retype password"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                                <label for="agreeTerms">
                                    Saya setuju dengan <a href="#">syarat & ketentuan</a>
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                <a href="/login" class="text-center">Sudah punya akun? Login</a>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/adminlte/dist/js/adminlte.min.js"></script>
</body>

</html>