# PERPUSTAKAAN

Project studi kasus nyata sistem perpustakaan. Mata kuliah Pemrograman Web 2, menggunakan Bahasa pemrograman PHP, library Laravel, dan Database MySQL.

## Tugas 1 (Pertemuan 6): Eksplorasi Database dengan Query (40%)

Dokumentasi Hasil Query (Screenshot dari folder `SS tugas 6`)

### 1. Statistik Buku (5 Query)

**1.1 Total buku seluruhnya**
![Total Buku](./SS%20tugas%206/1.%20Menghitung%20total%20buku%20seluruhnya.png)

**1.2 Total nilai inventaris (sum harga × stok)**
![Total Inventaris](./SS%20tugas%206/--%202.%20Menghitung%20total%20nilai%20inventaris%20(total%20harga%20dari%20seluruh%20stok).png)

**1.3 Rata-rata harga buku**
![Rata-rata Harga](./SS%20tugas%206/3.%20Menghitung%20rata-rata%20harga%20buku.png)

**1.4 Buku termahal (tampilkan judul dan harga)**
![Buku Termahal](./SS%20tugas%206/4.%20Menampilkan%20buku%20termahal%20(judul%20dan%20harga).png)

**1.5 Buku dengan stok terbanyak**
![Stok Terbanyak](./SS%20tugas%206/5.%20Menampilkan%20buku%20dengan%20stok%20terbanyak.png)

---

### 2. Filter dan Pencarian (5 Query)

**2.1 Semua buku kategori Programming yang harga < 100.000**
![Filter Programming](./SS%20tugas%206/1.%20Semua%20buku%20kategori%20Programming%20dengan%20harga%20di%20bawah%20100.000.png)

**2.2 Buku yang judulnya mengandung kata "PHP" atau "MySQL"**
![Search Judul](./SS%20tugas%206/2.%20Buku%20yang%20judulnya%20mengandung%20kata%20PHP%20atau%20MySQL.png)

**2.3 Buku yang terbit tahun 2024**
![Filter Tahun](./SS%20tugas%206/3.%20Buku%20yang%20terbit%20pada%20tahun%202024.png)

**2.4 Buku yang stoknya antara 5-10**
![Range Stok](./SS%20tugas%206/4.%20Buku%20dengan%20jumlah%20stok%20antara%205%20sampai%2010.png)

**2.5 Buku yang pengarangnya "Budi Raharjo"**
![Filter Pengarang](./SS%20tugas%206/5.%20Buku%20yang%20ditulis%20oleh%20pengarang%20Budi%20Raharjo.png)

---

### 3. Grouping dan Agregasi (3 Query)

**3.1 Jumlah buku per kategori (dengan total stok per kategori)**
![Jumlah per Kategori](./SS%20tugas%206/1.%20Jumlah%20buku%20dan%20total%20stok%20per%20kategori.png)

**3.2 Rata-rata harga per kategori**
![Rata-rata per Kategori](./SS%20tugas%206/2.%20Rata-rata%20harga%20buku%20untuk%20setiap%20kategori.png)

**3.3 Kategori dengan total nilai inventaris terbesar**
![Inventaris Terbesar](./SS%20tugas%206/3.%20Kategori%20dengan%20total%20nilai%20inventaris%20(harga%20stok)%20terbesar.png)

---

### 4. Update Data (2 Query)

**4.1 Naikkan harga semua buku kategori Programming sebesar 5%**
![Update Harga](./SS%20tugas%206/1.%20Menaikkan%20harga%20semua%20buku%20kategori%20Programming%20sebesar%205%25.png)

**4.2 Tambah stok 10 untuk semua buku yang stoknya < 5**
![Update Stok](./SS%20tugas%206/2.%20Menambah%20stok%20sebanyak%2010%20unit%20untuk%20buku%20yang%20stoknya%20di%20bawah%205.png)

---

### 5. Laporan Khusus (2 Query)

**5.1 Daftar buku yang perlu restocking (stok < 5)**
![Restocking](./SS%20tugas%206/1.%20Daftar%20buku%20yang%20perlu%20segera%20restock%20(stok%20kurang%20dari%205).png)

**5.2 Top 5 buku termahal**
![Top 5 Termahal](./SS%20tugas%206/2.%20Top%205%20buku%20dengan%20harga%20termahal.png)



## Tugas 2: Desain Database Lengkap (60%)

Pengembangan database `perpustakaan_lengkap` dengan struktur relasional (Normalisasi), penggunaan Foreign Keys, dan fitur-fitur lanjutan.

### 1. Entity Relationship Diagram (ERD)
Diagram ini menunjukkan relasi *One-to-Many* antara tabel Buku dengan tabel master Kategori, Penerbit, dan Rak.
![ERD Perpustakaan](./SS%20tugas%206/Screenshot%202026-04-27%20235913.png)

### 2. Implementasi Query JOIN
Query untuk menampilkan detail lengkap buku dengan menggabungkan tabel `kategori_buku` dan `penerbit`.
![Hasil JOIN](./SS%20tugas%206/Screenshot%202026-04-27%20235659.png)

### 3. Agregasi Relasional
Menghitung jumlah judul buku berdasarkan kategori yang tersedia di tabel master.
![Buku Per Kategori](./SS%20tugas%206/Screenshot%202026-04-27%20235713.png)

---

## 🚀 Cara Penggunaan
1. Import file SQL ke phpMyAdmin Anda.
2. Jalankan kueri laporan yang tersedia untuk melihat hasil analisis data.
