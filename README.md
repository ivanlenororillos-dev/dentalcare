# Dental Care - Clinic Client Management System

A comprehensive web-based dental clinic management system built with Laravel 12 (LAMP stack compatible). The system tracks client records and maintains a detailed per-tooth history using an interactive, clickable digital recreation of the standard 32-tooth dental chart.

## Features

- **Interactive Dental Chart**: SVG-based clickable chart matching the Universal Numbering System (1-32) with all four quadrants (Upper Right, Upper Left, Lower Left, Lower Right)
- **Per-Tooth History Tracking**: Click any tooth to view its full procedure history and log new treatments
- **Visual Status Overlays**: Color-coded tooth statuses (Healthy, Cavity, Filled, Crowned, Extracted, Root Canal, Implant) with symbol indicators
- **Client Management**: Full CRUD operations with search/filter and soft deletes
- **Dentist Management**: Staff directory with license tracking
- **PDF Reports**: Downloadable print-ready PDF of any client's chart and full history
- **Authentication**: Secure login with role-based access (Admin, Dentist, Receptionist)
- **Dashboard**: At-a-glance statistics with recent activity feed

## Status Color Legend

| Status     | Color  | Symbol |
|------------|--------|--------|
| Healthy    | Green  | —      |
| Cavity     | Yellow | C      |
| Filled     | Blue   | F      |
| Crowned    | Gray   | Cr     |
| Extracted  | Red    | X      |
| Root Canal | Orange | RC     |
| Implant    | Purple | Im     |

## Tech Stack

- **Backend**: PHP 8.1+ / Laravel 12 (compatible with 10+)
- **Frontend**: Blade templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 5.7+ (XAMPP compatible)
- **PDF Generation**: barryvdh/laravel-dompdf
- **Authentication**: Laravel Breeze (Blade stack)
- **Build Tool**: Vite

## Requirements

- PHP 8.1 or higher
- Composer 2.x
- Node.js 18+ and npm
- MySQL 5.7+ (or MariaDB 10.3+)
- Apache with mod_rewrite (for XAMPP/LAMP deployment)

## Local Development Setup (XAMPP)

### 1. Clone / Copy the Project

Place the project in your desired location (e.g., `C:\xampp\htdocs\dental-care` or `C:\Users\USER\Desktop\Dental Care`).

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` to match your XAMPP MySQL settings:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_clinic
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create the Database

Open phpMyAdmin (http://localhost/phpmyadmin) or use the MySQL CLI:

```sql
CREATE DATABASE dental_clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations and Seed

```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- The `teeth_master` table with all 32 teeth mapped from the dental chart
- A default admin user: `admin@dentalcare.com` / `password`

### 6. Build Frontend Assets

```bash
npm run build
```

For development with hot-reloading:

```bash
npm run dev
```

### 7. Start the Development Server

```bash
php artisan serve
```

Visit http://localhost:8000 and log in with:
- **Email**: `admin@dentalcare.com`
- **Password**: `password`

## Database Schema

### Tables

- **users** — Authentication accounts with roles (admin, dentist, receptionist)
- **dentists** — Staff directory linked to user accounts
- **clients** — Patient records with contact info and medical notes (soft-deletable)
- **teeth_master** — Static reference table with all 32 teeth (number, quadrant, name, type)
- **tooth_history** — Per-tooth procedure log linked to clients and dentists

### Entity Relationships

```
users ←——— dentists ←——— tooth_history ———→ clients
                              ↑
                        teeth_master (reference)
```

## Project Structure (Key Files)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ClientController.php        # Client CRUD
│   │   ├── DashboardController.php     # Dashboard stats
│   │   ├── DentalChartController.php   # Chart view data
│   │   ├── DentistController.php       # Dentist CRUD
│   │   ├── ReportController.php        # PDF generation
│   │   └── ToothHistoryController.php  # JSON API for tooth history
│   ├── Middleware/
│   │   └── CheckRole.php               # Role-based access control
│   └── Requests/
│       ├── StoreClientRequest.php
│       └── StoreToothHistoryRequest.php
├── Models/
│   ├── Client.php
│   ├── Dentist.php
│   ├── TeethMaster.php
│   └── ToothHistory.php
database/
├── migrations/                         # Schema definitions
└── seeders/
    ├── DatabaseSeeder.php
    └── TeethMasterSeeder.php           # All 32 teeth from the chart
resources/views/
├── clients/
│   ├── chart.blade.php                 # Interactive SVG dental chart
│   ├── index.blade.php                 # Client listing with search
│   ├── show.blade.php                  # Client profile
│   ├── create.blade.php / edit.blade.php
│   └── _form.blade.php
├── dentists/                           # Dentist management views
├── components/
│   └── status-legend.blade.php         # Status color key component
├── reports/
│   └── client-chart-pdf.blade.php      # PDF report template
└── dashboard.blade.php
tests/Feature/
├── ClientTest.php                      # Client CRUD tests
├── ToothHistoryTest.php                # Tooth history API tests
└── DentalChartTest.php                 # Chart & seeder tests
```

## Running Tests

```bash
php artisan test
```

