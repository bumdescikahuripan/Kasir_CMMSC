<?php
// Desain khusus printer kasir struk roll
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Nota Thermal</title>
    <style>
        @page { size: 80mm auto; margin: 0; }
        body { font-family: 'Courier New', Courier, monospace; width: 70mm; font-size: 12px; padding: 5mm; }
        .text-center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <strong>BUMDes CMMSC</strong><br>
        Desa Cikahuripan, Bogor<br>
        Telp: 0812-XXXX-XXXX
    </div>
    <div class="line"></div>
    No: STRK-2026052401<br>
    Kasir: Admin<br>
    Tanggal: 24/05/2026
    <div class="line"></div>
    1x Barang Contoh @15.000 -> 15.000
    <div class="line"></div>
    <strong>TOTAL : Rp 15.000</strong><br>
    TUNAI : Rp 20.000<br>
    KEMBALI: Rp  5.000
    <div class="line"></div>
    <div class="text-center">Terima Kasih Atas Kunjungan Anda!</div>
</body>
</html>