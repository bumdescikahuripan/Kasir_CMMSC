<?php
session_start();

// OTOMATIS MENCARI FILE KONEKSI AGAR TIDAK ERROR FAILED TO OPEN STREAM
if (file_exists('config/koneksi.php')) {
    include 'config/koneksi.php';
} elseif (file_exists('../config/koneksi.php')) {
    include '../config/koneksi.php';
} else {
    // Jika masih tidak ketemu, kita buatkan koneksi cadangan langsung agar aplikasi tidak mati
    $koneksi = mysqli_connect("localhost", "root", "", "db_toko_bumdes");
}

if (!isset($koneksi) || !$koneksi) {
    echo "Gagal menyambungkan database lokal XAMPP Pak, silakan cek file config/koneksi.php";
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Transaksi tidak ditemukan Pak!'); window.close();</script>";
    exit;
}

$id_target = mysqli_real_escape_string($koneksi, $_GET['id']);
$jenis     = isset($_GET['jenis']) ? mysqli_real_escape_string($koneksi, $_GET['jenis']) : 'penjualan';

$judul_dokumen  = "";
$no_nota        = "";
$tanggal_raw    = "";
$pihak_terkait  = "";
$total_uang     = 0;
$is_pengeluaran = false;
$keterangan_opsional = "";
$status_nota    = "Diproses";

if ($jenis == 'pengeluaran') {
    // AMBIL DATA PEMBELIAN SEKALIGUS KOLOM NAMA_SUPPLIER DAN METODE_BAYAR TERBARU
    $query = mysqli_query($koneksi, "SELECT p.*, b.nama_barang FROM data_pembelian p 
                                      LEFT JOIN data_barang b ON p.id_barang = b.id_barang 
                                      WHERE p.id_pembelian = '$id_target'");
    $data  = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>alert('Data pengeluaran pembelian tidak ditemukan Pak!'); window.close();</script>";
        exit;
    }
    
    $is_pengeluaran = true;
    $no_nota        = $data['no_faktur'];
    $pihak_terkait  = (!empty($data['nama_supplier'])) ? $data['nama_supplier'] : "Supplier / Vendor Toko";
    $total_uang     = $data['total_harga'];
    $tanggal_raw    = $data['tanggal_beli'];
    $keterangan_opsional = "Pembelian komoditas stok: " . $data['nama_barang'] . " sebanyak " . $data['jumlah'] . " unit.";
    
    if (isset($data['metode_bayar']) && $data['metode_bayar'] == 'Tempo') {
        $judul_dokumen = "NOTA BON PEMBELIAN TEMPO (HUTANG USAHA)";
        $status_nota   = isset($data['status_bayar']) ? $data['status_bayar'] : "Belum Lunas";
    } else {
        $judul_dokumen = "BUKTI PENGELUARAN KAS (PEMBELIAN TUNAI)";
        $status_nota   = "Lunas";
    }

} else {
    // UNTUK PENJUALAN UMUM MAUPUN NOTA PIUTANG TEMPO
    $query = mysqli_query($koneksi, "SELECT * FROM data_penjualan WHERE id_penjualan = '$id_target'");
    $data  = mysqli_fetch_assoc($query);
    
    if (!$data) {
        echo "<script>alert('Data transaksi penjualan tidak ditemukan Pak!'); window.close();</script>";
        exit;
    }
    
    if ($jenis == 'piutang') {
        $judul_dokumen  = "KWITANSI PELUNASAN PIUTANG KONSUMEN";
        $keterangan_opsional = "Penerimaan dana kas atas pelunasan tagihan piutang toko ritel desa jatuh tempo.";
        $status_nota    = "Lunas (Pelunasan)";
    } else {
        if ($data['status_bayar'] == 'Tempo' || $data['metode_bayar'] == 'Tempo') {
            $judul_dokumen = "INVOICE PENJUALAN TEMPO";
            $status_nota   = "Belum Lunas";
        } else {
            $judul_dokumen = "KWITANSI PEMBAYARAN TOKO (LUNAS)";
            $status_nota   = "Lunas";
        }
    }
    
    $no_nota        = $data['no_invoice'];
    $pihak_terkait  = $data['nama_pelanggan'];
    $total_uang     = $data['total_bayar']; 
    $tanggal_raw    = $data['tanggal_jual'];
    $is_pengeluaran = false;
}

