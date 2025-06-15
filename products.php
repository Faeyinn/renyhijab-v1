<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Renyhijab</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Produk Renyhijab</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="transactions.php">Transaksi</a>
            <a href="customers.php">Customer</a>
        </nav>
    </header>
    <main>
        <h2>Daftar Produk</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- PHP code to fetch and display products from the database -->
                <?php
                // Include database connection
                include 'db_connection.php';
                $result = $conn->query("SELECT * FROM Product");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_product']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['cost']}</td>
                            <td>{$row['stok']}</td>
                            <td>
                                <a href='edit_product.php?id={$row['id_product']}'>Edit</a>
                                <a href='delete_product.php?id={$row['id_product']}'>Hapus</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="add_product.php">Tambah Produk</a>
    </main>
</body>
</html>
