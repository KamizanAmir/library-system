# Sistem Perpustakaan KVSP (KVSP Library System)

A web-based library management system designed for Kolej Vokasional Seberang Perai (KVSP) to manage book loans, returns, and track student borrowing statistics.

## ✨ Features

- **Dashboard**: Real-time overview of active loans, total transactions, late returns, and upcoming due dates.
- **Transaction Management**:
  - **Borrowing**: Scan or type book codes to issue books to students.
  - **Returns**: Easy process for returning books.
  - **Status**: Visual indicators for Active (Green) and Overdue (Red) loans.
- **Top Students Leaderboard** 🏆: Gamification feature displaying the top 10 most active readers.
- **Category Browsing**: Visual grid of book categories (e.g., Fiction, General, Technology).
- **Clean Interface**: Modern, responsive user interface.

## 🛠 Prerequisites

- **Server**: XAMPP (Apache + MySQL)
- **Language**: PHP 7.4+
- **Database**: MySQL

## 🚀 Installation & Setup

1. **Clone/Download** the repository to your local web server folder (e.g., `C:\xampp\htdocs\library-system`).
2. **Database Setup**:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `kvsp_library`.
   - Import the provided SQL file (if available) or create the necessary tables (`books`, `readers`, `transactions`, `categories`).
3. **Configuration**:
   - The database connection is handled in `db.php`. Ensure your credentials match:
     ```php
     $host = 'localhost';
     $dbname = 'kvsp_library';
     $username = 'root';
     $password = ''; // Default XAMPP is empty
     ```
4. **Run the Application**:
   - Open your browser and navigate to: `http://localhost/library-system`

## 📂 Project Structure

- `index.php` - Main Dashboard & Transaction interface.
- `db.php` - Database connection configuration.
- `stats_list.php` - Detailed views for transaction lists (Active, Late, etc.).
- `category_list.php` - Lists books within a specific category.
- `top_students.php` - Leaderboard for top borrowers.

## 👥 Usage

1. **Borrowing a Book**:
   - Go to "Pinjaman Buku" section on the dashboard.
   - Enter/Scan Book Code and Student ID.
   - Click "Pinjam Buku".
2. **Returning a Book**:
   - Go to "Pemulangan Buku" section.
   - Enter/Scan Book Code.
   - Click "Pulang Buku".
3. **Checking Stats**:
   - Click on any of the colored cards at the top to see detailed lists of late or active loans.
   - Click "Pelajar Contoh" to see the top readers.

---

_Developed for FYP Project._