$tanggal_indo = date('d F Y', strtotime($tanggal_raw));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nota #<?= $no_nota; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #ffffff; color: #212529; }
        .kwitansi-box { max-width: 850px; margin: 20px auto; padding: 35px; border: 1px solid #dee2e6; border-radius: 8px; }
        .kop-title { font-size: 19px; font-weight: 700; }
        .garis-tebal { border-top: 3px solid #212529; margin-top: 10px; margin-bottom: 25px; opacity: 1; }
        .judul-kwitansi { font-weight: 800; color: <?= $is_pengeluaran ? '#dc3545' : '#0d6efd'; ?>; font-size: 19px; }
        @media print { 
            .no-print { display: none !important; } 
            .kwitansi-box { border: none !important; padding: 0 !important; margin: 0 !important; shadow: none !important; } 
        }
    </style>
</head>
<body>

<div class="container no-print mt-4 text-center" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border">
        <button onclick="window.close();" class="btn btn-secondary fw-bold btn-sm"><i class="fa-solid fa-circle-xmark me-1"></i> Tutup Halaman</button>
        <button onclick="window.print();" class="btn btn-primary fw-bold btn-sm"><i class="fa-solid fa-print me-1"></i> Cetak Sekarang</button>
    </div>
</div>

<div class="container">
    <div class="kwitansi-box shadow-sm my-3">
        <div class="row align-items-center">
            <div class="col-2 text-center">
                <img src="assets/logo.png" alt="Logo BUMDes" style="max-height: 70px;" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
            </div>
            <div class="col-10">
                <div class="kop-title text-uppercase">Badan Usaha Milik Desa (BUMDes) CMMSC</div>
                <div class="fw-bold text-secondary small">Unit Usaha Perdagangan & Jasa</div>
                <div class="small text-muted">Jl. Klapanunggal - Bojong RT. 019/008 Desa Cikahuripan Kec. Klapanunggal - Bogor</div>
            </div>
        </div>
        
        <div class="garis-tebal"></div>

        <div class="row mb-4">
            <div class="col-md-7">
                <div class="judul-kwitansi text-uppercase mb-2"><?= $judul_dokumen; ?></div>
                <table class="table table-borderless table-sm small mb-0">
                    <tr>
                        <td width="35%" class="text-muted">No. Faktur / Invoice</td>
                        <td>: <span class="font-monospace fw-bold text-dark"><?= $no_nota; ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Transaksi</td>
                        <td>: <?= $tanggal_indo; ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Nota</td>
                        <td>: <span class="badge bg-dark text-uppercase"><?= $status_nota; ?></span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5 text-md-end">
                <div class="text-muted small"><?= $is_pengeluaran ? 'Diberikan Kepada Supplier:' : 'Kepada Yth:'; ?></div>
                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($pihak_terkait); ?></h5>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle small">
                <thead class="text-center table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Barang / Komoditas Deskripsi</th>
                        <th width="20%">Jumlah</th>
                        <th width="25%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_bersih_hitung = 0;

                    // Mengambil item rinci hanya jika tipe transaksi adalah penjualan retail umum
                    if ($jenis != 'pengeluaran' && $jenis != 'piutang') {
                        $q_item = mysqli_query($koneksi, "SELECT d.*, b.nama_barang, b.satuan FROM detail_penjualan d 
                                                          LEFT JOIN data_barang b ON d.id_barang = b.id_barang 
                                                          WHERE d.no_invoice = '$no_nota'");
                        if ($q_item && mysqli_num_rows($q_item) > 0) {
                            $no_item = 1;
                            while ($item = mysqli_fetch_assoc($q_item)) {
                                $total_bersih_hitung += $item['subtotal'];
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no_item++; ?></td>
                                    <td><?= htmlspecialchars($item['nama_barang'] ?? 'Komoditas Toko'); ?></td>
                                    <td class="text-center"><?= $item['jumlah']; ?> <?= $item['satuan'] ?? 'Pcs'; ?></td>
                                    <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }

                    // Jika item rinci kosong (atau transaksi pembelian grosir/pelunasan piutang), pakai ringkasan akumulasi
                    if ($total_bersih_hitung == 0) {
                        $total_bersih_hitung = $total_uang;
                        ?>
                        <tr>
                            <td class="text-center">1</td>
                            <td><?= !empty($keterangan_opsional) ? $keterangan_opsional : 'Pencatatan Ritel Dokumen Administrasi Toko BUMDes'; ?></td>
                            <td class="text-center">1 Paket</td>
                            <td class="text-end">Rp <?= number_format($total_bersih_hitung, 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class="table-light fs-6 fw-bold">
                        <td colspan="3" class="text-end text-uppercase">Total Keseluruhan</td>
                        <td class="text-end <?= $is_pengeluaran ? 'text-danger' : 'text-primary'; ?>">Rp <?= number_format($total_bersih_hitung, 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 pt-2 small">
            <div class="col-6 text-center">
                <p class="mb-5 text-muted"><?= $is_pengeluaran ? 'Penerima / Supplier' : 'Pelanggan / Konsumen'; ?></p>
                <p class="border-top d-inline-block px-4 pt-1 fw-bold">( <?= htmlspecialchars($pihak_terkait); ?> )</p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-5 text-muted">Bogor, <?= date('d F Y'); ?><br>Hormat Kami, Pengelola</p>
                <p class="border-top d-inline-block px-4 pt-1 fw-bold">( Petugas BUMDes CMMSC )</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>