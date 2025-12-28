<?php
// Start the session to manage user state and flash messages
session_start();
// Include database connection
require 'db.php';

// Initialize variables for messages
$message = "";
$msg_type = "";

// Check if there is a flash message set in the session (e.g., from a redirect)
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $msg_type = $_SESSION['flash_type'];
    // Clear the message after assigning it, so it doesn't show again on refresh
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle "Borrow Book" action
    if (isset($_POST['action']) && $_POST['action'] == 'borrow') {
        // Sanitize input
        $book_code = trim($_POST['book_code']);
        $student_id = trim($_POST['student_id']);
        $student_name = trim($_POST['student_name']);

        // Basic validation: Check if required fields are present
        if (empty($book_code) || empty($student_id)) {
            $_SESSION['flash_message'] = "Sila isi Kod Buku dan ID Pelajar.";
            $_SESSION['flash_type'] = "error";
        } else {
            // Check if book exists in the database
            $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = :id");
            $stmt->execute([':id' => $book_code]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$book) {
                $_SESSION['flash_message'] = "Buku tidak dijumpai (Kod salah).";
                $_SESSION['flash_type'] = "error";
            } elseif ($book['is_available'] == 0) {
                // Check if book is already borrowed
                $_SESSION['flash_message'] = "Buku ini sedang dipinjam oleh orang lain.";
                $_SESSION['flash_type'] = "error";
            } else {
                // Check if reader (student) exists
                $stmt = $conn->prepare("SELECT reader_id FROM readers WHERE student_id = :sid");
                $stmt->execute([':sid' => $student_id]);
                $reader = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($reader) {
                    $reader_id = $reader['reader_id'];
                } else {
                    // Start registration for new student if not found
                    if (empty($student_name)) $student_name = "Pelajar Baru";
                    $stmt = $conn->prepare("INSERT INTO readers (student_id, full_name) VALUES (:sid, :fname)");
                    $stmt->execute([':sid' => $student_id, ':fname' => $student_name]);
                    $reader_id = $conn->lastInsertId();
                }

                // Process the borrowing transaction
                // Set due date to 14 days from today
                $due_date = date('Y-m-d', strtotime('+14 days'));

                // Insert transaction record
                $stmt = $conn->prepare("INSERT INTO transactions (book_id, reader_id, due_date, return_date) VALUES (:bid, :rid, :due, NULL)");
                $stmt->execute([':bid' => $book_code, ':rid' => $reader_id, ':due' => $due_date]);

                // Update book status to unavailable (0)
                $stmt = $conn->prepare("UPDATE books SET is_available = 0 WHERE book_id = :bid");
                $stmt->execute([':bid' => $book_code]);

                $_SESSION['flash_message'] = "Berjaya! Buku $book_code telah dipinjam.";
                $_SESSION['flash_type'] = "success";
            }
        }
    }

    // Handle "Return Book" action
    if (isset($_POST['action']) && $_POST['action'] == 'return') {
        $book_code = trim($_POST['return_book_code']);
        $student_id = trim($_POST['return_student_id']);

        // Validation
        if (empty($book_code) || empty($student_id)) {
            $_SESSION['flash_message'] = "Sila isi Kod Buku dan ID Pelajar untuk pemulangan.";
            $_SESSION['flash_type'] = "error";
        } else {
            // Find active transaction (return_date IS NULL) for this book and student
            $sql = "SELECT t.trans_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id WHERE t.book_id = :bid AND r.student_id = :sid AND t.return_date IS NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':bid' => $book_code, ':sid' => $student_id]);
            $trans = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($trans) {
                // Update transaction with current Return Date
                $stmt = $conn->prepare("UPDATE transactions SET return_date = NOW() WHERE trans_id = :tid");
                $stmt->execute([':tid' => $trans['trans_id']]);

                // Set book availability back to 1 (Available)
                $stmt = $conn->prepare("UPDATE books SET is_available = 1 WHERE book_id = :bid");
                $stmt->execute([':bid' => $book_code]);

                $_SESSION['flash_message'] = "Terima Kasih! Buku $book_code telah dipulangkan.";
                $_SESSION['flash_type'] = "success";
            } else {
                $_SESSION['flash_message'] = "Ralat: Tiada rekod pinjaman aktif untuk kombinasi ini.";
                $_SESSION['flash_type'] = "error";
            }
        }
    }
    // Redirect to self to prevent form resubmission
    header("Location: index.php");
    exit();
}

