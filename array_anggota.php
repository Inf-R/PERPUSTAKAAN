<?php
// DATA ANGGOTA (Multidimensional Array)
$anggota_list = [
    [
        "id" => "AGT-001",
        "nama" => "Budi Santoso",
        "email" => "budi@email.com",
        "telepon" => "081234567890",
        "alamat" => "Jakarta",
        "tanggal_daftar" => "2024-01-15",
        "status" => "Aktif",
        "total_pinjaman" => 5
    ],
    [
        "id" => "AGT-002",
        "nama" => "Siti Aminah",
        "email" => "siti@email.com",
        "telepon" => "082345678901",
        "alamat" => "Bandung",
        "tanggal_daftar" => "2024-02-10",
        "status" => "Aktif",
        "total_pinjaman" => 8
    ],
    [
        "id" => "AGT-003",
        "nama" => "Andi Wijaya",
        "email" => "andi@email.com",
        "telepon" => "083456789012",
        "alamat" => "Surabaya",
        "tanggal_daftar" => "2024-03-05",
        "status" => "Non-Aktif",
        "total_pinjaman" => 2
    ],
    [
        "id" => "AGT-004",
        "nama" => "Dewi Lestari",
        "email" => "dewi@email.com",
        "telepon" => "084567890123",
        "alamat" => "Yogyakarta",
        "tanggal_daftar" => "2024-01-25",
        "status" => "Aktif",
        "total_pinjaman" => 10
    ],
    [
        "id" => "AGT-005",
        "nama" => "Rudi Hartono",
        "email" => "rudi@email.com",
        "telepon" => "085678901234",
        "alamat" => "Semarang",
        "tanggal_daftar" => "2024-02-20",
        "status" => "Non-Aktif",
        "total_pinjaman" => 1
    ]
];

// ================= LOGIC =================

// Total anggota
$total_anggota = count($anggota_list);

// Hitung status
$aktif = 0;
$non_aktif = 0;
$total_pinjaman = 0;

// Cari anggota teraktif
$anggota_teraktif = $anggota_list[0];

foreach ($anggota_list as $anggota) {
    if ($anggota['status'] == "Aktif") {
        $aktif++;
    } else {
        $non_aktif++;
    }

    $total_pinjaman += $anggota['total_pinjaman'];

    if ($anggota['total_pinjaman'] > $anggota_teraktif['total_pinjaman']) {
        $anggota_teraktif = $anggota;
    }
}

// Persentase
$persen_aktif = ($aktif / $total_anggota) * 100;
$persen_nonaktif = ($non_aktif / $total_anggota) * 100;

// Rata-rata pinjaman
$rata_pinjaman = $total_pinjaman / $total_anggota;

// Filter status (optional)
$status_filter = $_GET['status'] ?? null;
$filtered_list = $anggota_list;

if ($status_filter) {
    $filtered_list = array_filter($anggota_list, function($a) use ($status_filter) {
        return $a['status'] == $status_filter;
    });
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Statistik Anggota</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card p-3 bg-primary text-white">
            <h5>Total Anggota</h5>
            <h3><?= $total_anggota ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 bg-success text-white">
            <h5>Aktif</h5>
            <h3><?= number_format($persen_aktif, 1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 bg-danger text-white">
            <h5>Non-Aktif</h5>
            <h3><?= number_format($persen_nonaktif, 1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 bg-warning text-dark">
            <h5>Rata Pinjaman</h5>
            <h3><?= number_format($rata_pinjaman, 1) ?></h3>
        </div>
    </div>
</div>

<div class="card p-3 mb-4">
    <h5>Anggota Teraktif</h5>
    <p><strong><?= $anggota_teraktif['nama'] ?></strong> (<?= $anggota_teraktif['total_pinjaman'] ?> pinjaman)</p>
</div>

<h2>Filter</h2>
<a href="?status=Aktif" class="btn btn-success btn-sm">Aktif</a>
<a href="?status=Non-Aktif" class="btn btn-danger btn-sm">Non-Aktif</a>
<a href="?" class="btn btn-secondary btn-sm">Reset</a>

<h2 class="mt-3">Daftar Anggota</h2>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Total Pinjaman</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filtered_list as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= $a['nama'] ?></td>
            <td><?= $a['email'] ?></td>
            <td><?= $a['telepon'] ?></td>
            <td><?= $a['alamat'] ?></td>
            <td><?= $a['tanggal_daftar'] ?></td>
            <td><?= $a['status'] ?></td>
            <td><?= $a['total_pinjaman'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>