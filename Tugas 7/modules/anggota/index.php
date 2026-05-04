<?php
require_once __DIR__ . '/../../config.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters & Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';
$filter_jk = isset($_GET['jenis_kelamin']) ? trim($_GET['jenis_kelamin']) : '';

// Base Query
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

// Get Total Records for Pagination
$count_query = str_replace("SELECT *", "SELECT COUNT(*)", $query);
$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute($params);
$total_rows = $stmt_count->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Fetch Data with Pagination
$query .= " ORDER BY id_anggota DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$anggota_list = $stmt->fetchAll();

// Statistik Dashboard (Bonus)
$stats = $pdo->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'Aktif' THEN 1 ELSE 0 END) as aktif,
    SUM(CASE WHEN status = 'Nonaktif' THEN 1 ELSE 0 END) as nonaktif
FROM anggota
")->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-semibold text-gray-500 uppercase">Total Anggota</span>
                <span class="text-3xl font-bold text-indigo-600 mt-2"><?= $stats['total'] ?? 0 ?></span>
            </div>
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-semibold text-gray-500 uppercase">Anggota Aktif</span>
                <span class="text-3xl font-bold text-green-600 mt-2"><?= $stats['aktif'] ?? 0 ?></span>
            </div>
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-sm font-semibold text-gray-500 uppercase">Anggota Nonaktif</span>
                <span class="text-3xl font-bold text-red-600 mt-2"><?= $stats['nonaktif'] ?? 0 ?></span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-lg border border-gray-200 shadow-sm mb-6">
            <form method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, email, telepon..." class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm min-w-[240px]">
                
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="Aktif" <?= $filter_status == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Nonaktif" <?= $filter_status == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>

                <select name="jenis_kelamin" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <option value="">Semua Gender</option>
                    <option value="Laki-laki" <?= $filter_jk == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $filter_jk == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">Filter & Cari</button>
                <?php if ($search || $filter_status || $filter_jk): ?>
                    <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300">Reset</a>
                <?php endif; ?>
            </form>

            <div class="flex gap-2">
                <a href="export.php?search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>&jenis_kelamin=<?= urlencode($filter_jk) ?>" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">Export Excel</a>
                <a href="create.php" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">+ Tambah Anggota</a>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                        <tr>
                            <th class="px-6 py-3">Foto</th>
                            <th class="px-6 py-3">Kode / Nama</th>
                            <th class="px-6 py-3">Kontak</th>
                            <th class="px-6 py-3">Jenis Kelamin</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        <?php if (empty($anggota_list)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Data anggota tidak ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($anggota_list as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" class="h-12 w-12 rounded-full object-cover border border-gray-300" alt="Foto">
                                        <?php else: ?>
                                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                <span class="text-xs uppercase font-medium"><?= substr($row['nama'], 0, 2) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-indigo-600"><?= htmlspecialchars($row['kode_anggota']) ?></div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($row['nama']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-600"><?= htmlspecialchars($row['email']) ?></div>
                                        <div class="text-gray-500"><?= htmlspecialchars($row['telepon']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['jenis_kelamin'] === 'Laki-laki'): ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Laki-laki</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold text-pink-800 bg-pink-100 rounded-full">Perempuan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($row['status'] === 'Aktif'): ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="edit.php?id=<?= $row['id_anggota'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <a href="delete.php?id=<?= $row['id_anggota'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="text-red-600 hover:text-red-900">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="flex justify-between items-center mt-6">
                <span class="text-sm text-gray-700">Halaman <span class="font-bold"><?= $page ?></span> dari <span class="font-bold"><?= $total_pages ?></span></span>
                <nav class="flex gap-1">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>&jenis_kelamin=<?= urlencode($filter_jk) ?>" class="px-3 py-2 bg-white border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded">Sebelumnya</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>&jenis_kelamin=<?= urlencode($filter_jk) ?>" class="px-3 py-2 border <?= $i === $page ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' ?> text-sm font-medium rounded"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($filter_status) ?>&jenis_kelamin=<?= urlencode($filter_jk) ?>" class="px-3 py-2 bg-white border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded">Selanjutnya</a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>