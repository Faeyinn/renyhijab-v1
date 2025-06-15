<?php 
include 'koneksi.php';

// Proses tambah/edit/hapus
if(isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    
    if(isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $query = "UPDATE produk SET nama='$nama', harga=$harga, stok=$stok, kategori_id=$kategori_id WHERE id=$id";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil diupdate!'); window.location='produk.php';</script>";
    } else {
        // Insert
        $query = "INSERT INTO produk (nama, harga, stok, kategori_id) VALUES ('$nama', $harga, $stok, $kategori_id)";
        mysqli_query($conn, $query);
        echo "<script>alert('Data berhasil ditambah!'); window.location='produk.php';</script>";
    }
}

// Proses hapus
if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM produk WHERE id=$id";
    mysqli_query($conn, $query);
    echo "<script>alert('Data berhasil dihapus!'); window.location='produk.php';</script>";
}

// Ambil data untuk edit
$edit_data = null;
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM produk WHERE id=$id";
    $result = mysqli_query($conn, $query);
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Kelola Produk</h2>
        
        <!-- Navigasi -->
        <nav class="nav nav-pills nav-justified mb-4">
            <a class="nav-link" href="index.php">Dashboard</a>
            <a class="nav-link" href="kategori.php">Kategori</a>
            <a class="nav-link active" href="produk.php">Produk</a>
            <a class="nav-link" href="customer.php">Customer</a>
            <a class="nav-link" href="order.php">Order</a>
        </nav>

        <div class="row">
            <!-- Form Tambah/Edit -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?= $edit_data ? 'Edit' : 'Tambah' ?> Produk</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if($edit_data): ?>
                                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" name="nama" 
                                       value="<?= $edit_data ? $edit_data['nama'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" name="harga" 
                                       value="<?= $edit_data ? $edit_data['harga'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stok" 
                                       value="<?= $edit_data ? $edit_data['stok'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                    $kategori_query = "SELECT * FROM kategori";
                                    $kategori_result = mysqli_query($conn, $kategori_query);
                                    while($kategori = mysqli_fetch_assoc($kategori_result)):
                                    ?>
                                        <option value="<?= $kategori['id'] ?>" 
                                            <?= ($edit_data && $edit_data['kategori_id'] == $kategori['id']) ? 'selected' : '' ?>>
                                            <?= $kategori['nama'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-primary">
                                <?= $edit_data ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if($edit_data): ?>
                                <a href="produk.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Daftar Produk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT p.*, k.nama as kategori_nama 
                                         FROM produk p 
                                         LEFT JOIN kategori k ON p.kategori_id = k.id 
                                         ORDER BY p.id DESC";
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['kategori_nama'] ?></td>
                                    <td>Rp <?= number_format($row['harga']) ?></td>
                                    <td><?= $row['stok'] ?></td>
                                    <td>
                                        <a href="produk.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="produk.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
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
