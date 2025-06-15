<?php
include 'db_connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer - Renyhijab</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Customer Renyhijab</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="transactions.php">Transaksi</a>
            <a href="customers.php">Customer</a>
        </nav>
    </header>
    <main>
        <h2>Daftar Customer</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Customer</th>
                    <th>Nama Customer</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP code to fetch and display customers from the database -->
                <?php
                $result = $conn->query("SELECT * FROM Customer");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_cust']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>
                                <a href='edit_customer.php?id={$row['id_cust']}'>Edit</a>
                                <a href='delete_customer.php?id={$row['id_cust']}'>Hapus</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="add_customer.php">Tambah Customer</a>
    </main>
</body>
</html>
