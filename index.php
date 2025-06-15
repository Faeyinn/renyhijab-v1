<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Belajar CRUD PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Sistem CRUD Sederhana</h1>
        
        <!-- Navigasi -->
        <nav class="nav nav-pills nav-justified mb-4">
            <a class="nav-link active" href="index.php">Dashboard</a>
            <a class="nav-link" href="kategori.php">Kategori</a>
            <a class="nav-link" href="produk.php">Produk</a>
            <a class="nav-link" href="customer.php">Customer</a>
            <a class="nav-link" href="order.php">Order</a>
        </nav>

        <!-- Statistik -->
        <div class="row mb-4">
            <?php
            $kategori = mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori");
            $produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM produk");
            $customer = mysqli_query($conn, "SELECT COUNT(*) as total FROM customer");
            $order = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
            ?>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Kategori</h5>
                        <h2 class="text-primary"><?= mysqli_fetch_assoc($kategori)['total'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Produk</h5>
                        <h2 class="text-success"><?= mysqli_fetch_assoc($produk)['total'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Customer</h5>
                        <h2 class="text-warning"><?= mysqli_fetch_assoc($customer)['total'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Order</h5>
                        <h2 class="text-danger"><?= mysqli_fetch_assoc($order)['total'] ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Order Terbaru -->
        <div class="card">
            <div class="card-header">
                <h5>Order Terbaru</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT o.*, c.nama as customer_nama, p.nama as produk_nama 
                                 FROM orders o 
                                 JOIN customer c ON o.customer_id = c.id 
                                 JOIN produk p ON o.produk_id = p.id 
                                 ORDER BY o.id DESC LIMIT 5";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($result)):
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['customer_nama'] ?></td>
                            <td><?= $row['produk_nama'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp <?= number_format($row['total']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
