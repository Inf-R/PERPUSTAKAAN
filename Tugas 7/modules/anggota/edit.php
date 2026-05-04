<?php
require_once __DIR__ . '/../../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt_get = $pdo->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
$stmt_get->execute([$id]);
$anggota = $stmt_get->fetch();

if (!$anggota) {
    header("Location: index.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_anggota  = trim($_POST['kode_anggota']);
    $nama          = trim($_POST['nama']);
    $email         = trim($_POST['email']);
    $telepon       = trim($_POST['telepon']);
    $alamat        = trim($_POST['alamat']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    $pekerjaan     = trim($_POST['pekerjaan']);
    $status        = trim($_POST['status']);

    // Validasi
    if (empty($kode_anggota))  $errors['kode_anggota'] = 'Kode anggota wajib diisi.';
    if (empty($nama))          $errors['nama'] = 'Nama wajib diisi.';
    if (empty($email))         $errors['email'] = 'Email wajib diisi.';
    if (empty($telepon))       $errors['telepon'] = 'Telepon wajib diisi.';
    if (empty($alamat))        $errors['alamat'] = 'Alamat wajib diisi.';
    if (empty($tanggal_lahir)) $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';
    if (empty($jenis_kelamin)) $errors['jenis_kelamin'] = 'Jenis kelamin wajib diisi.';

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    }
    if (!empty($telepon) && !preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors['telepon'] = 'Telepon harus diawali 08 dan berjumlah 10-13 digit.';
    }
    if (!empty($tanggal_lahir)) {
        $dob = new DateTime($tanggal_lahir);
        $today = new DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 10) {
            $errors['tanggal_lahir'] = 'Umur minimal adalah 10 tahun.';
        }
    }

    // Cek Unique
    if (empty($errors)) {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE (kode_anggota = ? OR email = ?) AND id_anggota != ?");
        $stmt_check->execute([$kode_anggota, $email, $id]);
        if ($stmt_check->fetchColumn() > 0) {
            $errors['global'] = 'Kode Anggota atau Email sudah digunakan anggota lain.';
        }
    }

    // Handle Upload Foto Baru
    $foto_name = $anggota['foto'];
    if (empty($errors) && !empty($_FILES['foto']['name'])) {
        $file = $_FILES['foto'];
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $errors['foto'] = 'Format foto harus JPG, JPEG, atau PNG.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors['foto'] = 'Ukuran foto maksimal adalah 2MB.';
        } else {
            // Hapus foto lama jika ada
            if (!empty($anggota['foto']) && file_exists('uploads/' . $anggota['foto'])) {
                unlink('uploads/' . $anggota['foto']);
            }
            $foto_name = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], 'uploads/' . $foto_name);
        }
    }

    // Update Data
    if (empty($errors)) {
        try {
            $sql = "UPDATE anggota SET kode_anggota = ?, nama = ?, email = ?, telepon = ?, alamat = ?, tanggal_lahir = ?, jenis_kelamin = ?, pekerjaan = ?, status = ?, foto = ? WHERE id_anggota = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode_anggota, $nama, $email, $telepon, $alamat, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $status, $foto_name, $id]);
            
            header("Location: index.php");
            exit;
        } catch (\PDOException $e) {
            $errors['global'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Anggota</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Data Anggota</h2>
                <a href="index.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Kembali</a>
            </div>

            <?php if (isset($errors['global'])): ?>
                <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded border border-red-200"><?= $errors['global'] ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Anggota *</label>
                        <input type="text" name="kode_anggota" value="<?= htmlspecialchars($_POST['kode_anggota'] ?? $anggota['kode_anggota']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <?php if (isset($errors['kode_anggota'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['kode_anggota'] ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? $anggota['nama']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <?php if (isset($errors['nama'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['nama'] ?></p><?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $anggota['email']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <?php if (isset($errors['email'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['email'] ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Telepon *</label>
                        <input type="text" name="telepon" value="<?= htmlspecialchars($_POST['telepon'] ?? $anggota['telepon']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <?php if (isset($errors['telepon'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['telepon'] ?></p><?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap *</label>
                    <textarea name="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm"><?= htmlspecialchars($_POST['alamat'] ?? $anggota['alamat']) ?></textarea>
                    <?php if (isset($errors['alamat'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['alamat'] ?></p><?php endif; ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($_POST['tanggal_lahir'] ?? $anggota['tanggal_lahir']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <?php if (isset($errors['tanggal_lahir'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['tanggal_lahir'] ?></p><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="Laki-laki" <?= (isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : $anggota['jenis_kelamin']) === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= (isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : $anggota['jenis_kelamin']) === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="<?= htmlspecialchars($_POST['pekerjaan'] ?? $anggota['pekerjaan']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Anggota</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="Aktif" <?= (isset($_POST['status']) ? $_POST['status'] : $anggota['status']) === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Nonaktif" <?= (isset($_POST['status']) ? $_POST['status'] : $anggota['status']) === 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 flex gap-4 items-center">
                    <?php if (!empty($anggota['foto']) && file_exists('uploads/' . $anggota['foto'])): ?>
                        <img src="uploads/<?= $anggota['foto'] ?>" alt="Foto Lama" class="w-16 h-16 rounded-full object-cover border border-gray-300">
                    <?php endif; ?>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ubah Foto (Opsional)</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-sm border border-gray-300 rounded cursor-pointer bg-gray-50 focus:outline-none">
                        <?php if (isset($errors['foto'])): ?><p class="text-red-500 text-xs mt-1"><?= $errors['foto'] ?></p><?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200 flex justify-end gap-2">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-medium text-sm rounded shadow-sm hover:bg-indigo-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>