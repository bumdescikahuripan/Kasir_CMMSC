<?php
// 1. Panggil koneksi (Mendukung pemanggilan dari sub-folder atau folder utama)
if (file_exists('koneksi.php')) {
    include('koneksi.php');
} else {
    include('../koneksi.php');
}

// 2. Ambil ID Pembayaran (Dibuat fleksibel agar bisa menangkap ?id= atau ?id_pembayaran=)
$id = 0;
if (isset($_GET['id_pembayaran'])) {
    $id = intval($_GET['id_pembayaran']);
} elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

// 3. Cek nama tabel pembayaran yang benar di database Anda (pembayarans_sewa ATAU pembayaran_sewa)
$table_check = mysqli_query($koneksi, "SHOW TABLES LIKE 'pembayaran_sewa'");
$nama_tabel_pembayaran = (mysqli_num_rows($table_check) > 0) ? 'pembayarans_sewa' : 'pembayaran_sewa';

// 4. Jalankan query dengan relasi INNER JOIN yang aman
$data = null;
if ($id > 0) {
    $query = mysqli_query($koneksi, "SELECT p.*, s.nama_penyewa, s.nama_aset 
                                     FROM $nama_tabel_pembayaran p 
                                     JOIN data_sewa s ON p.kode_sewa = s.kode_sewa 
                                     WHERE p.id_pembayaran = '$id'");
    if ($query) {
        $data = mysqli_fetch_assoc($query);
    }
}

// 5. Pengecekan jika data tidak ditemukan (Dilengkapi debug info agar Bapak tahu ID berapa yang dikirim)
if (!$data) {
    die("<div style='text-align:center; margin-top:50px; font-family: sans-serif; color: #c0392b;'>
            <h3>⚠️ Kwitansi Tidak Ditemukan!</h3>
            <p>Pastikan ID Pembayaran Benar atau Status Transaksi sudah tersimpan di database.</p>
            <p style='font-size:11px; color:gray;'>Debug Info System: ID yang dicari = <b>" . $id . "</b> pada tabel <b>" . $nama_tabel_pembayaran . "</b></p>
            <a href='javascript:history.back()' style='display:inline-block; margin-top:10px; padding:6px 12px; background:#34495e; color:white; text-decoration:none; border-radius:4px; font-size:12px;'>⬅️ Kembali</a>
         </div>");
}

// Fungsi Terbilang Otomatis untuk Kwitansi Resmi
function terbilangKwitansi($angka) {
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $terbilang = "";
    if ($angka < 12) { $terbilang = " " . $baca[$angka]; }
    else if ($angka < 20) { $terbilang = terbilangKwitansi($angka - 10) . " belas"; }
    else if ($angka < 100) { $terbilang = terbilangKwitansi($angka / 10) . " puluh" . terbilangKwitansi($angka % 10); }
    else if ($angka < 200) { $terbilang = " seratus" . terbilangKwitansi($angka - 100); }
    else if ($angka < 1000) { $terbilang = terbilangKwitansi($angka / 100) . " ratus" . terbilangKwitansi($angka % 100); }
    else if ($angka < 2000) { $terbilang = " seribu" . terbilangKwitansi($angka - 1000); }
    else if ($angka < 1000000) { $terbilang = terbilangKwitansi($angka / 1000) . " ribu" . terbilangKwitansi($angka % 1000); }
    else if ($angka < 1000000000) { $terbilang = terbilangKwitansi($angka / 1000000) . " juta" . terbilangKwitansi($angka % 1000000); }
    return $terbilang;
}

// Maping nama bulan Indonesia
$bln_indo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tgl_bayar = new DateTime($data['tanggal_bayar'] ?? 'now');
$format_tgl_bayar = $tgl_bayar->format('d') . ' ' . $bln_indo[(int)$tgl_bayar->format('m') - 1] . ' ' . $tgl_bayar->format('Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi_<?= $data['id_pembayaran']; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 14px; color: #000; background: #fff; }
        .kwitansi { width: 600px; border: 2px solid #000; padding: 25px; margin: 30px auto; background: #fff; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header strong { font-size: 16px; text-transform: uppercase; }
        
        .row-kwitansi { display: flex; margin-bottom: 8px; align-items: top; }
        .label-kwt { width: 180px; }
        .titik-kwt { width: 20px; }
        .isi-kwt { flex: 1; }
        
        .jumlah-box { background: #f2f2f2; padding: 10px; margin-top: 20px; border: 2px solid #000; font-size: 16px; font-weight: bold; display: inline-block; }
        .btn-print { background: #27ae60; color: #fff; padding: 10px 20px; border: none; cursor: pointer; font-weight: bold; font-size: 14px; border-radius: 4px; }
        @media print { .no-print { display: none; } .kwitansi { border: 2px solid #000; margin: 0 auto; } }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Kwitansi Resmi</button>
    </div>

    <div class="kwitansi">
        <div class="header">
            <strong>BUMDes CMMSC Cikahuripan</strong><br>
            <small>Unit Pengelolaan Aset & Jasa Pertokoan Desa</small>
        </div>
        
        <div class="row-kwitansi">
            <div class="label-kwt">No. Kwitansi</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt"><b><?= str_pad($data['id_pembayaran'], 5, '0', STR_PAD_LEFT); ?></b></div>
        </div>
        <div class="row-kwitansi">
            <div class="label-kwt">Tanggal Bayar</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt"><?= $format_tgl_bayar; ?></div>
        </div>
        
        <hr style="border: none; border-top: 1px dashed #000; margin: 15px 0;">
        
        <div class="row-kwitansi">
            <div class="label-kwt">Telah diterima dari</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt"><b><?= htmlspecialchars($data['nama_penyewa']); ?></b></div>
        </div>
        
        <div class="row-kwitansi">
            <div class="label-kwt">Untuk Pembayaran</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt"><i>Sewa <?= htmlspecialchars($data['nama_aset']); ?></i></div>
        </div>
        
        <div class="row-kwitansi">
            <div class="label-kwt">Keterangan</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt"><?= htmlspecialchars($data['keterangan'] ?? 'Pelunasan Sewa'); ?></div>
        </div>

        <div class="row-kwitansi">
            <div class="label-kwt">Terbilang</div>
            <div class="titik-kwt">:</div>
            <div class="isi-kwt" style="font-style: italic; text-transform: capitalize;">
                # <?= trim(terbilangKwitansi($data['jumlah_bayar'] ?? 0)); ?> Rupiah #
            </div>
        </div>

        <div class="jumlah-box">
            JUMLAH : Rp <?= number_format($data['jumlah_bayar'] ?? 0, 0, ',', '.'); ?>,-
        </div>

        <div style="margin-top: 40px; display: flex; justify-content: space-between;">
            <div style="text-align: center; width: 40%;">
                Penyetor,<br><br><br><br>
                ( ____________________ )
            </div>
            <div style="text-align: center; width: 40%;">
                Admin Kasir BUMDes,<br><br><br><br>
                ( <b><?= htmlspecialchars($data['petugas'] ?? 'Admin Kasir'); ?></b> )
            </div>
        </div>
    </div>

</body>
</html>