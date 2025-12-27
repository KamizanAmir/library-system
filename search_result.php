<?php
require 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$books = [];
$readers = [];
$categories = [];

if ($q != '') {
    $book_sql = "SELECT b.*, c.category_name,
                (SELECT CONCAT(r.full_name, ' (', DATE_FORMAT(t.borrow_date, '%d/%m/%Y'), ')') 
                 FROM transactions t JOIN readers r ON t.reader_id = r.reader_id 
                 WHERE t.book_id = b.book_id ORDER BY t.borrow_date DESC LIMIT 1) as last_borrower
                 FROM books b 
                 JOIN categories c ON b.category_code = c.category_code
                 WHERE b.book_id LIKE :q OR b.title LIKE :q";
    $stmt = $conn->prepare($book_sql);
    $stmt->execute([':q' => "%$q%"]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reader_sql = "SELECT r.*, COUNT(t.trans_id) as total_loans 
                   FROM readers r 
                   LEFT JOIN transactions t ON r.reader_id = t.reader_id 
                   WHERE r.full_name LIKE :q OR r.student_id LIKE :q
                   GROUP BY r.reader_id";
    $stmt = $conn->prepare($reader_sql);
    $stmt->execute([':q' => "%$q%"]);
    $readers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $cat_sql = "SELECT * FROM categories WHERE category_code LIKE :q OR category_name LIKE :q";
    $stmt = $conn->prepare($cat_sql);
    $stmt->execute([':q' => "%$q%"]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carian: <?php echo htmlspecialchars($q); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 30px;
        }

        .btn-back {
            text-decoration: none;
            color: white;
            background: #6c757d;
            padding: 10px 15px;
            border-radius: 4px;
        }

        .result-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
            font-weight: bold;
        }

        .bg-green {
            background: #28a745;
        }

        .bg-red {
            background: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <a href="index.php" class="btn-back">← Kembali ke Dashboard</a>
            <h1 style="margin-top:20px;">Hasil Carian: "<?php echo htmlspecialchars($q); ?>"</h1>
        </div>

        <?php if (count($readers) > 0): ?>
            <div class="result-section">
                <div class="section-title">👤 Pelajar Ditemui</div>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pelajar</th>
                            <th>Nama</th>
                            <th>Jumlah Buku Dipinjam</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($readers as $r): ?>
                            <tr>
                                <td><b><?php echo $r['student_id']; ?></b></td>
                                <td><?php echo $r['full_name']; ?></td>
                                <td><?php echo $r['total_loans']; ?> kali</td>
                                <td><a href="stats_list.php?type=active" style="color:#007bff;">Lihat Sejarah</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (count($books) > 0): ?>
            <div class="result-section">
                <div class="section-title">📖 Buku Ditemui</div>
                <table>
                    <thead>
                        <tr>
                            <th>Kod Buku</th>
                            <th>Tajuk</th>
                            <th>Rak</th>
                            <th>Status</th>
                            <th>Peminjam Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $b): ?>
                            <tr>
                                <td><b><?php echo $b['book_id']; ?></b></td>
                                <td><?php echo $b['title']; ?></td>
                                <td>📍 <?php echo $b['shelf_location']; ?></td>
                                <td>
                                    <?php if ($b['is_available']): ?>
                                        <span class="badge bg-green">Ada di Rak</span>
                                    <?php else: ?>
                                        <span class="badge bg-red">Sedang Dipinjam</span>
                                    <?php endif; ?>
                                </td>
                                <td style="font-size:13px; color:#666;">
                                    <?php echo $b['last_borrower'] ? $b['last_borrower'] : 'Tiada rekod'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (count($categories) > 0): ?>
            <div class="result-section">
                <div class="section-title">📚 Kategori Ditemui</div>
                <?php foreach ($categories as $cat): ?>
                    <div style="margin-bottom:15px;">
                        <h3 style="margin:0;"><?php echo $cat['category_name']; ?> (<?php echo $cat['category_code']; ?>)</h3>
                        <p style="color:#666; margin:5px 0 10px 0;"><?php echo $cat['sub_text']; ?></p>
                        <a href="category_list.php?code=<?php echo $cat['category_code']; ?>" style="color:#007bff;">Lihat Semua Buku dalam Kategori Ini →</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($books) == 0 && count($readers) == 0 && count($categories) == 0): ?>
            <p style="text-align:center; font-size:18px; color:#888;">Tiada rekod dijumpai. Sila cuba kata kunci lain.</p>
        <?php endif; ?>

    </div>
</body>

</html>