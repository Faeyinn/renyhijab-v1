<?php 
include 'koneksi.php';

// Proses tambah/edit/hapus
if(isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];
    
    if(isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $query = "UPDATE customer SET nama='$nama', email='$email', telp='$telp' WHERE id=$id";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil diupdate!'); window.location='customer.php';</script>";
    } else {
        // Insert
        $query = "INSERT INTO customer (nama, email, telp) VALUES ('$nama', '$email', '$telp')";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil ditambah!'); window.location='customer.php';</script>";
    }
}

// Proses hapus
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM customer WHERE id=$id";
    mysqli_query($conn, $query);
    echo "<script>alert('Data berhasil dihapus!'); window.location='customer.php';</script>";
}

// Ambil data untuk edit
$edit_data = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM customer WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Kelola Customer</h2>
        
        <!-- Navigasi -->
        <nav class="nav nav-pills nav-justified mb-4">
            <a class="nav-link" href="index.php">Dashboard</a>
            <a class="nav-link" href="kategori.php">Kategori</a>
            <a class="nav-link" href="produk.php">Produk</a>
            <a class="nav-link active" href="customer.php">Customer</a>
            <a class="nav-link" href="order.php">Order</a>
        </nav>

        <div class="row">
            <!-- Form Tambah/Edit -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?= $edit_data ? 'Edit' : 'Tambah' ?> Customer</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if($edit_data): ?>
                                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Customer</label>
                                <input type="text" class="form-control" name="nama" 
                                       value="<?= $edit_data ? $edit_data['nama'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= $edit_data ? $edit_data['email'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" name="telp" 
                                       value="<?= $edit_data ? $edit_data['telp'] : '' ?>">
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if($edit_data): ?>
                                <a href="customer.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Customer</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM customer ORDER BY id DESC";
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['telp'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <a href="customer.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="customer.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
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
