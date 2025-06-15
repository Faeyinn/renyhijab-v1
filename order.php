<?php 
include 'koneksi.php';

// Proses tambah/edit/hapus
if(isset($_POST['submit'])) {
    $customer_id = $_POST['customer_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah = $_POST['jumlah'];
    
    // Ambil harga produk
    $harga_query = "SELECT harga FROM produk WHERE id=$produk_id";
    $harga_result = mysqli_query($conn, $harga_query);
    $harga_data = mysqli_fetch_assoc($harga_result);
    $total = $harga_data['harga'] * $jumlah;
    
    if(isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $query = "UPDATE orders SET customer_id=$customer_id, produk_id=$produk_id, jumlah=$jumlah, total=$total WHERE id=$id";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil diupdate!'); window.location='order.php';</script>";
    } else {
        // Insert
        $query = "INSERT INTO orders (customer_id, produk_id, jumlah, total) VALUES ($customer_id, $produk_id, $jumlah, $total)";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil ditambah!'); window.location='order.php';</script>";
    }
}

// Proses hapus
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM orders WHERE id=$id";
    mysqli_query($conn, $query);
    echo "<script>alert('Data berhasil dihapus!'); window.location='order.php';</script>";
}

// Ambil data untuk edit
$edit_data = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM orders WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Kelola Order</h2>
        
        <!-- Navigasi -->
        <nav class="nav nav-pills nav-justified mb-4">
            <a class="nav-link" href="index.php">Dashboard</a>
            <a class="nav-link" href="kategori.php">Kategori</a>
            <a class="nav-link" href="produk.php">Produk</a>
            <a class="nav-link" href="customer.php">Customer</a>
            <a class="nav-link active" href="order.php">Order</a>
        </nav>

        <div class="row">
            <!-- Form Tambah/Edit -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?= $edit_data ? 'Edit' : 'Tambah' ?> Order</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if($edit_data): ?>
                                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <select class="form-select" name="customer_id" required>
                                    <option value="">Pilih Customer</option>
                                    <?php
                                    $customer_query = "SELECT * FROM customer";
                                    $customer_result = mysqli_query($conn, $customer_query);
                                    while($customer = mysqli_fetch_assoc($customer_result)):
                                    ?>
                                        <option value="<?= $customer['id'] ?>" 
                                            <?= ($edit_data && $edit_data['customer_id'] == $customer['id']) ? 'selected' : '' ?>>
                                            <?= $customer['nama'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Produk</label>
                                <select class="form-select" name="produk_id" required>
                                    <option value="">Pilih Produk</option>
                                    <?php
                                    $produk_query = "SELECT * FROM produk WHERE stok > 0";
                                    $produk_result = mysqli_query($conn, $produk_query);
                                    while($produk = mysqli_fetch_assoc($produk_result)):
                                    ?>
                                        <option value="<?= $produk['id'] ?>" 
                                            <?= ($edit_data && $edit_data['produk_id'] == $produk['id']) ? 'selected' : '' ?>>
                                            <?= $produk['nama'] ?> - Rp <?= number_format($produk['harga']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="jumlah" min="1"
                                       value="<?= $edit_data ? $edit_data['jumlah'] : '' ?>" required>
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if($edit_data): ?>
                                <a href="order.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Order</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT o.*, c.nama as customer_nama, p.nama as produk_nama 
                                         FROM orders o 
                                         JOIN customer c ON o.customer_id = c.id 
                                         JOIN produk p ON o.produk_id = p.id 
                                         ORDER BY o.id DESC";
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
                                    <td>
                                        <a href="order.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="order.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
