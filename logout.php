<?php
// 1. Jalankan session pelacakan
session_start();

// 2. Kosongkan semua data variabel session aktif
$_SESSION = array();

// 3. Hancurkan cookie session yang melekat pada browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hancurkan session yang tersimpan di memori server
session_destroy();

// 5. OTOMATIS DIALIKHAN KEMBALI KE HALAMAN LOGIN
header("Location: login.php");
exit;
?>