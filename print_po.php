<?php
include 'koneksi.php';

$no_po = isset($_GET['no_po']) ? mysqli_real_escape_string($koneksi, $_GET['no_po']) : '';
$query_po = mysqli_query($koneksi, "SELECT * FROM data_po WHERE no_po = '$no_po'");
$po = mysqli_fetch_assoc($query_po);

if (!$po) {
    die("Dokumen Purchase Order tidak ditemukan di sistem internet/lokal.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - <?= $po['no_po']; ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 13px; color: #000; padding: 10px; }
        .page-wrapper { width: 100%; max-width: 800px; margin: 0 auto; }
        .kop-surat { display: flex; align-items: center; border-bottom: 4px double #000; padding-bottom: 12px; margin-bottom: 20px; }
        .kop-logo { width: 80px; height: 80px; margin-right: 20px; }
        .kop-teks { text-align: center; flex-grow: 1; margin-right: 80px; }
        .kop-teks h2 { font-size: 16px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .kop-teks h1 { font-size: 20px; font-weight: bold; margin: 3px 0; text-transform: uppercase; }
        .kop-teks p { font-size: 11px; font-style: italic; margin: 0; }
        
        .po-title { text-align: center; font-size: 16px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .meta-container { display: flex; justify-content: space-between; margin-bottom: 20px; line-height: 1.4; }
        .meta-box { width: 48%; }
        
        .table-items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-items th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .table-items th, .table-items td { border: 1px solid #000; padding: 6px 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .ttd-container { display: flex; justify-content: space-between; text-align: center; margin-top: 40px; }
        .ttd-box { width: 30%; }
        .ttd-space { height: 70px; }
        
        @media print { .btn-print { display: none; } }
    </style>
</head>
<body>

    <button onclick="window.print();" class="btn-print" style="padding: 6px 12px; background: #27ae60; color:#fff; border:none; margin-bottom:15px; cursor:pointer; font-weight:bold;">Cetak Dokumen PO</button>

    <div class="page-wrapper">
        <div class="kop-surat">
            <img src="assets/logo.png" class="kop-logo" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
            <div class="kop-teks">
                <h2>Pemerintah Kabupaten Bogor</h2>
                <h2>Kecamatan Klapanunggal - Desa Cikahuripan</h2>
                <h1>BUMDes CMMSC</h1>
                <p>Jl. Klapanunggal - Bojong, Cikahuripan, Bogor, Jawa Barat 16820</p>
            </div>
        </div>

        <div class="po-title">SURAT PESANAN BARANG (PURCHASE ORDER)</div>

        <div class="meta-container">
            <div class="meta-box">
                <strong>Kepada Yth Supplier:</strong><br>
                <?= htmlspecialchars($po['nama_supplier']); ?><br>
                <?= nl2br(htmlspecialchars($po['alamat_supplier'])); ?>
            </div>
            <div class="meta-box" style="text-align: right;">
                <strong>No. Dokumen:</strong> <span style="font-family: monospace; font-size:14px; font-weight:bold;"><?= $po['no_po']; ?></span><br>
                <strong>Tanggal Order:</strong> <?= date('d F Y', strtotime($po['tanggal_po'])); ?><br>
                <strong>Status Ajuan:</strong> Resmi (Sistem BUMDes)
            </div>
        </div>

        <p>Dengan hormat, bersama surat ini kami mengajukan pesanan barang kebutuhan retail unit usaha BUMDes CMMSC dengan rincian item sebagai berikut:</p>

        <table class="table-items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="50%">Nama Barang / Spesifikasi Produk</th>
                    <th width="12%">Volume Qty</th>
                    <th width="13%">Satuan</th>
                    <th width="20%">Est. Harga (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q_det = mysqli_query($koneksi, "SELECT * FROM detail_po WHERE no_po = '$no_po'");
                while($det = mysqli_fetch_assoc($q_det)):
                ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= htmlspecialchars($det['nama_barang']); ?></td>
                    <td class="text-center"><?= $det['jumlah']; ?></td>
                    <td class="text-center"><?= htmlspecialchars($det['satuan']); ?></td>
                    <td class="text-right">Rp <?= number_format($det['harga_estimasi'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr style="font-weight: bold; background-color: #fafafa;">
                    <td colspan="4" class="text-right">TOTAL PERKIRAAN PEMBAYARAN :</td>
                    <td class="text-right">Rp <?= number_format($po['total_estimasi'], 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 12px; font-style:italic;">*Catatan: Segala bentuk penyesuaian harga atau ketidaktersediaan stok barang mohon dikonfirmasikan kepada manajemen BUMDes CMMSC sebelum pengiriman dilakukan.</p>

        <div class="ttd-container">
            <div class="ttd-box">
                <div>Diajukan Oleh,</div>
                <div style="font-weight: bold;">Manajer Unit Perdagangan</div>
                <div class="ttd-space"></div>
                <div>( <?= htmlspecialchars($po['dibuat_oleh']); ?> )</div>
            </div>
            <div class="ttd-box">
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">Direktur BUMDes CMMSC</div>
                <div class="ttd-space"></div>
                <div>( .................................... )</div>
            </div>
            <div class="ttd-box">
                <div>Diterima & Disetujui,</div>
                <div style="font-weight: bold;">Pihak Supplier / Vendor</div>
                <div class="ttd-space"></div>
                <div>( .................................... )</div>
            </div>
        </div>
    </div>
</body>
</html>