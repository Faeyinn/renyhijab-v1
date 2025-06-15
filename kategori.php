<?php 
include 'koneksi.php';

// Proses tambah/edit/hapus
if(isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    
    if(isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $query = "UPDATE kategori SET nama='$nama' WHERE id=$id";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil diupdate!'); window.location='kategori.php';</script>";
    } else {
        // Insert
        $query = "INSERT INTO kategori (nama) VALUES ('$nama')";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil ditambah!'); window.location='kategori.php';</script>";
    }
}

// Proses hapus
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM kategori WHERE id=$id";
    mysqli_query($conn, $query);
    echo "<script>alert('Data berhasil dihapus!'); window.location='kategori.php';</script>";
}

// Ambil data untuk edit
$edit_data = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM kategori WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Kelola Kategori</h2>
        
        <!-- Navigasi -->
        <nav class="nav nav-pills nav-justified mb-4">
            <a class="nav-link" href="index.php">Dashboard</a>
            <a class="nav-link active" href="kategori.php">Kategori</a>
            <a class="nav-link" href="produk.php">Produk</a>
            <a class="nav-link" href="customer.php">Customer</a>
            <a class="nav-link" href="order.php">Order</a>
        </nav>

        <div class="row">
            <!-- Form Tambah/Edit -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?= $edit_data ? 'Edit' : 'Tambah' ?> Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if($edit_data): ?>
                                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" name="nama" 
                                       value="<?= $edit_data ? $edit_data['nama'] : '' ?>" required>
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if($edit_data): ?>
                                <a href="kategori.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Kategori</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kategori</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM kategori ORDER BY id DESC";
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <a href="kategori.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="kategori.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
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
