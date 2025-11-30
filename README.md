# 🎨 ArtWeb - Digital Art Gallery & Community Platform

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38bdf8)
![Status](https://img.shields.io/badge/Status-Active-success)

**ArtWeb** adalah platform komunitas seni digital yang dibangun dengan **Laravel**. Aplikasi ini memungkinkan seniman untuk memamerkan karya, berinteraksi melalui like/komentar, menyusun koleksi favorit, serta mengikuti kompetisi seni (Art Challenges) yang dikelola oleh kurator.

---

## 📸 Screenshots

| Halaman Utama (Explore) | Detail Karya |
|:---:|:---:|
| ![Explore Page](path/to/screenshot_explore.png) <br> *Masonry Grid Layout* | ![Artwork Detail](path/to/screenshot_detail.png) <br> *Detail & Komentar* |

| Art Challenge | Dashboard Admin |
|:---:|:---:|
| ![Challenge](path/to/screenshot_challenge.png) <br> *Info & Pemenang* | ![Admin](path/to/screenshot_admin.png) <br> *Moderasi Konten* |

> *Catatan: Ganti `path/to/screenshot_...` dengan link gambar asli atau path file di folder public Anda.*

---

## ✨ Fitur Unggulan

### 👤 User & Komunitas
* **Upload Karya:** Mendukung upload gambar resolusi tinggi dengan kompresi otomatis.
* **Interaksi Sosial:** Fitur Like, Komentar (Real-time update UI), dan Follow kreator.
* **Koleksi Favorit (Boards):** Simpan karya ke dalam grup koleksi pribadi (misal: "Inspirasi", "Referensi").
* **Profil Kreator:** Halaman profil yang menampilkan portofolio karya.
* **Dark Mode Support:** Tampilan ramah mata yang menyesuaikan preferensi sistem/user.

### 🏆 Gamifikasi (Challenges)
* **Art Challenges:** Kompetisi berkala dengan tema tertentu.
* **Deadline System:** Status otomatis (Akan Datang, Berlangsung, Selesai).
* **Hall of Fame:** Tampilan podium eksklusif untuk Juara 1, 2, dan 3.

### 🛡️ Peran & Keamanan
* **Multi-Role System:**
    * **Member:** User biasa (Upload, Vote, Submit Challenge).
    * **Curator:** Membuat Challenge, Menilai submisi, Memilih pemenang.
    * **Admin:** Manajemen Kategori, User, dan Moderasi Laporan.
* **Sistem Pelaporan:** User dapat melaporkan karya atau komentar yang tidak pantas untuk ditinjau Admin.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** [Laravel 11](https://laravel.com)
* **Frontend:** Blade Templates, [Alpine.js](https://alpinejs.dev) (untuk interaktivitas)
* **Styling:** [Tailwind CSS](https://tailwindcss.com) (Utility-first framework)
* **Database:** MySQL
* **Build Tool:** Vite

---

## 🚀 Instalasi & Menjalankan Project

Ikuti langkah-langkah ini untuk menjalankan project di lokal komputer Anda:

### Prasyarat
* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL

### Langkah Instalasi

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/ghanyy25/art-web.git](https://github.com/ghanyy25/art-web.git)
    cd art-web
    ```

2.  **Install Dependensi PHP & JS**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi Database & Seeder**
    Jalankan migrasi untuk membuat tabel dan mengisi data dummy (User, Kategori, dll):
    ```bash
    php artisan migrate --seed
    ```

6.  **Buat Storage Link**
    Agar gambar yang diupload bisa diakses publik:
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Server**
    Buka dua terminal terpisah:
    ```bash
    # Terminal 1 (Backend)
    php artisan serve

    # Terminal 2 (Frontend Build/Watch)
    npm run dev
    ```

8.  **Selesai!**
    Buka browser dan akses `http://localhost:8000`.

---

## 🔑 Akun Demo (Seeder)

Jika Anda menjalankan `--seed`, Anda dapat menggunakan akun berikut:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password` |
| **Curator** | `curator@example.com` | `password` |
| **Member** | `user@example.com` | `password` |

---

## 🤝 Kontribusi

Kontribusi selalu diterima! Cara berkontribusi:

1.  Fork repositori ini.
2.  Buat branch fitur baru (`git checkout -b fitur-keren`).
3.  Commit perubahan Anda (`git commit -m 'Menambahkan fitur keren'`).
4.  Push ke branch (`git push origin fitur-keren`).
5.  Buat Pull Request.

---

## 📝 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Dibuat dengan ❤️ oleh <a href="https://github.com/ghanyy25">Ghanyy25</a>
</p>
