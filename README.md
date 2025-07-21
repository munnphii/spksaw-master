# Sistem Pendukung Keputusan Seleksi Calon Atlet Berbakat Terbaik

## Deskripsi Aplikasi

Sistem Pendukung Keputusan (SPK) untuk seleksi calon atlet berbakat terbaik menggunakan metode **SAW (Simple Additive Weighting)** dengan PHP dan MySQL.

### Tentang Metode SAW

Metode Simple Additive Weighting (SAW) sering juga dikenal istilah metode penjumlahan terbobot. Konsep dasar metode SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut (Fishburn 1967). SAW dapat dianggap sebagai cara yang paling mudah dan intuitif untuk menangani masalah Multiple Criteria Decision-Making MCDM, karena fungsi linear additive dapat mewakili preferensi pembuat keputusan (Decision-Making, DM).

### Langkah Penyelesaian SAW:

1. **Menentukan kriteria-kriteria** yang akan dijadikan acuan dalam pengambilan keputusan, yaitu Ci
2. **Menentukan rating kecocokan** setiap alternatif pada setiap kriteria (X)
3. **Membuat matriks keputusan** berdasarkan kriteria(Ci), kemudian melakukan normalisasi matriks berdasarkan persamaan yang disesuaikan dengan jenis atribut (atribut keuntungan ataupun atribut biaya) sehingga diperoleh matriks ternormalisasi R
4. **Hasil akhir** diperoleh dari proses perankingan yaitu penjumlahan dari perkalian matriks ternormalisasi R dengan vektor bobot sehingga diperoleh nilai terbesar yang dipilih sebagai alternatif terbaik (Ai) sebagai solusi

---

## 📋 Fitur Aplikasi

- ✅ **Dashboard** - Halaman utama dengan penjelasan metode SAW
- ✅ **Manajemen Alternatif** - Tambah, edit, hapus kandidat atlet
- ✅ **Upload Foto & PDF** - Upload foto profil dan biodata PDF kandidat
- ✅ **Bobot & Kriteria** - Kelola kriteria penilaian dan bobot
- ✅ **Matriks Keputusan** - Input nilai penilaian alternatif
- ✅ **Perhitungan SAW** - Normalisasi dan perhitungan preferensi
- ✅ **Ranking Hasil** - Tampilan peringkat akhir kandidat
- ✅ **Export PDF** - Cetak laporan lengkap
- ✅ **Import CSV** - Import data alternatif dari file CSV
- ✅ **Sistem Login** - Autentikasi admin

---

## 🚀 Cara Instalasi

### Prasyarat

- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx) atau XAMPP/Laragon
- Composer (untuk dependencies)

### Langkah Instalasi

#### 1. **Clone/Download Repository**

```bash
git clone https://github.com/munnphi/Sistem-Pendukung-Keputusan-Seleksi-Calon-Atlet-Berbakat-Terbaik-Menggunakan-Metode-SAW.git
cd Sistem-Pendukung-Keputusan-Seleksi-Calon-Atlet-Berbakat-Terbaik-Menggunakan-Metode-SAW
```

#### 2. **Setup Database**

- Buat database baru di MySQL/phpMyAdmin
- Import file `db/db_saw.sql` ke database yang sudah dibuat
- Atau jalankan perintah:
  ```sql
  mysql -u username -p database_name < db/db_saw.sql
  ```

#### 3. **Konfigurasi Database**

- Edit file `include/conn.php`
- Sesuaikan dengan konfigurasi database Anda:
  ```php
  $host = 'localhost';
  $user = 'username_database';
  $pass = 'password_database';
  $db   = 'nama_database';
  ```

#### 4. **Setup Folder Uploads**

- Pastikan folder `uploads/` sudah ada
- Berikan permission write pada folder tersebut
- Folder ini akan menyimpan foto profil dan file PDF biodata

#### 5. **Install Dependencies (Opsional)**

```bash
composer install
```

#### 6. **Akses Aplikasi**

- Buka browser
- Akses: `http://localhost/spksaw/`
- Login dengan:
  - Username: `admin`
  - Password: `admin`

---

## 📊 Struktur Database

### Tabel dan Field:

#### 1. **saw_alternatives** (Tabel Alternatif/Kandidat)

