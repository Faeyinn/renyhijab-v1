<?php
include 'db_connection.php'; // pastikan path file benar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Renyhijab</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Transaksi Renyhijab</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="transactions.php">Transaksi</a>
            <a href="customers.php">Customer</a>
        </nav>
    </header>
    <main>
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Invoice</th>
                    <th>Tanggal</th>
                    <th>ID Customer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP code to fetch and display transactions from the database -->
                <?php
                $result = $conn->query("SELECT * FROM Transaction_Header");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_inv']}</td>
                            <td>{$row['date_inv']}</td>
                            <td>{$row['id_cust']}</td>
                            <td>
                                <a href='view_transaction.php?id={$row['id_inv']}'>Lihat</a>
                                <a href='delete_transaction.php?id={$row['id_inv']}'>Hapus</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="add_transaction.php">Tambah Transaksi</a>
    </main>
</body>
</html>
