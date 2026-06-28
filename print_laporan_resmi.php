<?php
// 1. Validasi Fleksibilitas File Koneksi Database
if (file_exists('koneksi.php')) {
    include('koneksi.php');
} else {
    include('../koneksi.php');
}

// Menangkap parameter periode (default ke bulan & tahun berjalan)
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// 2. SCANNING STRUKTUR TABEL data_penjualan SECARA AMAN
$kolom_tersedia = [];
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM data_penjualan");
if ($cek_kolom) {
    while ($k = mysqli_fetch_assoc($cek_kolom)) {
        $kolom_tersedia[] = strtolower($k['Field']);
    }
}

// Tentukan kolom omzet / total belanja (Utamakan nominal angka)
$field_total = '0';
foreach (['total_belanja', 'total_harga', 'total', 'grand_total', 'subtotal', 'harga', 'bayar'] as $t) {
    if (in_array($t, $kolom_tersedia)) {
        $field_total = $t;
        break;
    }
}

// Tentukan kolom realisasi kas / nominal bayar yang diterima kasir
$field_bayar = '0';
foreach (['bayar', 'jumlah_bayar', 'cash', 'terima', 'total'] as $b) {
    if (in_array($b, $kolom_tersedia)) {
        $field_bayar = $b;
        break;
    }
}

// Jika kolom bayar tetap tidak terdeteksi, samakan dengan field_total
if ($field_bayar == '0' && $field_total != '0') {
    $field_bayar = $field_total;
}

// Tentukan kolom tanggal penjualan secara spesifik
$field_tanggal = '';
foreach (['tgl_penjualan', 'tanggal', 'tgl', 'tgl_transaksi', 'created_at'] as $tg) {
    if (in_array($tg, $kolom_tersedia)) {
        $field_tanggal = $tg;
        break;
    }
}

// 3. STRATEGI QUERY FORMULASI AMAN (Mencegah Blank / Fatal Error)
if (!empty($field_tanggal) && $field_total !== '0') {
    $query_string = "SELECT 
        SUM($field_total) as total_omzet, 
        SUM($field_bayar) as total_kas 
        FROM data_penjualan 
        WHERE MONTH($field_tanggal) = '$bulan' AND YEAR($field_tanggal) = '$tahun'";
} else {
    // Jalankan Fallback Amortisasi jika tabel kasir kosong / tidak sesuai standar kolom
    $query_string = "SELECT 0 as total_omzet, 0 as total_kas FROM data_penjualan LIMIT 1";
}

$q_sum = mysqli_query($koneksi, $query_string);
$omzet = 0;
$kas_masuk = 0;

if ($q_sum) {
    $d = mysqli_fetch_assoc($q_sum);
    $omzet = $d['total_omzet'] ?? 0;
    $kas_masuk = $d['total_kas'] ?? 0;
}

$piutang = ($omzet > $kas_masuk) ? ($omzet - $kas_masuk) : 0;

