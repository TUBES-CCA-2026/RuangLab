# RuangLab 🏗️
Sistem Penjadwalan & Reservasi Ruangan Laboratorium Berbasis Web  
**UMI Makassar — ICo LABS**

---

## Anggota Tim
| Nama | Tugas |
|------|-------|
| Sa'ad | Backend, ERD, Flowchart |
| Qamri | Flowchart, CRUD Aslab |
| Mekar | UI/UX Figma, Use Case |
| Fahmi | Frontend, Dokumentasi |

---

## Cara Setup di XAMPP

### 1. Clone / copy folder
```
Salin folder `ruanglab` ke: C:/xampp/htdocs/ruanglab
```

### 2. Import database
- Buka `http://localhost/phpmyadmin`
- Klik **Import** → pilih file `database/ruanglab.sql`
- Klik **Go**

### 3. Konfigurasi database (jika perlu)
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');       // password phpMyAdmin kamu
define('DB_NAME', 'ruanglab');
```

### 4. Jalankan
Buka browser: `http://localhost/ruanglab/login.php`

---

## Akun Testing

| Email | Password | Role |
|-------|----------|------|
| laboran@umi.ac.id | password123 | Laboran |
| aslab@umi.ac.id | password123 | Aslab |
| peminjam@umi.ac.id | password123 | Peminjam |

---

## Struktur Folder
```
ruanglab/
├── login.php              ← Halaman login
├── logout.php             ← Proses logout
├── unauthorized.php       ← Halaman akses ditolak
├── config/
│   ├── database.php       ← Koneksi database
│   └── auth.php           ← Helper cek login & role
├── dashboard/
│   ├── peminjam.php       ← Dashboard Peminjam
│   ├── aslab.php          ← Dashboard Aslab
│   └── laboran.php        ← Dashboard Laboran
├── assets/
│   └── css/
│       └── style.css      ← Stylesheet utama
├── database/
│   └── ruanglab.sql       ← SQL setup & seed
└── README.md              ← Dokumentasi ini
```

---

## Stack Teknologi
- **Backend**: PHP 8+ (native, tanpa framework)
- **Database**: MySQL (via XAMPP)
- **Frontend**: HTML5, CSS3, Vanilla JS
- **Icon**: Tabler Icons
- **Font**: Inter (Google Fonts)
