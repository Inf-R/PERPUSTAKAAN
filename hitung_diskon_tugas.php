<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perhitungan Diskon - Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Sistem Perhitungan Diskon Bertingkat</h1>

    <?php
    // Data
    $nama_pembeli = "Rangga";
    $judul_buku = "Laravel Advanced";
    $harga_satuan = 150000;
    $jumlah_beli = 4;
    $is_member = true;

    // Subtotal
    $subtotal = $harga_satuan * $jumlah_beli;

    // Diskon berdasarkan jumlah
    if ($jumlah_beli <= 2) {
        $persentase_diskon = 0;
    } elseif ($jumlah_beli <= 5) {
        $persentase_diskon = 10;
    } elseif ($jumlah_beli <= 10) {
        $persentase_diskon = 15;
    } else {
        $persentase_diskon = 20;
    }

    // Diskon utama
    $diskon = $subtotal * ($persentase_diskon / 100);

    // Setelah diskon pertama
    $total_setelah_diskon1 = $subtotal - $diskon;

    // Diskon member (5%)
    $diskon_member = 0;
    if ($is_member) {
        $diskon_member = $total_setelah_diskon1 * 0.05;
    }

    // Total setelah semua diskon
    $total_setelah_diskon = $total_setelah_diskon1 - $diskon_member;

    // PPN 11%
    $ppn = $total_setelah_diskon * 0.11;

    // Total akhir
    $total_akhir = $total_setelah_diskon + $ppn;

    // Total hemat
    $total_hemat = $diskon + $diskon_member;

    function rupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
    ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Detail Pembelian</h5>
        </div>
        <div class="card-body">

            <p><strong>Nama Pembeli:</strong> <?= $nama_pembeli; ?></p>
            <p><strong>Judul Buku:</strong> <?= $judul_buku; ?></p>
            <p><strong>Status:</strong>
                <span class="badge bg-<?= $is_member ? 'success' : 'secondary'; ?>">
                    <?= $is_member ? 'Member' : 'Non-Member'; ?>
                </span>
            </p>

            <table class="table">
                <tr><th>Harga Satuan</th><td><?= rupiah($harga_satuan); ?></td></tr>
                <tr><th>Jumlah</th><td><?= $jumlah_beli; ?> buku</td></tr>
                <tr><th>Subtotal</th><td><?= rupiah($subtotal); ?></td></tr>
                <tr>
                    <th>Diskon (<?= $persentase_diskon; ?>%)</th>
                    <td class="text-danger">- <?= rupiah($diskon); ?></td>
                </tr>

                <?php if ($is_member): ?>
                <tr>
                    <th>Diskon Member (5%)</th>
                    <td class="text-danger">- <?= rupiah($diskon_member); ?></td>
                </tr>
                <?php endif; ?>

                <tr><th>Total Setelah Diskon</th><td><?= rupiah($total_setelah_diskon); ?></td></tr>
                <tr><th>PPN (11%)</th><td><?= rupiah($ppn); ?></td></tr>
                <tr class="table-success">
                    <th>Total Akhir</th>
                    <td><strong><?= rupiah($total_akhir); ?></strong></td>
                </tr>
                <tr class="table-warning">
                    <th>Total Hemat</th>
                    <td><strong><?= rupiah($total_hemat); ?></strong></td>
                </tr>
            </table>

        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>