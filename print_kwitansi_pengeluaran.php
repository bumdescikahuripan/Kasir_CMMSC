<?php
// 1. INCLUDE KONEKSI DATABASE AGAR VARIABEL $koneksi TERSEDIA
include 'koneksi.php'; 

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

// 2. QUERY MENYESUAIKAN KOLOM TABEL ASLI BAPAK (id_pengeluaran)
$query = mysqli_query($koneksi, "SELECT * FROM pengeluaran_kas WHERE id_pengeluaran = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<h3>Data pengeluaran dengan ID '$id' tidak ditemukan di database Pak!</h3>";
    exit;
}

// Fungsi otomatis mengubah angka menjadi teks terbilang rupiah
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " Belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " Seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " Seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Voucher Pengeluaran Kas #<?= $data['id_pengeluaran']; ?></title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .wrapper { width: 100%; max-width: 800px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }
        .header { display: flex; align-items: center; justify-content: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 70px; height: 70px; margin-center: 0 auto 10 ; }
        .title-bumdes { font-size: 18px; font-weight: bold; text-align: center; }
        .subtitle-bumdes { font-size: 11px; text-align: center; color: #666; }
        .doc-title { text-align: center; font-size: 14px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        table.meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        table.meta-table td { padding: 4px 0; vertical-align: top; }
        .terbilang-box { background-color: #f9f9f9; border: 1px dashed #999; padding: 10px; margin-bottom: 15px; font-style: italic; font-weight: bold; }
        .jumlah-box { font-size: 16px; font-weight: bold; margin-bottom: 30px; }
        .ttd-container { display: flex; justify-content: space-between; margin-top: 5px; text-align: center; }
        .ttd-box { width: 30%; }
        .ttd-space { height: 70px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print();" style="padding: 8px 15px; background: #000; color: #fff; border: none; cursor: pointer; font-weight: bold;">Cetak Sekarang</button>
    </div>

    <div class="wrapper">
        <div class="header">
            <img src="assets/logo.png" alt="Logo" style="max-height: 65px;" class="mb-2" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
            <div>
                <div class="title-bumdes">BADAN USAHA MILIK DESA</div>
                <div class="title-bumdes">CIKAHURIPAN MAKMUR MANDIRI SEJAHTER CIKAHURIPAN (CMMSC)</div>
                <div class="subtitle-bumdes">Jl. Klapanunggal - Bojong,Kp. Cibeber RT. 019/008 Desa Cikahuripan Kec. Klapanunggal - Bogor</div>
                <div class="subtitle-bumdes">Email : bumdes.cmmsc@gmail.com  | WA : 082125415593</div>
            </div>
        </div>

        <div class="doc-title">VOUCHER PENGELUARAN KAS</div>

        <table class="meta-table">
            <tr>
                <td width="20%">No. ID Pengeluaran</td>
                <td width="2%">:</td>
                <td width="78%" style="font-family: monospace; font-weight: bold;"><?= $data['id_pengeluaran']; ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
            </tr>
            <tr>
                <td>Kategori Akun</td>
                <td>:</td>
                <td><strong><?= htmlspecialchars($data['nama_akun']); ?></strong></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['keterangan']); ?></td>
            </tr>
        </table>

        <div class="terbilang-box">
            Terbilang: # <?= trim(terbilang($data['kredit'])); ?> Rupiah #
        </div>

        <div class="jumlah-box">
            JUMLAH : Rp <?= number_format($data['kredit'], 0, ',', '.'); ?>
        </div>

        <div class="ttd-container">
            <div class="ttd-box">
                <div>Bendahara</div>
                <div class="ttd-space"></div>
                <div>( .................... )</div>
            </div>
            <div class="ttd-box">
                <div>Direktur</div>
                <div class="ttd-space"></div>
                <div>( .................... )</div>
            </div>
            <div class="ttd-box">
                <div>Penerima</div>
                <div class="ttd-space"></div>
                <div>( .................... )</div>
            </div>
        </div>
    </div>

</body>
</html>