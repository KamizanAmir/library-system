<?php
require 'db.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$page_title = "Senarai Transaksi";
$results = [];

switch ($type) {
    case 'active':
        // Show books currently borrowed (Return Date is NULL)
        $page_title = "Pinjaman Aktif (Sedang Dipinjam)";
        $sql = "SELECT t.*, r.full_name, r.student_id, b.title, b.book_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id JOIN books b ON t.book_id = b.book_id WHERE t.return_date IS NULL ORDER BY t.borrow_date DESC";
        $results = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'late':
        // Show overdue books (Return Date is NULL AND Due Date < Today)
        $page_title = "Pinjaman Lewat (Melebihi Tarikh)";
        $sql = "SELECT t.*, r.full_name, r.student_id, b.title, b.book_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id JOIN books b ON t.book_id = b.book_id WHERE t.return_date IS NULL AND t.due_date < CURDATE() ORDER BY t.due_date ASC";
        $results = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'almost_late':
        // Show books due soon (within next 3 days)
        $page_title = "Hampir Lewat (Akan Datang 3 Hari)";
        $sql = "SELECT t.*, r.full_name, r.student_id, b.title, b.book_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id JOIN books b ON t.book_id = b.book_id WHERE t.return_date IS NULL AND t.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) ORDER BY t.due_date ASC";
        $results = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'top_students':
        // Show Top 10 Students by total number of transactions
        $page_title = "🏆 Top 10 Pelajar Paling Aktif";
        $sql = "SELECT r.student_id, r.full_name, COUNT(t.trans_id) as total_loans 
                FROM transactions t 
                JOIN readers r ON t.reader_id = r.reader_id 
                GROUP BY r.reader_id 
                ORDER BY total_loans DESC 
                LIMIT 10";
        $results = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'total':
    default:
        // Default: Show all transactions history
        $page_title = "Jumlah Semua Transaksi";
        $sql = "SELECT t.*, r.full_name, r.student_id, b.title, b.book_id FROM transactions t JOIN readers r ON t.reader_id = r.reader_id JOIN books b ON t.book_id = b.book_id ORDER BY t.borrow_date DESC";
        $results = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        break;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - KVSP</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f8ff;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }

        .date-red {
            color: red;
            font-weight: bold;
        }

        .date-green {
            color: green;
            font-weight: bold;
        }

        .book-code {
            font-family: monospace;
            font-weight: bold;
            color: #444;
            background: #eee;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .rank {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="btn-back">← Kembali</a>
        <h2><?php echo htmlspecialchars($page_title); ?></h2>

        <?php if (count($results) > 0): ?>
            <table>
                <?php if ($type == 'top_students'): ?>
                    <thead>
                        <tr>
                            <th>Kedudukan</th>
                            <th>ID Pelajar</th>
                            <th>Nama Pelajar</th>
                            <th>Jumlah Buku Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1;
                        foreach ($results as $row): ?>
                            <tr>
                                <td><span class="rank">#<?php echo $rank++; ?></span></td>
                                <td><b><?php echo htmlspecialchars($row['student_id']); ?></b></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_loans']); ?> Buku</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php else: ?>
                    <thead>
                        <tr>
                            <th>ID Pelajar</th>
                            <th>Nama Pelajar</th>
                            <th>Kod Buku</th>
                            <th>Buku Dipinjam</th>
                            <th>Tarikh Pinjam</th>
                            <th>Tarikh Mesti Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $row):
                            $due = strtotime($row['due_date']);
                            $is_overdue = $due < time() && $row['return_date'] == NULL;
                        ?>
                            <tr>
                                <td style="font-weight:bold; color:#007bff;"><?php echo htmlspecialchars($row['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><span class="book-code"><?php echo htmlspecialchars($row['book_id']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['borrow_date'])); ?></td>
                                <td class="<?php echo $is_overdue ? 'date-red' : 'date-green'; ?>"><?php echo date('d/m/Y', strtotime($row['due_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#777; margin-top:30px;">Tiada rekod dijumpai.</p>
        <?php endif; ?>
    </div>
</body>

</html>