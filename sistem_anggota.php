<?php
require_once 'functions_anggota.php';

// DATA (should come from DB in real world)
$anggota_list = [
    ["id"=>"AGT-001","nama"=>"Budi Santoso","email"=>"budi@email.com","telepon"=>"081234567890","alamat"=>"Jakarta","tanggal_daftar"=>"2024-01-15","status"=>"Aktif","total_pinjaman"=>5],
    ["id"=>"AGT-002","nama"=>"Siti Aminah","email"=>"siti@email.com","telepon"=>"082345678901","alamat"=>"Bandung","tanggal_daftar"=>"2024-02-10","status"=>"Aktif","total_pinjaman"=>8],
    ["id"=>"AGT-003","nama"=>"Andi Wijaya","email"=>"andi@email.com","telepon"=>"083456789012","alamat"=>"Surabaya","tanggal_daftar"=>"2024-03-05","status"=>"Non-Aktif","total_pinjaman"=>2],
    ["id"=>"AGT-004","nama"=>"Dewi Lestari","email"=>"dewi@email.com","telepon"=>"084567890123","alamat"=>"Yogyakarta","tanggal_daftar"=>"2024-01-25","status"=>"Aktif","total_pinjaman"=>10],
    ["id"=>"AGT-005","nama"=>"Rudi Hartono","email"=>"rudi@email.com","telepon"=>"085678901234","alamat"=>"Semarang","tanggal_daftar"=>"2024-02-20","status"=>"Non-Aktif","total_pinjaman"=>1],
];

// ================= CONTROL =================

// Search
$keyword = $_GET['search'] ?? '';
if ($keyword) {
    $anggota_list = searchByNama($anggota_list, $keyword);
}

// Sort
$sort = $_GET['sort'] ?? '';
if ($sort == 'nama') {
    $anggota_list = sortByNama($anggota_list);
}

// Statistik
$total = getTotalAnggota($anggota_list);
$stats = getStatistik($anggota_list);
$teraktif = getAnggotaTeraktif($anggota_list);
$aktif_list = filterByStatus($anggota_list, "Aktif");
$nonaktif_list = filterByStatus($anggota_list, "Non-Aktif");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h1 class="mb-4">📚 Sistem Anggota Perpustakaan</h1>

<!-- SEARCH & SORT -->
<form class="row mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search nama..." value="<?= $keyword ?>">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Search</button>
    </div>
    <div class="col-md-2">
        <a href="?sort=nama" class="btn btn-warning">Sort A-Z</a>
    </div>
</form>

<!-- DASHBOARD -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white p-3">
            <h5>Total</h5>
            <h3><?= $total ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            <h5>Aktif</h5>
            <h3><?= number_format($stats['aktif_percent'],1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white p-3">
            <h5>Non-Aktif</h5>
            <h3><?= number_format($stats['nonaktif_percent'],1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark p-3">
            <h5>Rata Pinjaman</h5>
            <h3><?= number_format($stats['rata_pinjaman'],1) ?></h3>
        </div>
    </div>
</div>

<!-- TABEL -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        Daftar Anggota
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Nama</th>
                <th>Status</th>
                <th>Pinjaman</th>
            </tr>
            <?php foreach ($anggota_list as $a): ?>
            <tr>
                <td><?= $a['nama'] ?></td>
                <td><?= $a['status'] ?></td>
                <td><?= $a['total_pinjaman'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<!-- TERAKTIF -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        Anggota Teraktif
    </div>
    <div class="card-body">
        <h4><?= $teraktif['nama'] ?></h4>
        <p>Total Pinjaman: <?= $teraktif['total_pinjaman'] ?></p>
    </div>
</div>

<!-- AKTIF VS NON -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">Anggota Aktif</div>
            <div class="card-body">
                <ul>
                    <?php foreach ($aktif_list as $a): ?>
                    <li><?= $a['nama'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">Anggota Non-Aktif</div>
            <div class="card-body">
                <ul>
                    <?php foreach ($nonaktif_list as $a): ?>
                    <li><?= $a['nama'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>