# Badminton Hall Pemda

![Badminton Hall Pemda](doc/badminton-hall-banner.png)

[Badminton Hall Pemda](https://github.com/username/badminton_hall) adalah aplikasi layanan booking online lapangan badminton yang memudahkan pelanggan untuk memesan jadwal bermain, memilih metode pembayaran, dan mendapatkan informasi promo terbaru.

## Preview

### Logo
![Preview Desktop](doc/logo.png)


## Latar Belakang

Di era digital saat ini, pengelolaan fasilitas olahraga sudah beralih dari metode manual menjadi sistem online. Melalui internet, pelanggan dapat melakukan pemesanan lapangan badminton kapan saja dan di mana saja tanpa perlu datang langsung.

Badminton Hall Pemda hadir untuk memberikan solusi atas permasalahan seperti:
- Kesulitan memesan lapangan karena harus datang langsung.
- Jadwal yang tumpang tindih akibat pencatatan manual.
- Kurangnya informasi terkait promo dan ketersediaan lapangan.

Dengan sistem booking online, pelanggan dapat memesan lapangan secara mudah, sementara pengelola dapat mengatur jadwal dan transaksi secara terpusat.

## Tujuan dan Hasil yang Akan Dicapai

- Mempermudah pelanggan dalam memesan lapangan badminton.
- Memberikan informasi jadwal dan promo secara real-time.
- Membantu pengelola dalam mengatur jadwal, pembayaran, dan laporan transaksi.
- Mengurangi risiko double booking.

## Analisa SWOT

### Strengths (S)
- Pemesanan lapangan yang cepat dan mudah.
- Informasi ketersediaan lapangan dan promo yang jelas.
- Pengelolaan transaksi dan jadwal yang terpusat.

### Weaknesses (W)
- Membutuhkan koneksi internet.
- Pelanggan yang belum terbiasa dengan teknologi memerlukan adaptasi.
- Keamanan data dan pembayaran harus dijaga ketat.

### Opportunities (O)
- Meningkatkan jumlah pelanggan melalui kemudahan pemesanan.
- Menarik sponsor atau event melalui platform online.
- Memperluas layanan ke cabang lain.

### Threats (T)
- Kompetitor dengan sistem serupa.
- Gangguan teknis seperti server down.
- Potensi serangan siber.

## Instalasi

Pastikan sudah terpasang:
- PHP versi 8+
- [Composer](https://getcomposer.org/download/)
- Web Server (Apache/Nginx)
- Database MySQL/MariaDB

Langkah instalasi:
```bash
git clone https://github.com/username/badminton_hall.git
cd badminton_hall
cp .env.example .env
composer install
php spark key:generate
# Konfigurasi database di file .env
php spark migrate --seed
php spark serve
# Buka http://localhost:8080/
