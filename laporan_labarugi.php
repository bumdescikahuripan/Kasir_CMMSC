<?php
// =========================================================================
// 1. ATUR PERIODE TANGGAL (DEFAULT: AWAL BULAN S/D AKHIR BULAN BERJALAN)
// =========================================================================
$tgl_mulai  = isset($_POST['tgl_mulai']) ? mysqli_real_escape_string($koneksi, $_POST['tgl_mulai']) : date('Y-m-01');
$tgl_sampai = isset($_POST['tgl_sampai']) ? mysqli_real_escape_string($koneksi, $_POST['tgl_sampai']) : date('Y-m-t');

// =========================================================================
// 2. DETEKTIF SINKRONISASI: MENCARI TABEL & KOLOM PENJUALAN YANG BENAR
// =========================================================================
$total_pendapatan = 0;

$kemungkinan_tabel = ['penjualan', 'data_penjualan', 'transaksi_penjualan', 'tb_penjualan', 'transaksi', 'detail_penjualan'];
$kemungkinan_kolom = ['total_bayar', 'total', 'total_harga', 'jumlah', 'grand_total', 'subtotal'];
$kemungkinan_tanggal = ['tanggal_jual', 'tanggal', 'tgl_transaksi', 'tgl'];

foreach ($kemungkinan_tabel as $tabel) {
    $cek_tabel = mysqli_query($koneksi, "SHOW TABLES LIKE '$tabel'");
    if ($cek_tabel && mysqli_num_rows($cek_tabel) > 0) {
        
        foreach ($kemungkinan_kolom as $kolom) {
            $cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tabel` LIKE '$kolom'");
            if ($cek_kolom && mysqli_num_rows($cek_kolom) > 0) {
                
                foreach ($kemungkinan_tanggal as $kolom_tgl) {
                    $cek_tgl = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tabel` LIKE '$kolom_tgl'");
                    if ($cek_tgl && mysqli_num_rows($cek_tgl) > 0) {
                        
                        // Jalankan query dengan filter tanggal
                        $q_omset = mysqli_query($koneksi, "SELECT SUM(`$kolom`) as total FROM `$tabel` WHERE DATE(`$kolom_tgl`) BETWEEN '$tgl_mulai' AND '$tgl_sampai'");
                        if ($q_omset) {
                            $d_omset = mysqli_fetch_assoc($q_omset);
                            $total_pendapatan = (float)($d_omset['total'] ?? 0);
                            if ($total_pendapatan > 0) {
                                break 3; // Keluar jika data valid ditemukan
                            }
                        }
                    }
                }
            }
        }
    }
}

// B. [PEMBELIAN] Total Kulakan / Belanja Stok Barang Dagangan
$q_pembelian = mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM data_pembelian WHERE DATE(tanggal_beli) BETWEEN '$tgl_mulai' AND '$tgl_sampai'");
$d_pembelian = $q_pembelian ? mysqli_fetch_assoc($q_pembelian) : null;
$total_pembelian = (float)($d_pembelian['total'] ?? 0);

// C. [OPERASIONAL] Total Biaya Pengeluaran Kas Umum
$q_ops = mysqli_query($koneksi, "SELECT SUM(kredit) as total FROM pengeluaran_kas WHERE DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_sampai'");
$d_ops = $q_ops ? mysqli_fetch_assoc($q_ops) : null;
$total_operasional = (float)($d_ops['total'] ?? 0);

// D. TOTAL PENGELUARAN GABUNGAN
$grand_total_pengeluaran = $total_pembelian + $total_operasional;

// E. FORMULA LABA SEJATI
$laba_bersih = $total_pendapatan - $grand_total_pengeluaran;
?>

<div class="container-fluid pt-3">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-0 text-gray-800 fw-bold"><i class="fa-solid fa-scale-balanced me-2 text-success"></i>Laporan Laba Rugi Akurat</h1>
            <p class="text-muted small mb-0">Sudah sinkron dengan metrik perhitungan real-time Dashboard Utama</p>
        </div>
        <p class="text-muted small">Standar Juknis Kementerian Desa PDTT</p>
    </div>

    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body">
            <form method="POST" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control form-control-sm" value="<?= $tgl_mulai; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control form-control-sm" value="<?= $tgl_sampai; ?>" required>
                </div>
                <div class="col-md-4 d-grid gap-2 d-md-block">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-filter me-1"></i> Saring Data</button>
                    <a href="print_laporan_resmi.php?tgl_mulai=<?= $tgl_mulai; ?>&tgl_sampai=<?= $tgl_sampai; ?>" target="_blank" class="btn btn-sm btn-success"><i class="fa-solid fa-print me-1"></i> Cetak Dokumen Resmi</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-light py-3">
            <h6 class="m-0 font-weight-bold text-dark fw-bold"><i class="fa-solid fa-list-check me-2"></i>Rincian Neraca Saldo Berjalan (Periode: <?= date('d M Y', strtotime($tgl_mulai)); ?> s/d <?= date('d M Y', strtotime($tgl_sampai)); ?>)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle">
                    <thead class="table-dark small text-uppercase">
                        <tr>
                            <th class="text-center" width="15%">Kode Rekening</th>
                            <th width="55%">Uraian Akun Keuangan</th>
                            <th class="text-end" width="30%">Subtotal / Nilai Kas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fw-bold bg-light">
                            <td class="text-center">1.0.0</td>
                            <td>PENDAPATAN USAHA</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">1.1.0</td>
                            <td class="ps-4 text-secondary">Total Omset Hasil Penjualan Kasir (Retail Unit Dagang)</td>
                            <td class="text-end text-success fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
                        </tr>

                        <tr class="fw-bold bg-light">
                            <td class="text-center">2.0.0</td>
                            <td>HARGA POKOK PENJUALAN (HPP) / BELANJA PRODUK</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">2.1.0</td>
                            <td class="ps-4 text-secondary">Biaya Pembelian / Kulakan Stok Barang Masuk Toko</td>
                            <td class="text-end text-danger">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
                        </tr>

                        <tr class="fw-bold bg-light">
                            <td class="text-center">3.0.0</td>
                            <td>BEBAN OPERASIONAL UNIT BISNIS</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="text-center">3.1.0</td>
                            <td class="ps-4 text-secondary">Pengeluaran Kas Umum Operasional (Gaji, Listrik, ATK, dll)</td>
                            <td class="text-end text-danger">Rp <?= number_format($total_operasional, 0, ',', '.'); ?></td>
                        </tr>

                        <tr class="fw-bold text-muted bg-light" style="font-size: 13px;">
                            <td class="text-center">-</td>
                            <td class="text-end">TOTAL BEBAN GABUNGAN (BELANJA + OPERASIONAL) :</td>
                            <td class="text-end text-danger">Rp <?= number_format($grand_total_pengeluaran, 0, ',', '.'); ?></td>
                        </tr>

                        <tr class="table-info fw-bold fs-5" style="border-top: 3px solid #000;">
                            <td class="text-center">4.0.0</td>
                            <td>SURPLUS / LABA BERSIH BERJALAN (NET PROFIT)</td>
                            <td class="text-end <?= $laba_bersih >= 0 ? 'text-dark' : 'text-danger'; ?>">
                                Rp <?= number_format($laba_bersih, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>