// Gather Dashboard Statistics
$stats = [
    // Total number of transactions in history
    'total' => $conn->query("SELECT COUNT(*) FROM transactions")->fetchColumn(),
    // Active loans (books not yet returned)
    'active' => $conn->query("SELECT COUNT(*) FROM transactions WHERE return_date IS NULL")->fetchColumn(),
    // Overdue loans (active loans passed due date)
    'late' => $conn->query("SELECT COUNT(*) FROM transactions WHERE return_date IS NULL AND due_date < CURDATE()")->fetchColumn(),
    // Loans approaching due date (within next 3 days)
    'almost_late' => $conn->query("SELECT COUNT(*) FROM transactions WHERE return_date IS NULL AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)")->fetchColumn()
];

// Fetch all book categories for the grid display
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Fetch 5 most recent transactions for the table
$recent_transactions = $conn->query("SELECT t.*, r.full_name, r.student_id, b.title, b.book_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id JOIN books b ON t.book_id = b.book_id ORDER BY t.trans_id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Genre Chart (Doughnut)
// Group transactions by book category to see which genres are most popular
$genre_sql = "SELECT c.category_name, COUNT(t.trans_id) as total 
              FROM transactions t 
              JOIN books b ON t.book_id = b.book_id 
              JOIN categories c ON b.category_code = c.category_code 
              GROUP BY c.category_code ORDER BY total DESC";
$genre_data = $conn->query($genre_sql)->fetchAll(PDO::FETCH_ASSOC);

$genre_labels = [];
$genre_values = [];
foreach ($genre_data as $g) {
    $genre_labels[] = $g['category_name'];
    $genre_values[] = $g['total'];
}

// Prepare data for Trend Chart (Line)
// Group transactions by month to show activity trend
$trend_sql = "SELECT DATE_FORMAT(borrow_date, '%M') as month_name, MONTH(borrow_date) as month_num, COUNT(*) as total 
              FROM transactions 
              GROUP BY month_num 
              ORDER BY borrow_date ASC LIMIT 6";
$trend_data = $conn->query($trend_sql)->fetchAll(PDO::FETCH_ASSOC);

$trend_labels = [];
$trend_values = [];
foreach ($trend_data as $t) {
    $trend_labels[] = $t['month_name'];
    $trend_values[] = $t['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan KVSP</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Main CSS Styles for the dashboard layout */
        /* Reset box model */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 80%;
            max-width: 600px;
            padding: 15px;
            border-radius: 6px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .header-title h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header-title p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form {
            display: flex;
        }

        .search-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            outline: none;
            width: 250px;
        }

        .search-btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        .btn-top-student {
            text-decoration: none;
            background: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-top-student:hover {
            background: #5a6268;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-title {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-card.red-bg .stat-number {
            color: #dc3545;
        }

        .stat-card.orange-text .stat-number {
            color: #ffc107;
        }

        .charts-wrapper {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 50px;
        }

        .chart-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .chart-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #444;
            text-align: center;
        }

        .section-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 50px;
        }

        .cat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s;
        }

        .cat-card:hover {
            transform: translateY(-5px);
        }

        .cat-icon {
            font-size: 32px;
            margin-bottom: 15px;
            display: block;
        }

        .cat-code {
            font-weight: bold;
            color: #007bff;
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .operations-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 50px;
        }

        .op-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .op-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #444;
        }

        .input-group {
            display: flex;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 16px;
            outline: none;
        }

        .form-input:focus {
            border-color: #007bff;
        }

        .input-group .form-input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .btn-scan {
            background-color: #00acee;
            color: white;
            border: none;
            padding: 0 20px;
            cursor: pointer;
            font-weight: bold;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .btn-action {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-borrow {
            background-color: #00acee;
        }

        .btn-borrow:hover {
            background-color: #0095ce;
        }

        .btn-return {
            background-color: #d6d8db;
            color: #6c757d;
            cursor: not-allowed;
        }

        .btn-return.active {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        @media (max-width: 992px) {

            .stats-grid,
            .category-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-wrapper {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {

            .operations-container,
            .stats-grid,
            .category-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
        }
    </style>
</head>

<body>

    <?php if (!empty($message)): ?>
        <div class="alert <?php echo ($msg_type == 'success') ? 'alert-success' : 'alert-error'; ?>" id="flash-msg"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="main-container">

        <div class="top-bar">
            <div class="header-title">
                <h1>Sistem Perpustakaan KVSP</h1>
                <p>Kolej Vokasional Seberang Perai</p>
            </div>
            <div class="header-actions">
                <form action="search_result.php" method="GET" class="search-form">
                    <input type="text" name="q" class="search-input" placeholder="Cari Buku / Pelajar / Kategori...">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
                <a href="stats_list.php?type=top_students" class="btn-top-student">🏆 Pelajar Paling Aktif</a>
            </div>
        </div>

        <div class="stats-grid">
            <a href="stats_list.php?type=active" class="stat-link">
                <div class="stat-card">
                    <div class="stat-title">Pinjaman Aktif</div>
                    <div class="stat-number"><?php echo $stats['active']; ?></div>
                </div>
            </a>
            <a href="stats_list.php?type=total" class="stat-link">
                <div class="stat-card">
                    <div class="stat-title">Jumlah Transaksi</div>
                    <div class="stat-number"><?php echo $stats['total']; ?></div>
                </div>
            </a>
            <a href="stats_list.php?type=late" class="stat-link">
                <div class="stat-card red-bg">
                    <div class="stat-title">⚠ Pinjaman Lewat</div>
                    <div class="stat-number"><?php echo $stats['late']; ?></div>
                </div>
            </a>
            <a href="stats_list.php?type=almost_late" class="stat-link">
                <div class="stat-card orange-text">
                    <div class="stat-title">📅 Hampir Lewat</div>
                    <div class="stat-number"><?php echo $stats['almost_late']; ?></div>
                </div>
            </a>
        </div>

        <div class="charts-wrapper">
            <div class="chart-card">
                <div class="chart-title">📊 Genre Pilihan Pelajar</div>
                <canvas id="genreChart"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-title">📈 Trend Pinjaman Bulanan</div>
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="section-header">📚 Kategori Genre Buku</div>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="category_list.php?code=<?php echo $cat['category_code']; ?>" class="cat-card">
                    <span class="cat-icon">📖</span>
                    <span class="cat-code"><?php echo $cat['category_code']; ?></span>
                    <?php echo $cat['category_name']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Main Action Area: Borrow and Return Forms -->
        <div class="operations-container">
            <!-- Borrow Book Form -->
            <div class="op-card">
                <div class="op-title"><span style="color:#e91e63">📑</span> Pinjaman Buku</div>
                <form method="POST">
                    <input type="hidden" name="action" value="borrow">
                    <div class="form-group">
                        <label class="form-label">Kod Buku (Imbas/Taip)</label>
                        <div class="input-group">
                            <input type="text" class="form-input" name="book_code" id="borrow_book" placeholder="Contoh: FIC001">
                            <button type="button" class="btn-scan">📷 Imbas</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ID Pelajar</label>
                        <input type="text" class="form-input" name="student_id" id="borrow_student" placeholder="Contoh: KVS-2024-001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Pelajar</label>
                        <input type="text" class="form-input" name="student_name" placeholder="Nama penuh pelajar">
                    </div>
                    <button type="submit" class="btn-action btn-borrow">Pinjam Buku</button>
                </form>
            </div>

            <div class="op-card">
                <div class="op-title"><span style="color:#28a745">✅</span> Pemulangan Buku</div>
                <form method="POST">
                    <input type="hidden" name="action" value="return">
                    <div class="form-group">
                        <label class="form-label">Kod Buku</label>
                        <div class="input-group">
                            <input type="text" class="form-input" name="return_book_code" id="return_book" placeholder="Contoh: FIC001">
                            <button type="button" class="btn-scan">📷</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ID Pelajar</label>
                        <input type="text" class="form-input" name="return_student_id" id="return_student" placeholder="Contoh: KVS-2024-001">
                    </div>
                    <button type="submit" class="btn-action btn-return" id="btn_return" disabled>Pulang Buku</button>
                </form>
            </div>
        </div>

        <div class="section-header">📄 Rekod Transaksi Terkini</div>
        <?php if (count($recent_transactions) > 0): ?>
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <table>
                    <thead>
                        <tr>
                            <th>ID Pelajar</th>
                            <th>Nama</th>
                            <th>Buku</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_transactions as $trans): ?>
                            <tr>
                                <td><b><?php echo htmlspecialchars($trans['student_id']); ?></b></td>
                                <td><?php echo htmlspecialchars($trans['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($trans['title']); ?> (<?php echo $trans['book_id']; ?>)</td>
                                <td style="color: <?php echo $trans['return_date'] ? 'green' : 'orange'; ?>; font-weight:bold;">
                                    <?php echo $trans['return_date'] ? 'Dipulangkan' : 'Sedang Dipinjam'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="text-align:center;">Tiada rekod.</p>
        <?php endif; ?>
        <div style="height:50px;"></div>

    </div>
    <!-- Client-side Scripts for UI interactions and Chart initialization -->
    <script>
        // Flash Message Auto-Dismissal
        const flashMsg = document.getElementById('flash-msg');
        if (flashMsg) {
            setTimeout(() => {
                flashMsg.style.opacity = '0';
                setTimeout(() => flashMsg.remove(), 500);
            }, 3000);
        }

        const returnBookInput = document.getElementById('return_book');
        const returnStudentInput = document.getElementById('return_student');
        const returnButton = document.getElementById('btn_return');

        function checkReturnInputs() {
            if (returnBookInput.value.trim().length > 0 && returnStudentInput.value.trim().length > 0) {
                returnButton.classList.add('active');
                returnButton.disabled = false;
            } else {
                returnButton.classList.remove('active');
                returnButton.disabled = true;
            }
        }
        returnBookInput.addEventListener('input', checkReturnInputs);
        returnStudentInput.addEventListener('input', checkReturnInputs);

        // 3. Scanner Enter Key Logic
        const borrowBookInput = document.getElementById('borrow_book');
        const borrowStudentInput = document.getElementById('borrow_student');
        if (borrowBookInput) {
            borrowBookInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    borrowStudentInput.focus();
                }
            });
        }
        if (returnBookInput) {
            returnBookInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    returnStudentInput.focus();
                }
            });
        }

        const genreLabels = <?php echo json_encode($genre_labels); ?>;
        const genreValues = <?php echo json_encode($genre_values); ?>;
        const trendLabels = <?php echo json_encode($trend_labels); ?>;
        const trendValues = <?php echo json_encode($trend_values); ?>;

        new Chart(document.getElementById('genreChart'), {
            type: 'doughnut',
            data: {
                labels: genreLabels,
                datasets: [{
                    data: genreValues,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED', '#76A346']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: trendValues,
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>

</html>