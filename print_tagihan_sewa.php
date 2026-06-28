<?php
// Mengamankan path pemanggilan file koneksi database
if (file_exists('koneksi.php')) {
    include('koneksi.php');
} else {
    include('../koneksi.php');
}

// Menangkap parameter kode_sewa dari URL (Contoh: ?kode_sewa=SEWA-XXXX)
$kode_sewa = isset($_GET['kode_sewa']) ? mysqli_real_escape_string($koneksi, $_GET['kode_sewa']) : '';

// Query pencarian data disesuaikan dengan database data_sewa
$query = mysqli_query($koneksi, "SELECT * FROM data_sewa WHERE kode_sewa = '$kode_sewa'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan, tampilkan pesan error
if (!$data) {
    die("<div style='color:red; font-family:sans-serif; padding:20px; text-align:center;'>
            <h3>⚠️ Data Tagihan Tidak Ditemukan</h3>
            <p>Gagal memuat data dengan kode sewa: <b>" . htmlspecialchars($kode_sewa) . "</b></p>
         </div>");
}

// 1. KALKULASI OTOMATIS JUMLAH BULAN TUNGGAKAN
$tgl_awal = new DateTime($data['tanggal_mulai']);
$tgl_sekarang = new DateTime(); // Hari ini

$selisih_bulan = (($tgl_sekarang->format('Y') - $tgl_awal->format('Y')) * 12) + ($tgl_sekarang->format('m') - $tgl_awal->format('m'));

// Pengaman jika selisih minus atau nol, minimal dihitung 1 bulan berjalan
if ($selisih_bulan <= 0) {
    $selisih_bulan = 1;
}

// Total Nominal Tunggakan (Jumlah Bulan x Tarif Sewa)
$tarif_sewa = $data['tarif_sewa'];
$total_tagihan = $selisih_bulan * $tarif_sewa;

// Fungsi Terbilang Otomatis untuk Angka Rupiah
function terbilang($angka) {
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}
$terbilang_final = ucwords(terbilang($total_tagihan)) . " Rupiah";

// Nama Bulan Bahasa Indonesia untuk Rentang Keterangan
$nama_bulan = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemberitahuan & Penagihan Sewa Aset - BUMDes CMMSC</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; background: #fff; padding: 20px; line-height: 1.6; font-size: 11pt; }
        .surat-box { max-width: 750px; margin: auto; padding: 10px 30px; }
        
        /* Susunan Kop Surat Resmi dengan Logo */
        .kop-container { display: flex; align-items: center; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 85px; height: 85px; margin-right: 20px; }
        .kop-teks { flex: 1; text-align: center; margin-right: 40px; } /* Margin kanan untuk penyeimbang posisi logo */
        .kop-teks h3 { margin: 0; font-size: 13pt; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }
        .kop-teks h2 { margin: 3px 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; line-height: 1.3; }
        .kop-teks p { margin: 2px 0 0 0; font-size: 9.5pt; font-style: italic; }
        
        .tabel-struktur { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabel-struktur td { vertical-align: top; padding: 2px 0; }
        
        .tabel-rincian { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .tabel-rincian th { border: 1px solid #000; padding: 8px; background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .tabel-rincian td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        
        .font-bold { font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .no-print-bar { background: #2c3e50; padding: 12px; text-align: center; margin-bottom: 20px; border-radius: 5px; }
        .btn-print { background: #e74c3c; color: white; border: none; padding: 8px 24px; font-weight: bold; cursor: pointer; border-radius: 4px; font-size: 11pt; }
        @media print { .no-print-bar { display: none; } body { padding: 0; } .surat-box { padding: 0; } }
    </style>
</head>
<body>

<div class="no-print-bar">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Surat Resmi Penagihan</button>
</div>

<div class="surat-box">
    <div class="kop-container">
        <img src="../assets/logo.png" class="kop-logo" onerror="this.src='https://i.ibb.co.com/yFmswV1D/logo-bumdes.png'">
        <div class="kop-teks">
            <h3>Pemerintah Desa Cikahuripan - Kecamatan Klapanunggal</h3>
            <h2>BUMDES CIKAHURIPAN MAKMUR MANDIRI<br>SEJAHTERA CIKAHURIPAN (CMMSC)</h2>
            <p>Jl. Raya Cikahuripan, Desa Cikahuripan, Kecamatan Klapanunggal, Kabupaten Bogor, Jawa Barat 16820</p>
        </div>
    </div>

    <table class="tabel-struktur">
        <tr>
            <td width="12%">Nomor</td>
            <td width="2%">:</td>
            <td width="48%">001/BUMDes-CMMSC/ST/<?= date('m/Y'); ?></td>
            <td width="38%" class="text-right">Bogor, <?= date('d') . ' ' . $nama_bulan[date('m')] . ' ' . date('Y'); ?></td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>Penting / Segera</td>
            <td></td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td class="font-bold">Surat Pemberitahuan & Penagihan Sewa Aset</td>
            <td></td>
        </tr>
    </table>

    <div style="margin-bottom: 20px;">
        Kepada Yth.<br>
        <span class="font-bold"><?= htmlspecialchars($data['nama_penyewa']); ?></span><br>
        Penyewa <?= htmlspecialchars($data['jenis_aset']); ?> (<?= htmlspecialchars($data['nama_aset']); ?>)<br>
        Di Tempat
    </div>

    <div style="text-align: justify; margin-bottom: 15px;">
        Dengan hormat,<br>
        Sehubungan dengan kesepakatan kontrak sewa aset milik Desa yang dikelola oleh BUMDes CMMSC, kami memberitahukan bahwa berdasarkan catatan administrasi keuangan kami, kewajiban pembayaran sewa untuk aset yang Bapak/Ibu gunakan saat ini telah melewati batas waktu jatuh tempo yang telah ditentukan.
    </div>

    <div style="text-align: justify; margin-bottom: 10px;">
        Adapun rincian komponen acuan tarif sewa berjalan adalah sebagai berikut:
    </div>

    <table class="tabel-rincian">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Kode Kontrak Acuan</th>
                <th>Uraian Objek Sewa Aset</th>
                <th width="30%">Nominal Tarif (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center"><?= htmlspecialchars($data['kode_sewa']); ?></td>
                <td>
                    Sewa <?= htmlspecialchars($data['nama_aset']); ?> (Skema <?= htmlspecialchars($data['tipe_sewa']); ?>)<br>
                    <small style="color: #333; font-style: italic;">Mulai tgl: <?= date('d-m-Y', strtotime($data['tanggal_mulai'])); ?> s/d <?= date('d-m-Y'); ?></small>
                </td>
                <td class="text-right"><?= $selisih_bulan; ?> x Rp <?= number_format($tarif_sewa, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-right">TOTAL TAGIHAN JATUH TEMPO:</td>
                <td class="font-bold text-right" style="background-color: #fafafa;">Rp <?= number_format($total_tagihan, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="font-bold" style="font-style: italic; font-size: 10pt; background-color: #fdfdfd;">
                    Terbilang: # <?= $terbilang_final; ?> #
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: justify; margin-bottom: 15px;">
        Meningat pentingnya kontribusi retribusi ini untuk pengelolaan fasilitas dan Pendapatan Asli Desa (PADes) Cikahuripan, kami mengharapkan kebijaksanaan Bapak/Ibu untuk dapat segera melakukan penyelesaian pembayaran di Kantor Sekretariat BUMDes CMMSC atau melalui petugas kasir resmi kami.
    </div>

    <div style="background-color: #fafafa; border: 1px dashed #000; padding: 10px; margin-bottom: 25px; font-size: 10pt;">
        <span class="font-bold" style="text-decoration: underline;">Catatan & Himbauan:</span>
        <ol style="margin: 5px 0 0 0; padding-left: 20px; text-align: justify;">
            <li>Jika Bapak/Ibu telah melakukan pembayaran sebelum surat ini diterima, mohon untuk mengabaikan surat pemberitahuan ini dan mengonfirmasikannya dengan membawa bukti kwitansi sah ke petugas kami.</li>
            <li>Batas waktu konfirmasi atau pelunasan adalah 3 (tiga) hari kerja sejak surat ini disampaikan.</li>
            <li><span class="font-bold" style="color: red;">Jika sampai akhir bulan ini tidak melakukan pembayaran, maka dengan sangat terpaksa Kami memutuskan aliran utilitas fasilitas atau meninjau ulang status hak sewa tempat guna penertiban.</span></li>
        </ol>
    </div>

    <div style="text-align: justify; margin-bottom: 35px;">
        Demikian surat pemberitahuan dan penagihan ini kami sampaikan. Atas perhatian, pengertian, dan kerja sama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.
    </div>

    <table style="width: 100%; border-collapse: collapse; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Mengetahui,<br>
                <span class="font-bold">Direktur BUMDes CMMSC</span>
                <br><br><br><br><br>
                ( _______________________ )
            </td>
            <td style="width: 50%; text-align: center;">
                Hormat Kami,<br>
                <span class="font-bold">Mgr. Unit Perdagangan & Jasa</span>
                <br><br><br><br><br>
                ( _______________________ )
            </td>
        </tr>
    </table>
</div>

</body>
</html>