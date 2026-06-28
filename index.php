<?php
// 1. PENGATURAN AWAL SISTEM
session_start();
include 'koneksi.php'; // Memanggil database langsung dari folder utama

// Proteksi Login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil data user yang sedang login untuk sidebar
$username_aktif = $_SESSION['username'] ?? 'Admin';
$role_aktif     = $_SESSION['role'] ?? 'Petugas';

// Cek halaman aktif saat ini untuk penanda menu
$page_aktif = $_SESSION['page'] ?? ($_GET['page'] ?? 'dashboard');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Terpadu - BUMDes CMMSC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #D3F5D3; }
        .wrapper { display: flex; align-items: stretch; min-height: 100vh; }
        /* SIDEBAR STYLE */
        #sidebar { min-width: 260px; max-width: 260px; background: #045C2C; color: #0298F2; transition: all 0.3s; }
        .sidebar-header { padding: 25px 20px; background: #0F6F8C; text-align: center; border-bottom: 1px solid #09B4E8; }
        .sidebar-header img { 
    width: 70px; 
    height: 70px; 
    border-radius: 50%; 
    object-fit: cover; 
    background: #ffffff; 
    padding: 4px; 
    border: 2px solid #2FAB29; 
    display: block; 
    margin: 0 auto 10px auto; 
}
        .sidebar-header h5 { font-size: 15px; font-weight: 700; margin: 0; letter-spacing: 0.5px; }
        .user-profile { padding: 8px 10px; background: #040201; font-size: 13px; display: flex; align-items: center; }
        .user-profile i { font-size: 10px; color: #0DB80D; margin-right: 8px; }
        .menu-section { padding: 15px 20px 5px 20px; font-size: 11px; text-transform: uppercase; font-weight: 700; color: #2738F5; letter-spacing: 1px; }
        ul.components { padding: 5px 10px; list-style: none; }
        ul.components li a { padding: 12px 15px; font-size: 14px; display: block; color: #EEF527; text-decoration: none; border-radius: 6px; margin-bottom: 4px; transition: 0.2s; }
        ul.components li a:hover, ul.components li.active > a { background: #06A19A; color: #EEF527; }
        ul.components li a i { margin-right: 12px; width: 20px; text-align: center; }
        
        /* SUBMENU DROPDOWN STYLE */
        .sub-menu-container { background: #0b1319; border-radius: 6px; padding: 4px 0; margin-bottom: 4px; }
        .sub-menu-container a { padding: 10px 15px 10px 45px !important; font-size: 13.5px !important; color: #EBEB09 !important; }
        .sub-menu-container a:hover, .sub-menu-container a.sub-active { color: #fff !important; background: #16242f !important; }
        .btn-toggle-menu[aria-expanded="true"] i.fa-chevron-down { transform: rotate(180deg); }
        .btn-toggle-menu i.fa-chevron-down { transition: transform 0.2s; }
        
        /* CONTENT STYLE */
        #content { width: 100%; padding: 30px; min-height: 100vh; }
        .sidebar {
    width: 250px;
    height: 100vh;
    padding: 20px;
    color: white;
    animation: gantiWarna 10s infinite;
}

/* Animasi warna */
@keyframes gantiWarna {
    0% {
        background: #198754;
    }
    25% {
        background: #0d6efd;
    }
    50% {
        background: #dc3545;
    }
    75% {
        background: #fd7e14;
    }
    100% {
        background: #198754;
    }
}
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="assets/logo.png" alt="Logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
            <marquee behavior="scroll" direction="left">
              <h5>BUMDes CMMSC Desa Cikahuripan, Klapanunggal-Bogor</h5>
            </marquee>
        </div>
        
        <div class="user-profile">
            <div>
                <i class="fa-solid fa-circle"></i><strong><?= htmlspecialchars($username_aktif); ?></strong> 
                <span class="text-muted d-block" style="font-size: 11px;"><?= htmlspecialchars($role_aktif); ?></span>
            </div>
        </div>

        <ul class="components">
            <li class="<?= ($page_aktif == 'dashboard') ? 'active' : ''; ?>">
                <a href="index.php?page=dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            </li>
            
            <div class="menu-section">Data Master</div>
            <li class="<?= ($page_aktif == 'barang') ? 'active' : ''; ?>">
                <a href="index.php?page=barang"><i class="fa-solid fa-box"></i> Data Barang</a>
            </li>
            <li class="<?= ($page_aktif == 'pembelian') ? 'active' : ''; ?>">
                <a href="index.php?page=pembelian"><i class="fa-solid fa-cart-shopping"></i> Data Pembelian</a>
                <a href="index.php?page=purchase_order" class="nav-link text-yellow py-2 d-block">
                <i class="fa-solid fa-file-contract me-2"></i> Purchase Order (PO)</a>
            </li>
            <li class="<?= ($page_aktif == 'data_penjualan' || $page_aktif == 'edit_penjualan') ? 'active' : ''; ?>">
                <a href="index.php?page=data_penjualan"><i class="fa-solid fa-file-invoice-dollar"></i> Data Penjualan</a>
            </li>
            
            <div class="menu-section">Transaksi</div>
            <li class="<?= ($page_aktif == 'kasir') ? 'active' : ''; ?>">
                <a href="index.php?page=kasir"><i class="fa-solid fa-cash-register"></i> Aplikasi Kasir</a>
            </li>
            <li class="<?= ($page_aktif == 'keuangan') ? 'active' : ''; ?>">
                <a href="index.php?page=keuangan"><i class="fa-solid fa-wallet"></i> Modul Keuangan</a>
            </li>
            <li class="<?= ($page_aktif == 'penyewaan kios & lapangan') ? 'active' : ''; ?>">
            <a href="index.php?page=sewa" class="nav-link text-yellow py-2 d-block">
            <i class="fa-solid fa-house-laptop me-2"></i> Penyewaan Kios & Lapangan
            </a>
            </li>
            <div class="menu-section">Sistem</div>
            <li class="<?= ($page_aktif == 'pengguna') ? 'active' : ''; ?>">
                <a href="#" class="btn-toggle-menu d-flex align-items-center justify-content-between" data-bs-toggle="collapse" data-bs-target="#pengaturanSubmenu" aria-expanded="<?= ($page_aktif == 'pengguna') ? 'true' : 'false'; ?>">
                    <span><i class="fa-solid fa-gear"></i> Modul Pengaturan</span>
                    <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                </a>
                <div class="collapse <?= ($page_aktif == 'pengguna') ? 'show' : ''; ?>" id="pengaturanSubmenu">
                    <div class="sub-menu-container">
                        <a href="index.php?page=pengguna" class="<?= ($page_aktif == 'pengguna') ? 'sub-active' : ''; ?>">
                            <i class="fa-solid fa-users-gear me-2"></i>Data Pengguna
                        </a>
                        <a href="print_laporan_resmi.php" target="_blank" class="btn btn-primary">
    <i class="fa-solid fa-file-pdf"></i> Cetak Laporan LPJ Resmi
</a>
                    </div>
                </div>
            </li>
            
            <li>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar Pak?');" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
            </li>
        </ul>
    </nav>

    <div id="content">
        <div class="container-fluid">
            <?php
            // ROUTING SISTEM AMAN - Otomatis mendeteksi parameter ?page=
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $page = $_GET['page'];
                $file_target = "pages/" . $page . ".php";

                // Cek apakah file modulnya ada di dalam folder pages
                if (file_exists($file_target)) {
                    include $file_target;
                } else {
                    // Jika file fisiknya belum dibuat di folder pages/
                    echo "
                    <div class='alert alert-danger shadow-sm border-start border-danger border-4 rounded-3 d-flex align-items-center' role='alert'>
                        <i class='fa-solid fa-triangle-exclamation fs-3 me-3'></i>
                        <div>
                            <h5 class='alert-heading fw-bold mb-1'>Modul Belum Terpasang Pak!</h5>
                            <p class='mb-0 small text-secondary'>File <code>pages/{$page}.php</code> belum ditemukan atau belum dibuat di dalam folder project Anda.</p>
                        </div>
                    </div>";
                }
            } else {
                // Halaman default jika baru pertama masuk sistem (Dashboard)
                $file_dashboard = "pages/dashboard.php";
                if (file_exists($file_dashboard)) {
                    include $file_dashboard;
                } else {
                    echo "
                    <div class='p-5 mb-4 bg-white rounded-3 shadow-sm border'>
                        <div class='container-fluid py-3'>
                            <h1 class='display-6 fw-bold text-dark'>Selamat Datang di Portal BUMDes</h1>
                            <p class='col-md-8 fs-6 text-muted'>Sistem manajemen toko unit usaha perdagangan terintegrasi. Pilih menu di sebelah kiri untuk mengelola operasional barang dan kasir.</p>
                        </div>
                    </div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>