// Alokasi Hasil Usaha sesuai Regulasi Kemendesa No. 15 Tahun 2021 (PADes 50%)
$pades_nominal = $kas_masuk * 0.50; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Usaha Bulanan BUMDes - Standar Kemendesa</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; line-height: 1.5; color: #000; background: #fff; padding: 30px; }
        .kop-surat { text-align: center; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 25px; }
        .kop-surat h2 { margin: 0; font-size: 16pt; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-surat h3 { margin: 5px 0 0 0; font-size: 11pt; font-weight: normal; }
        .kop-surat p { margin: 3px 0 0 0; font-size: 9pt; font-style: italic; }
        
        .judul-dokumen { text-align: center; font-size: 13pt; font-weight: bold; text-transform: uppercase; margin-bottom: 30px; text-decoration: underline; }
        
        .table-kemendes { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .table-kemendes th { border: 1px solid #000; padding: 10px 8px; font-size: 11pt; background-color: #f2f2f2; text-transform: uppercase; text-align: center; }
        .table-kemendes td { border: 1px solid #000; padding: 8px; font-size: 11pt; }
        
        .sub-total { font-weight: bold; background-color: #fafafa; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .ttd-wilayah { margin-top: 60px; width: 100%; border-collapse: collapse; }
        .ttd-box { width: 33%; text-align: center; font-size: 11pt; vertical-align: top; border: none !important; }
        
        .no-print-bar { background: #2c3e50; padding: 12px; text-align: center; margin-bottom: 20px; border-radius: 5px; }
        .btn-print { background: #27ae60; color: white; border: none; padding: 8px 24px; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 11pt; }
        @media print { .no-print-bar { display: none; } body { padding: 0; } }
    </style>
</head>
<body>

<div class="no-print-bar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Laporan Pertanggungjawaban Musdes</button>
</div>

<div class="kop-surat">
    <h2>Badan Usaha Milik Desa Cikahuripan Makmur Mandiri Sejahtera</h2>
    <h2>( BUMDes CMMSC Cikahuripan )</h2>
    <h3>Berdasarkan Permendesa PDTT No. 3 Tahun 2021 tentang Pendaftaran & Pengelolaan BUMDes</h3>
    <p>Kantor Sekretariat: Jl. Raya Cikahuripan, Desa Cikahuripan, Kec. Klapanunggal, Kabupaten Bogor, Jawa Barat 16820</p>
</div>

<div class="judul-dokumen">
    Laporan Realisasi Aktivitas Ekonomi & Hasil Usaha Bulanan<br>
    Periode Pelaporan: <?= $nama_bulan[$bulan] ?? $bulan; ?> <?= $tahun; ?>
</div>

<table class="table-kemendes">
    <thead>
        <tr>
            <th width="10%">Kode Rek.</th>
            <th>Uraian Komponen Pendapatan & Distribusi Hasil Usaha</th>
            <th width="25%">Jumlah Anggaran / Transaksi</th>
            <th width="25%">Realisasi Alokasi Kas</th>
        </tr>
    </thead>
    <tbody>
        <tr class="font-bold">
            <td>1.0</td>
            <td colspan="3">PENDAPATAN KOTOR (OMZET USAHA)</td>
        </tr>
        <tr>
            <td>1.1</td>
            <td>Total Pendapatan Transaksi Bruto (Omzet Sistem)</td>
            <td class="text-right">Rp <?= number_format($omzet, 0, ',', '.'); ?></td>
            <td class="text-right" style="color: #999;">-</td>
        </tr>
        <tr>
            <td>1.2</td>
            <td>Penerimaan Kas Bersih (Tunai Diterima)</td>
            <td class="text-right" style="color: #999;">-</td>
            <td class="text-right" style="font-weight: bold;">Rp <?= number_format($kas_masuk, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>1.3</td>
            <td>Piutang Dagang / Sewa Transaksi Berjalan</td>
            <td class="text-right">Rp <?= number_format($piutang, 0, ',', '.'); ?></td>
            <td class="text-right" style="color: #999;">-</td>
        </tr>
        <tr class="sub-total">
            <td>&nbsp;</td>
            <td>TOTAL REALISASI KAS BERSIH BUMDes</td>
            <td class="text-right" style="color: #999;">-</td>
            <td class="text-right" style="color: green;">Rp <?= number_format($kas_masuk, 0, ',', '.'); ?></td>
        </tr>

        <tr class="font-bold">
            <td style="padding-top: 15px;">2.0</td>
            <td colspan="3" style="padding-top: 15px;">DISTRIBUSI HASIL USAHA (PADes)</td>
        </tr>
        <tr>
            <td>2.1</td>
            <td>Kontribusi Pendapatan Asli Desa (PADes) - 25%</td>
            <td class="text-right" style="color: #999;">-</td>
            <td class="text-right">Rp <?= number_format($pades_nominal, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <td>2.2</td>
            <td>Dana Cadangan Umum Belanja Modal Eksternal - 30%</td>
            <td class="text-right" style="color: #999;">-</td>
            <td class="text-right">Rp <?= number_format($kas_masuk - $pades_nominal, 0, ',', '.'); ?></td>
        </tr>
        <tr class="sub-total">
            <td>&nbsp;</td>
            <td>TOTAL PERTANGGUNGJAWABAN ALOKASI</td>
            <td class="text-right" style="color: #999;">-</td>
            <td class="text-right">Rp <?= number_format($kas_masuk, 0, ',', '.'); ?></td>
        </tr>
    </tbody>
</table>

<p style="font-size: 9pt; font-style: italic; color: #555;">Dokumen ini sah dan diterbitkan secara elektronik melalui Aplikasi Konsolidasi Keuangan ERP Internal BUMDes CMMSC Cikahuripan.</p>

<table class="ttd-wilayah">
    <tr>
        <td class="ttd-box">
            Dibuat Oleh,<br>
            <strong>Bendahara / Kasir</strong>
            <br><br><br><br><br>
            ( _______________________ )
        </td>
        <td class="ttd-box">
            Menyetujui,<br>
            <strong>Direktur BUMDes CMMSC</strong>
            <br><br><br><br><br>
            ( _______________________ )
        </td>
        <td class="ttd-box">
            Mengetahui,<br>
            <strong>Kepala Desa Cikahuripan</strong><br>
            <span style="font-size: 8.5pt; font-weight: normal;">(Penasihat BUMDes)</span>
            <br><br><br><br>
            ( _______________________ )
        </td>
    </tr>
</table>

</body>
</html>