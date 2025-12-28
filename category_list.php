<?php
require 'db.php';

$category_code = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($category_code)) {
    die("Ralat: Tiada kod kategori dipilih. Sila kembali ke menu utama.");
}

/**
 * Fetch category details based on category code
 */
function getCategoryInfo($conn, $code)
{
    $stmt = $conn->prepare("SELECT * FROM categories WHERE category_code = :code");
    $stmt->execute([':code' => $code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Fetch all books belonging to a specific category
 * Ordered by shelf location for easier finding
 */
function getBooksByCategory($conn, $code)
{
    $stmt = $conn->prepare("SELECT * FROM books WHERE category_code = :code ORDER BY shelf_location ASC");
    $stmt->execute([':code' => $code]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Execute queries
$category = getCategoryInfo($conn, $category_code);
$books = getBooksByCategory($conn, $category_code);

if (!$category) {
    die("Ralat: Kategori tidak dijumpai dalam pangkalan data.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Buku - <?php echo htmlspecialchars($category['category_name']); ?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
        }

        .header-content p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .header-code {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 24px;
            font-weight: bold;
        }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            margin-bottom: 20px;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        th {
            text-align: left;
            padding: 15px;
            color: #495057;
            font-weight: 700;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-available {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-borrowed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .rack-loc {
            font-family: monospace;
            background: #eee;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="index.php" class="btn-back">← Kembali ke Utama</a>

        <div class="page-header">
            <div class="header-content">
                <h1><?php echo htmlspecialchars($category['category_name']); ?></h1>
                <p><?php echo htmlspecialchars($category['sub_text']); ?></p>
            </div>
            <div class="header-code">
                <?php echo htmlspecialchars($category['category_code']); ?>
            </div>
        </div>

        <div class="table-container">
            <?php if (count($books) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th width="15%">ID / Barcode</th>
                            <th width="40%">Tajuk Buku</th>
                            <th width="25%">Lokasi Rak</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td style="font-weight:bold; color:#007bff;">
                                    <?php echo htmlspecialchars($book['book_id']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($book['title']); ?>
                                </td>
                                <td>
                                    <span class="rack-loc">📍 <?php echo htmlspecialchars($book['shelf_location']); ?></span>
                                </td>
                                <td>
                                    <?php if ($book['is_available'] == 1): ?>
                                        <span class="badge badge-available">Ada di Rak</span>
                                    <?php else: ?>
                                        <span class="badge badge-borrowed">Sedang Dipinjam</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align:center; padding: 40px; color: #888;">
                    <p>Tiada buku didaftarkan untuk kategori ini lagi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>