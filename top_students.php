<?php
require 'db.php';

$page_title = "Senarai Pelajar Contoh (Top 10 Peminjam)";

// Fetch Top 10 Students who borrowed the most books
// Group by student_id to count transactions
// Order by total_books descending
$sql = "SELECT r.student_id, r.full_name, COUNT(t.trans_id) as total_books 
        FROM transactions t 
        JOIN readers r ON t.reader_id = r.reader_id 
        GROUP BY r.student_id 
        ORDER BY total_books DESC 
        LIMIT 10";
$stmt = $conn->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            display: flex;
            align-items: center;
            gap: 10px;
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

        .medal {
            font-size: 1.2em;
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="index.php" class="btn-back">← Kembali</a>

        <h2>🏆 <?php echo htmlspecialchars($page_title); ?></h2>

        <?php if (count($results) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>ID Pelajar</th>
                        <th>Nama Pelajar</th>
                        <th>Jumlah Buku Dipinjam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    foreach ($results as $row):
                    ?>
                        <tr>
                            <td>
                                <?php
                                if ($rank == 1) echo '<span class="medal">🥇</span>';
                                elseif ($rank == 2) echo '<span class="medal">🥈</span>';
                                elseif ($rank == 3) echo '<span class="medal">🥉</span>';
                                else echo $rank;
                                ?>
                            </td>
                            <td style="font-weight:bold; color:#007bff;"><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td style="font-weight:bold; font-size:1.1em;"><?php echo htmlspecialchars($row['total_books']); ?></td>
                        </tr>
                    <?php
                        $rank++;
                    endforeach;
                    ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#777; margin-top:30px;">Tiada rekod transaksi dijumpai.</p>
        <?php endif; ?>
    </div>

</body>

</html>