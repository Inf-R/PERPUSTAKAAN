<?php
require_once __DIR__ . '/../../config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';
$filter_jk = isset($_GET['jenis_kelamin']) ? trim($_GET['jenis_kelamin']) : '';

$query = "SELECT * FROM anggota WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (nama LIKE ? OR email LIKE ? OR telepon LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter_status !== '') {
    $query .= " AND status = ?";
    $params[] = $filter_status;
}

if ($filter_jk !== '') {
    $query .= " AND jenis_kelamin = ?";
    $params[] = $filter_jk;
}

$query .= " ORDER BY id_anggota DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$data = $stmt->fetchAll();

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Anggota_Perpustakaan_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1">
    <thead>
        <tr style="background-color: #4F46E5; color: white;">
            <th>No</th>
            <th>Kode Anggota</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Pekerjaan</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($data as $row): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['kode_anggota']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>'<?= htmlspecialchars($row['telepon']) ?></td> <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($row['pekerjaan']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_daftar']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>