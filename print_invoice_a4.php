<?php
// 1. KONEKSI KE DATABASE & SETTING WAKTU
// Menggunakan file koneksi.php yang berada satu folder di root
require_once 'koneksi.php'; 
date_default_timezone_set('Asia/Jakarta');

// 2. AMBIL NOMOR INVOICE DARI URL
$no_invoice = isset($_GET['faktur']) ? mysqli_real_escape_string($koneksi, $_GET['faktur']) : '';

if (empty($no_invoice)) {
    die("<div class='alert alert-danger m-3'>Format Nomor Invoice tidak valid Pak.</div>");
}

// 3. QUERY AMBIL DATA INDUK PENJUALAN
$query_utama = mysqli_query($koneksi, "SELECT * FROM data_penjualan WHERE no_invoice = '$no_invoice'");
$data_utama  = mysqli_fetch_assoc($query_utama);

if (!$data_utama) {
    die("<div class='alert alert-warning m-3'>Data Transaksi dengan Invoice <b>" . htmlspecialchars($no_invoice) . "</b> tidak ditemukan di database Pak.</div>");
}

// Format Tanggal Indonesia
$tanggal_indo = date('d F Y', strtotime($data_utama['tanggal_jual']));
$jatuh_tempo  = ($data_utama['metode_bayar'] == 'Tempo' && !empty($data_utama['jatuh_tempo'])) ? date('d F Y', strtotime($data_utama['jatuh_tempo'])) : '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Invoice - <?= htmlspecialchars($no_invoice); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f8f9fa; 
            color: #333; 
        }
        .invoice-box { 
            width: 210mm; 
            min-height: 297mm;
            margin: 20px auto; 
            padding: 25px; 
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .table-utama thead { 
            background-color: #111c24 !important; 
            color: #fff !important; 
        }
        .line-dan-border { 
            border-top: 3px double #333; 
            margin-top: 10px; 
            margin-bottom: 20px; 
        }
        
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                background-color: #fff; 
                padding: 0;
                margin: 0;
            }
            .invoice-box { 
                width: 100% !important; 
                max-width: 100% !important;
                margin: 0 !important; 
                padding: 10px !important; 
                box-shadow: none !important;
                border: none !important;
            }
            .table-utama thead th { 
                background-color: #111c24 !important; 
                color: #fff !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
            }
            @page {
                size: A4 portrait;
                margin: 15mm 10mm 15mm 10mm;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    
    <div class="d-flex justify-content-between align-items-center mb-4 no-print bg-light p-3 rounded border">
        <a href="index.php?page=data_penjualan" class="btn btn-secondary fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Data Penjualan
        </a>
        <button onclick="window.print();" class="btn btn-primary fw-bold">
            <i class="fa-solid fa-print me-1"></i> Cetak Struk / Dokumen
        </button>
    </div>

    <div class="row align-items-center">
        <div class="col-2 text-center">
            <img src="assets/logo.png" alt="Logo" style="max-height: 60px;" class="mb-2" onerror="this.src='https://cdn-icons-png.flaticon.com/512/4080/4080032.png'">
        </div>
        <div class="col-10">
            <h4 class="fw-bold text-uppercase m-0" style="letter-spacing: 0.5px;">Badan Usaha Milik Desa (BUMDes) CMMSC</h4>
            <h5 class="text-muted m-0 fw-semibold">Unit Usaha Perdagangan & Jasa</h5>
            <small class="text-secondary d-block mt-0.5">Jl. Raya Cikahuripan, Kecamatan Klapanunggal, Kabupaten Bogor, Jawa Barat</small>
        </div>
    </div>
    
    <div class="line-dan-border"></div>

    <div class="row mb-4">
        <div class="col-7">
            <h5 class="fw-bold text-primary text-uppercase mb-2">Invoice</h5>
            <table class="table table-sm table-borderless m-0 small">
                <tr>
                    <td class="p-0 text-muted" width="100px">No. Invoice</td>
                    <td class="p-0 fw-bold">: <?= htmlspecialchars($data_utama['no_invoice']); ?></td>
                </tr>
                <tr>
                    <td class="p-0 text-muted">Tanggal Jual</td>
                    <td class="p-0">: <?= $tanggal_indo; ?></td>
                </tr>
                <tr>
                    <td class="p-0 text-muted">Status</td>
                    <td class="p-0">: <span class="badge <?= $data_utama['status_bayar'] == 'Lunas' ? 'bg-success' : 'bg-danger'; ?>"><?= htmlspecialchars($data_utama['status_bayar']); ?></span></td>
                </tr>
            </table>
        </div>
        <div class="col-5 text-end">
            <span class="small text-muted d-block mb-1">Kepada Yth:</span>
            <h6 class="fw-bold text-dark m-0"><?= htmlspecialchars($data_utama['nama_pelanggan']); ?></h6>
            <?php if ($data_utama['metode_bayar'] == 'Tempo'): ?>
                <span class="badge bg-light text-danger fw-bold border border-danger mt-1 small">Jatuh Tempo: <?= $jatuh_tempo; ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-striped table-bordered align-middle table-utama small">
            <thead>
                <tr class="text-center align-middle">
                    <th width="5%">No</th>
                    <th width="45%">Nama Komoditas / Barang</th>
                    <th width="12%">Qty</th>
                    <th width="18%">Harga Satuan</th>
                    <th width="20%">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query_item = mysqli_query($koneksi, "SELECT dp.*, db.nama_barang FROM detail_penjualan dp 
                                                      JOIN data_barang db ON dp.id_barang = db.id_barang 
                                                      WHERE dp.no_invoice = '$no_invoice'");
                
                while ($item = mysqli_fetch_assoc($query_item)) {
                    // Hindari error pembagian dengan nol (division by zero)
                    $harga_satuan = ($item['jumlah'] > 0) ? ($item['subtotal'] / $item['jumlah']) : 0;
                ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="fw-semibold text-dark"><?= htmlspecialchars($item['nama_barang']); ?></td>
                        <td class="text-center"><?= number_format($item['jumlah'], 0, ',', '.'); ?> Pcs</td>
                        <td class="text-end">Rp <?= number_format($harga_satuan, 0, ',', '.'); ?></td>
                        <td class="text-end fw-bold">Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
                
                <tr class="table-light">
                    <td colspan="4" class="text-end fw-bold text-uppercase py-2 small">Total Tagihan (<?= htmlspecialchars($data_utama['metode_bayar'] == 'Tunai' ? 'Lunas' : 'Piutang'); ?>)</td>
                    <td class="text-end fw-bold text-primary py-2" style="font-size: 1rem;">Rp <?= number_format($data_utama['total_bayar'], 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row text-center mt-5 small justify-content-between">
        <div class="col-4">
            <p class="text-muted mb-5">Penerima / Pelanggan</p>
            <div class="mx-auto border-bottom border-secondary" style="width:120px; margin-top:60px;"></div>
            <small class="text-muted">( .................................... )</small>
        </div>

        <div class="col-4">
            <p class="text-muted mb-5">Dibuat Oleh,</p>
            <div class="mx-auto border-bottom border-secondary" style="width:120px; margin-top:60px;"></div>
            <small class="text-muted">( .................................... )</small>
        </div>

        <div class="col-4">
            <p class="text-muted mb-5">
                Bogor, <?= date('d M Y'); ?><br>
                Hormat Kami,
            </p>
            <div class="mx-auto border-bottom border-secondary" style="width:120px; margin-top:43px;"></div>
            <small class="fw-bold text-dark">( Mg. Unit Perdagangan & Jasa )</small>
        </div>
    </div>

</div>

<script>
    window.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            window.print();
        }, 500);
    });
</script>
</body>
</html>