- `id_alternative` (smallint, PK, AUTO_INCREMENT)
- `name` (varchar(30)) - Nama alternatif/kandidat
- `profile_photo` (varchar(255), NULL) - Nama file foto profil
- `biodata_pdf` (varchar(255), NULL) - Nama file biodata PDF

#### 2. **saw_criterias** (Tabel Kriteria)

- `id_criteria` (tinyint, PK)
- `criteria` (varchar(100)) - Nama kriteria
- `weight` (float) - Bobot kriteria
- `attribute` (set('benefit','cost')) - Jenis atribut

#### 3. **saw_evaluations** (Tabel Evaluasi)

- `id_alternative` (smallint, PK)
- `id_criteria` (tinyint, PK)
- `value` (float) - Nilai penilaian

#### 4. **saw_users** (Tabel User)

- `id_user` (int, PK, AUTO_INCREMENT)
- `username` (varchar(50))
- `password` (varchar(150))

### Data Sample:

Database sudah berisi data sample:

- 8 alternatif (kandidat atlet)
- 5 kriteria penilaian
- 30 data evaluasi
- 1 user admin (username: admin, password: admin)

---

## 🎯 Kriteria Penilaian Default

1. **Keterampilan Teknis Olahraga** (Bobot: 3.0, Benefit)
2. **Kondisi Fisik** (Bobot: 2.5, Benefit)
3. **Prestasi Akademik** (Bobot: 1.5, Benefit)
4. **Disiplin Latihan** (Bobot: 2.0, Benefit)
5. **Usia** (Bobot: 1.0, Cost)

---

## 📁 Struktur Folder

```
spksaw/
├── assets/                 # CSS, JS, Images
├── db/                    # File database
│   ├── db_saw.sql        # Database structure & data
│   └── README_UPDATE.md  # Database documentation
├── export/               # Export PDF
├── import/               # Import CSV
├── include/              # Database connection
├── layout/               # Layout components
├── temp/                 # Temporary files
├── uploads/              # Uploaded files
├── vendor/               # Composer dependencies
├── alternatif.php        # Halaman alternatif
├── bobot.php            # Halaman bobot & kriteria
├── index.php            # Dashboard
├── login.php            # Halaman login
├── matrik.php           # Halaman matriks
├── preferensi.php       # Halaman preferensi
└── README.md            # File ini
```

---

## 🔧 Penggunaan Aplikasi

### 1. **Login**

- Akses aplikasi dan login sebagai admin
- Username: `admin`, Password: `admin`

### 2. **Kelola Alternatif**

- Tambah kandidat atlet baru
- Upload foto profil dan biodata PDF
- Edit atau hapus data kandidat

### 3. **Kelola Kriteria**

- Sesuaikan kriteria penilaian
- Atur bobot setiap kriteria
- Tentukan jenis atribut (benefit/cost)

### 4. **Input Nilai**

- Masukkan nilai penilaian setiap kandidat
- Nilai berdasarkan kriteria yang telah ditentukan

### 5. **Lihat Hasil**

- Sistem akan menghitung normalisasi
- Menampilkan ranking akhir kandidat
- Export hasil ke PDF

---

## 📝 Format Import CSV

Untuk import data alternatif dari file CSV:

```
nama_alternatif,profile_photo,biodata_pdf
Andi Pratama,foto_andi.jpg,biodata_andi.pdf
Budi Santoso,foto_budi.jpg,biodata_budi.pdf
```

**Catatan:** File foto dan PDF harus diupload manual ke folder `uploads/` setelah import.

---

## 🛠️ Troubleshooting

### Error Database Connection

- Periksa konfigurasi di `include/conn.php`
- Pastikan database sudah dibuat dan diimport

### Error Upload File

- Periksa permission folder `uploads/`
- Pastikan folder memiliki permission write

### Error Layout

- Pastikan semua file layout ada di folder `layout/`
- Periksa path include di file PHP

---

## 📞 Support

Jika mengalami masalah atau ada pertanyaan, silakan:

- Buat issue di repository GitHub
- Hubungi developer melalui email

---

## 📄 License

Aplikasi ini dibuat untuk keperluan akademis dan pembelajaran metode SAW.

---

**Dibuat dengan ❤️ menggunakan PHP, MySQL, dan Bootstrap**