Tests use SQLite in-memory database and cover:
- Client CRUD operations and validation
- Tooth history JSON API (create, read, update, delete)
- Dental chart view data loading
- TeethMaster seeder integrity (all 32 teeth, correct quadrants)
- PDF report generation
- Authentication gates

## Tooth Numbering Reference

Based on the Universal Numbering System:

| # | Quadrant | Name | Type |
|---|----------|------|------|
| 1 | Upper Right | Wisdom Tooth (3rd Molar) | Molar |
| 2 | Upper Right | 12-year Molar (2nd Molar) | Molar |
| 3 | Upper Right | 6-year Molar (1st Molar) | Molar |
| 4 | Upper Right | 2nd PreMolar (2nd Bicuspid) | Premolar |
| 5 | Upper Right | 1st PreMolar (1st Bicuspid) | Premolar |
| 6 | Upper Right | Canine/Eye Tooth (Cuspid) | Canine |
| 7 | Upper Right | Lateral Incisor | Incisor |
| 8 | Upper Right | Central Incisor | Incisor |
| 9 | Upper Left | Central Incisor | Incisor |
| 10 | Upper Left | Lateral Incisor | Incisor |
| 11 | Upper Left | Canine/Eye Tooth (Cuspid) | Canine |
| 12 | Upper Left | 1st PreMolar (1st Bicuspid) | Premolar |
| 13 | Upper Left | 2nd PreMolar (2nd Bicuspid) | Premolar |
| 14 | Upper Left | 6-year Molar (1st Molar) | Molar |
| 15 | Upper Left | 12-year Molar (2nd Molar) | Molar |
| 16 | Upper Left | Wisdom Tooth (3rd Molar) | Molar |
| 17 | Lower Left | Wisdom Tooth (3rd Molar) | Molar |
| 18 | Lower Left | 12-year Molar (2nd Molar) | Molar |
| 19 | Lower Left | 6-year Molar (1st Molar) | Molar |
| 20 | Lower Left | 2nd PreMolar (2nd Bicuspid) | Premolar |
| 21 | Lower Left | 1st PreMolar (1st Bicuspid) | Premolar |
| 22 | Lower Left | Canine/Eye Tooth (Cuspid) | Canine |
| 23 | Lower Left | Lateral Incisor | Incisor |
| 24 | Lower Left | Central Incisor | Incisor |
| 25 | Lower Right | Central Incisor | Incisor |
| 26 | Lower Right | Lateral Incisor | Incisor |
| 27 | Lower Right | Canine/Eye Tooth (Cuspid) | Canine |
| 28 | Lower Right | 1st PreMolar (1st Bicuspid) | Premolar |
| 29 | Lower Right | 2nd PreMolar (2nd Bicuspid) | Premolar |
| 30 | Lower Right | 6-year Molar (1st Molar) | Molar |
| 31 | Lower Right | 12-year Molar (2nd Molar) | Molar |
| 32 | Lower Right | Wisdom Tooth (3rd Molar) | Molar |

## LAMP Production Deployment Guide

### Server Requirements

- Ubuntu 20.04+ (or CentOS 8+)
- Apache 2.4+ with `mod_rewrite` enabled
- MySQL 5.7+ or MariaDB 10.3+
- PHP 8.1+ with extensions: `mbstring`, `xml`, `curl`, `mysql`, `zip`, `gd`
- Composer 2.x
- Node.js 18+ (for building assets)

### Step-by-Step Deployment

#### 1. Install System Dependencies

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mysql-server php php-cli php-mbstring php-xml php-curl php-mysql php-zip php-gd unzip git nodejs npm -y
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### 2. Configure MySQL

```bash
sudo mysql_secure_installation
sudo mysql -u root -p
```

```sql
CREATE DATABASE dental_clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dentalapp'@'localhost' IDENTIFIED BY 'YOUR_SECURE_PASSWORD';
GRANT ALL PRIVILEGES ON dental_clinic.* TO 'dentalapp'@'localhost';
FLUSH PRIVILEGES;
```

#### 3. Deploy Application

```bash
cd /var/www
sudo git clone <your-repo-url> dental-care
cd dental-care
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
```

#### 4. Configure Environment

Edit `/var/www/dental-care/.env`:

```dotenv
APP_NAME="Dental Care"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dental_clinic
DB_USERNAME=dentalapp
DB_PASSWORD=YOUR_SECURE_PASSWORD

SESSION_DRIVER=file
CACHE_STORE=file
```

#### 5. Build and Finalize

```bash
npm install && npm run build
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 6. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/dental-care
sudo chmod -R 755 /var/www/dental-care
sudo chmod -R 775 /var/www/dental-care/storage
sudo chmod -R 775 /var/www/dental-care/bootstrap/cache
```

#### 7. Apache Virtual Host

Create `/etc/apache2/sites-available/dental-care.conf`:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/dental-care/public

    <Directory /var/www/dental-care/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/dental-care-error.log
    CustomLog ${APACHE_LOG_DIR}/dental-care-access.log combined
</VirtualHost>
```

```bash
sudo a2ensite dental-care.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

#### 8. SSL/HTTPS (Recommended)

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d your-domain.com
```

### Maintenance Commands

```bash
# Clear caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Run migrations after updates
php artisan migrate --force

# Rebuild caches
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## License

This project is proprietary software for dental clinic management.
