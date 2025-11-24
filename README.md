# 🧪 Laravel 12 Starter (Vite + Tailwind + Breeze)

Repository ini adalah skeleton aplikasi Laravel 12 dengan stack modern:

- **Laravel 12.x**
- **Authentication scaffolding** menggunakan Laravel Breeze
- **Vite 7** untuk bundling asset (JS/CSS)
- **Tailwind CSS 3**

---

## 📦 System Requirements

Pastikan environment memenuhi minimal requirement berikut:

### Server / Backend

- **PHP** `^8.2`
- **Composer** `^2.x`
- Ekstensi PHP yang umum dipakai Laravel:
  - `bcmath`
  - `ctype`
  - `fileinfo`
  - `json`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `tokenizer`
  - `xml`

### Frontend / Build Tools

- **Node.js** `>= 18` (disarankan LTS terbaru, kompatibel dengan Vite 7)
- **npm** `>= 9`

### Database

- Default contoh konfigurasi menggunakan **SQLite**.
- Bisa diganti ke **MySQL / PostgreSQL** atau database lain yang didukung Laravel dengan mengubah `.env`.

### Tools Opsional

- **Git**

---

## 🛠️ Tech Stack

### PHP / Laravel

- `laravel/framework` `^12.0`
- `laravel/tinker`
- `laravel/breeze`

### Frontend

- `vite` `^7.0.7`
- `tailwindcss` `^3.1.0`
- `@tailwindcss/forms`
- `@tailwindcss/vite`
- `axios` `^1.11.0`

---

## 🚀 Cara Setup & Instalasi

> **Catatan:** Seluruh perintah di bawah dijalankan di root folder project.

### 1. Clone Repository

```bash
git clone https://github.com/pilanggandu/rsam-mini-project.git
cd nama-repo
```

> Ganti `username/nama-repo` dengan URL GitHub kamu sendiri.

---

### 2. Setup Konfigurasi

#### a. Install Dependency PHP

```bash
composer install
```

#### b. Salin File Environment

```bash
cp .env.example .env
```

Atau di Windows:

```bash
copy .env.example .env
```

#### c. Generate Application Key

```bash
php artisan key:generate
```

#### d. Konfigurasi Database

Edit file `.env` sesuai kebutuhan, contoh (SQLite):

```env
DB_CONNECTION=sqlite
DB_DATABASE=/full/path/ke/project/database/database.sqlite
```

Atau untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=password
```

#### e. Jalankan Migrasi Database

```bash
php artisan migrate
```

#### f. Lakukan seeding database

```bash
php artisan db:seed
```

#### g. Install Dependency Frontend

```bash
npm install
```

---

## 🧑‍💻 Menjalankan Aplikasi (Development)

#### a. Jalankan Laravel

```bash
php artisan serve
```

Secara default akan berjalan di `http://127.0.0.1:8000`.

#### b. Jalankan Vite Dev Server

Di terminal lain:

```bash
npm run dev
```

Biasanya berjalan di `http://127.0.0.1:5173`.

Pastikan di Blade sudah menggunakan directive:

```php
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## 🏗️ Build untuk Production

Untuk build asset production (minify, cache-busting, dsb):

```bash
npm run build
```

Ini akan menghasilkan file build di `public/build` (sesuai konfigurasi Vite + Laravel Vite Plugin).

Deployment ke server production biasanya meliputi:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan migrate --force
npm install
npm run build
```

---

## 💬 Kontribusi

1. Fork repository ini.
2. Buat branch baru: `git checkout -b feature/nama-fitur`.
3. Commit perubahan: `git commit -m "Tambah fitur X"`.
4. Push ke branch: `git push origin feature/nama-fitur`.
5. Buat Pull Request di GitHub.

---

## 📄 Lisensi

Project ini menggunakan lisensi **MIT**, mengikuti default dari Laravel.

Silakan modifikasi README ini sesuai nama proyek dan kebutuhan spesifik aplikasi.
