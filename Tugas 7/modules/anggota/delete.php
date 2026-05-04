<?php
require_once __DIR__ . '/../../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Cari data untuk hapus foto di server
    $stmt_get = $pdo->prepare("SELECT foto FROM anggota WHERE id_anggota = ?");
    $stmt_get->execute([$id]);
    $anggota = $stmt_get->fetch();

    if ($anggota) {
        if (!empty($anggota['foto']) && file_exists('uploads/' . $anggota['foto'])) {
            unlink('uploads/' . $anggota['foto']);
        }

        $stmt_del = $pdo->prepare("DELETE FROM anggota WHERE id_anggota = ?");
        $stmt_del->execute([$id]);
    }
}

header("Location: index.php");
exit;