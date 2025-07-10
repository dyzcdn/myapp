# SSL 3-Tier Certificate Generator + Auth System (CodeIgniter 3)

![License](https://img.shields.io/badge/license-MIT-blue.svg) ![CI Version](https://img.shields.io/badge/CodeIgniter-3.1.13-red.svg) ![PHP](https://img.shields.io/badge/PHP->=7.4-blue)

> 🔒 Sistem otentikasi + 🔐 generator sertifikat SSL 3-Tier dengan UI modern, login Google, proteksi CSRF, Turnstile Captcha, pengaturan akun, dan sepenuhnya tersimpan di database.

---

## 🚀 Fitur Utama

### ✅ Sistem Autentikasi

* Login & Register menggunakan username/email + password
* Login dengan Google (OAuth2)
* Verifikasi email saat registrasi
* Lupa password via email (SMTP)
* Proteksi Captcha (Cloudflare Turnstile)
* Halaman profil: edit akun, ganti profil, preferensi notifikasi, hapus akun
* Logout otomatis saat token Google kedaluwarsa

### 🔐 SSL Certificate Generator

* Generate Root CA, Intermediate CA, dan Leaf Certificate
* Subject Alternative Name (SAN) support
* UUID untuk akses aman
* Simpan semua ke database (MySQL)
* Export ke berbagai format: `.pem`, `.key`, `.csr`, `.zip`, `.crt`, `.p12`, `.der`
* One-click action: Generate CA, Leaf, Re-Generate, Download Bundles

---

## 📦 Requirement

* PHP >= 7.4 (disarankan PHP 8.1 atau lebih tinggi)
* MySQL 5.7+ / MariaDB
* Ekstensi PHP yang diperlukan:

  * `openssl`
  * `pdo`
  * `mbstring`
  * `curl`
  * `json`
  * `zip`
* Composer (untuk instalasi dependency tambahan)

---

## 🗂 Struktur Folder

```text
myapp/
├── application/
│   ├── controllers/
│   │   ├── Auth.php
│   │   ├── Certificates.php
│   │   ├── Profile.php
│   │   └── Welcome.php
│   ├── models/
│   │   ├── User_model.php
│   │   └── Certificate_model.php
│   ├── views/
│   │   ├── _partials/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── auth/
│   │   │   ├── _partials/
│   │   │   │   ├── header.php
│   │   │   │   └── footer.php
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   ├── forgot_password.php
│   │   │   └── documentation.php
│   │   ├── profile.php
│   │   ├── ssl_view.php
│   │   ├── welcome.php
│   │   └── email_templates/
│   │       ├── verification_email.php
│   │       └── reset_password_email.php
│   ├── config/
│   │   ├── config.php
│   │   ├── database.php
│   │   └── self_config.php
│   ├── helpers/
│   │   ├── auth_helper.php
│   │   ├── flash_helper.php
│   │   ├── captcha_helper.php
│   │   └── utility_helper.php
├── uploads/
│   ├── certs/
│   └── profile_pics/
├── vendor/
├── composer.json
├── index.php
├── .htaccess
└── README.md
```

---

## ⚙️ Setup Cepat

### 0. Download Langsung

➡️ [Download](https://github.com/dyzcdn/myapp/releases/download/v1.0.0/myapp-v1.0.0.zip)

Dengan download langsung sudah termasuk Dependency. Atau anda dapat clone repository ini.

### 1. Clone Repository

```bash
git clone https://github.com/dyzcdn/myapp.git
cd myapp
```

### 2. Install Dependency

```bash
composer install
```

### 3. Konfigurasi

Edit file berikut:

* `application/config/config.php`: ubah `$config['base_url']`
* `application/config/database.php`: sesuaikan DB Anda
* `application/config/self_config.php`:

  * Konfigurasi DN (distinguished name) untuk CA
  * Konfigurasi SMTP pengirim email
  * OAuth Google login
  * Cloudflare Turnstile key

### 4. Setup `.htaccess`

```apacheconf
# /.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### 5. Jalankan

Akses aplikasi:

```
http://localhost/myapp/public/
```

Jika belum ada tabel:

* Akses `/auth`
* Klik **"Buat Tabel Database"**
* Lanjutkan ke **"Setup Admin/User Pertama"**
* Login dan mulai gunakan fitur SSL!

---

## 🧪 SMTP Test Email

Kunjungi `/welcome/smtp_test` untuk menguji koneksi SMTP.

---

## 📥 Download Rilis

Download versi stabil dari halaman [Releases](https://github.com/dyzcdn/myapp/releases).

---

## 📸 Contoh UI

![UI Screenshot](https://cdn.dyzulk.com/img-cdn/DyzulkDev-App-Login.png)
![UI Screenshot](https://cdn.dyzulk.com/img-cdn/DyzulkDev-App-Register.png)
![UI Screenshot](https://cdn.dyzulk.com/img-cdn/DyzulkDev-SSL-3-Tier-Generator.png)
![UI Screenshot](https://cdn.dyzulk.com/img-cdn/DyzulkDev-Tes-SMTP.png)

---

## 🔐 Keamanan

* Semua form autentikasi dilindungi Turnstile Captcha
* Autentikasi token Google kadaluarsa = logout otomatis
* Proteksi CSRF dan input filter built-in dari CI3
* Token email & reset tersimpan dengan waktu kedaluwarsa

---

## ⚖️ Lisensi

Proyek ini menggunakan lisensi **MIT** — silakan gunakan, fork, dan modifikasi untuk keperluan pribadi atau produksi.

---

## 🙌 Kontribusi

Pull request sangat disambut! Jangan ragu membuka [issue](https://github.com/dyzcdn/myapp/issues) untuk fitur, bug, atau pertanyaan.

---

Dibuat dengan ❤ oleh [@dyzcdn](https://github.com/dyzcdn)
