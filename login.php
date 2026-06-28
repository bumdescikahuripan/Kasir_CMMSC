<?php
// Jalankan session sistem
session_start();
include 'koneksi.php'; // Memanggil file koneksi database

// Jika pengguna sudah dalam posisi login, langsung lempar ke dashboard utama
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: index.php");
    exit;
}

// Proses verifikasi data saat tombol login diklik
if (isset($_POST['login_proses'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Query mencari username sesuai database asli (tabel: data_pengguna)
        $query = mysqli_query($koneksi, "SELECT * FROM data_pengguna WHERE username = '$username'");
        
        if (mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);
            
            // Verifikasi password hash Bcrypt sesuai data database Bapak
            if (password_verify($password, $row['password'])) {
                
                // Set semua variabel session penting untuk proteksi & hak akses menu
                $_SESSION['login']    = true;
                $_SESSION['id_user']  = $row['id_pengguna'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['nama']     = $row['nama_lengkap']; // Menggunakan nama_lengkap sesuai db
                $_SESSION['role']     = $row['role'];         // Menggunakan role sesuai db Pak

                echo "<script>alert('Selamat Datang Kembali Pak " . htmlspecialchars($row['nama_lengkap']) . "!'); window.location.href='index.php';</script>";
                exit;
            } else {
                $error_msg = "Password yang Bapak masukkan salah!";
            }
        } else {
            $error_msg = "Username tidak terdaftar dalam sistem Pak!";
        }
    } else {
        $error_msg = "Mohon isi username dan password terlebih dahulu!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BUMDes CMMSC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #5dee13; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; background: #ffffff; border-radius: 12px; border: none; }
        .brand-header { background: #111c24; color: #fff; text-align: center; padding: 30px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
        .brand-header img { max-height: 60px; margin-bottom: 10px; }
        .brand-header h4 { font-size: 16px; font-weight: 700; margin: 0; letter-spacing: 0.5px; }
        .btn-login { background: #111c24; color: #fff; border: none; font-weight: 600; padding: 10px; transition: 0.2s; }
        .btn-login:hover { background: #1e303d; color: #fff; }
    </style>
</head>
<body>

<div class="card login-card shadow-lg m-3">
    <div class="brand-header">
        <img src="assets/logo.png" alt="Logo bumdes" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
        <h4>PORTAL KASIR BUMDES CMMSC</h4>
    </div>
    <div class="card-body p-4">
        
        <?php if (isset($error_msg)) : ?>
            <div class="alert alert-danger small py-2 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> <?= $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-secondary">Username Login</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-user small"></i></span>
                    <input type="text" name="username" class="form-control bg-light border-start-0 small" placeholder="Masukkan username" required autocomplete="off">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock small"></i></span>
                    <input type="password" name="password" class="form-control bg-light border-start-0 small" placeholder="******" required>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" name="login_proses" class="btn btn-login"><i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Ke Sistem</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>