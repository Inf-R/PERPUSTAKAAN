<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Informasi Buku</h1>

    <div class="row">
        <?php
        // Data banyak buku
        $books = [
            [
                "judul" => "Laravel: From Beginner to Advanced",
                "pengarang" => "Budi Raharjo",
                "penerbit" => "Informatika",
                "tahun" => 2023,
                "harga" => 125000,
                "stok" => 8,
                "isbn" => "978-602-1234-56-7",
                "kategori" => "Programming",
                "bahasa" => "Indonesia",
                "halaman" => 350,
                "berat" => 500
            ],
            [
                "judul" => "Mastering MySQL Database",
                "pengarang" => "Andi Setiawan",
                "penerbit" => "Elex Media",
                "tahun" => 2022,
                "harga" => 99000,
                "stok" => 5,
                "isbn" => "978-602-9876-12-3",
                "kategori" => "Database",
                "bahasa" => "Indonesia",
                "halaman" => 280,
                "berat" => 400
            ],
            [
                "judul" => "Modern Web Design",
                "pengarang" => "John Doe",
                "penerbit" => "TechPress",
                "tahun" => 2021,
                "harga" => 150000,
                "stok" => 10,
                "isbn" => "978-111-2222-33-4",
                "kategori" => "Web Design",
                "bahasa" => "Inggris",
                "halaman" => 420,
                "berat" => 600
            ],
            [
                "judul" => "PHP untuk Pemula",
                "pengarang" => "Rudi Hartono",
                "penerbit" => "Informatika",
                "tahun" => 2020,
                "harga" => 85000,
                "stok" => 12,
                "isbn" => "978-602-1111-22-3",
                "kategori" => "Programming",
                "bahasa" => "Indonesia",
                "halaman" => 250,
                "berat" => 350
            ]
        ];

        // Fungsi warna badge
        function getBadgeColor($kategori) {
            switch ($kategori) {
                case "Programming": return "primary";
                case "Database": return "success";
                case "Web Design": return "warning";
                default: return "secondary";
            }
        }

        foreach ($books as $buku):
        ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><?php echo $buku['judul']; ?></h5>
                    </div>
                    <div class="card-body">

                        <!-- Badge kategori -->
                        <span class="badge bg-<?php echo getBadgeColor($buku['kategori']); ?>">
                            <?php echo $buku['kategori']; ?>
                        </span>

                        <table class="table table-sm mt-2">
                            <tr><th>Pengarang</th><td>: <?php echo $buku['pengarang']; ?></td></tr>
                            <tr><th>Penerbit</th><td>: <?php echo $buku['penerbit']; ?></td></tr>
                            <tr><th>Tahun</th><td>: <?php echo $buku['tahun']; ?></td></tr>
                            <tr><th>ISBN</th><td>: <?php echo $buku['isbn']; ?></td></tr>
                            <tr><th>Bahasa</th><td>: <?php echo $buku['bahasa']; ?></td></tr>
                            <tr><th>Halaman</th><td>: <?php echo $buku['halaman']; ?> hlm</td></tr>
                            <tr><th>Berat</th><td>: <?php echo $buku['berat']; ?> gram</td></tr>
                            <tr><th>Harga</th><td>: Rp <?php echo number_format($buku['harga'], 0, ',', '.'); ?></td></tr>
                            <tr><th>Stok</th><td>: <?php echo $buku['stok']; ?> buku</td></tr>
                        </table>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>