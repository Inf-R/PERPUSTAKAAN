<?php
$nama_anggota = "Budi Santoso";
$total_pinjaman = 2;
$buku_terlambat = 1;
$hari_keterlambatan = 5;

$max_pinjaman = 3;
$denda_per_hari = 1000;
$max_denda = 50000;


$total_denda = 0;

if ($buku_terlambat > 0) {
    $total_denda = $buku_terlambat * $hari_keterlambatan * $denda_per_hari;

    // Batasi maksimal denda
    if ($total_denda > $max_denda) {
        $total_denda = $max_denda;
    }
}


$status = "";

if ($buku_terlambat > 0) {
    $status = "Tidak bisa meminjam (ada keterlambatan)";
} elseif ($total_pinjaman >= $max_pinjaman) {
    $status = "Tidak bisa meminjam (sudah mencapai batas maksimal)";
} else {
    $status = "Bisa meminjam buku";
}


$level = "";

switch (true) {
    case ($total_pinjaman <= 5):
        $level = "Bronze";
        break;
    case ($total_pinjaman <= 15):
        $level = "Silver";
        break;
    default:
        $level = "Gold";
        break;
}


echo "=== STATUS PEMINJAMAN ===<br>";
echo "Nama: $nama_anggota <br>";
echo "Total Pinjaman: $total_pinjaman <br>";
echo "Buku Terlambat: $buku_terlambat <br>";
echo "Hari Keterlambatan: $hari_keterlambatan <br>";
echo "Level Member: $level <br><br>";

echo "Status: $status <br>";

if ($buku_terlambat > 0) {
    echo "Denda: Rp " . number_format($total_denda, 0, ',', '.') . "<br>";
    echo "⚠️ Harap segera mengembalikan buku yang terlambat!<br>";
}
?>