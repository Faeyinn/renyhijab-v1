<?php
include 'db_connection.php';

$message = '';

// Get customers and products for dropdowns
$customers = $conn->query("SELECT * FROM Customer ORDER BY customer_name");
$products = $conn->query("SELECT * FROM Product ORDER BY product_name");

if ($_POST) {
    $id_cust = $_POST['id_cust'];
    $date_inv = $_POST['date_inv'];
    $selected_products = $_POST['products'] ?? [];
    $quantities = $_POST['quantities'] ?? [];
    
    // Get next invoice ID
    $result = $conn->query("SELECT MAX(id_inv) as max_id FROM Transaction_Header");
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] + 1;
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert transaction header
        $stmt = $conn->prepare("INSERT INTO Transaction_Header (id_inv, date_inv, id_cust) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $next_id, $date_inv, $id_cust);
        $stmt->execute();
        
        // Insert transaction details
        foreach ($selected_products as $index => $product_id) {
            if (!empty($quantities[$index]) && $quantities[$index] > 0) {
                $stmt_detail = $conn->prepare("INSERT INTO Transaction_Detail (id_inv, id_product, qty) VALUES (?, ?, ?)");
                $stmt_detail->bind_param("iii", $next_id, $product_id, $quantities[$index]);
                $stmt_detail->execute();
            }
        }
        
        $conn->commit();
        $message = "Transaksi berhasil ditambahkan!";
        
    } catch (Exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Renyhijab</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Tambah Transaksi - Renyhijab</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="products.php">Produk</a>
            <a href="transactions.php">Transaksi</a>
            <a href="customers.php">Customer</a>
        </nav>
    </header>
    <main>
        <h2>Tambah Transaksi Baru</h2>
        
        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="id_cust">Customer:</label>
                    <select id="id_cust" name="id_cust" required>
                        <option value="">Pilih Customer</option>
                        <?php while ($customer = $customers->fetch_assoc()): ?>
                            <option value="<?php echo $customer['id_cust']; ?>">
                                <?php echo $customer['customer_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_inv">Tanggal:</label>
                    <input type="date" id="date_inv" name="date_inv" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <h3>Produk</h3>
                <div id="product-list">
                    <?php 
                    $products->data_seek(0); // Reset pointer
                    $index = 0;
                    while ($product = $products->fetch_assoc()): 
                    ?>
                        <div class="product-item" style="display: flex; align-items: center; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <input type="checkbox" name="products[]" value="<?php echo $product['id_product']; ?>" 
                                   onchange="toggleQuantity(this, <?php echo $index; ?>)" style="margin-right: 10px;">
                            <span style="flex: 1;"><?php echo $product['product_name']; ?> 
                                  (Rp <?php echo number_format($product['cost'], 0, ',', '.'); ?> - Stok: <?php echo $product['stok']; ?>)</span>
                            <input type="number" name="quantities[]" id="qty_<?php echo $index; ?>" min="1" 
                                   max="<?php echo $product['stok']; ?>" placeholder="Qty" disabled 
                                   style="width: 80px; margin-left: 10px;">
                        </div>
                    <?php 
                    $index++;
                    endwhile; 
                    ?>
                </div>
                
                <button type="submit" class="btn">Tambah Transaksi</button>
                <a href="transactions.php" class="btn" style="background-color: #6c757d;">Kembali</a>
            </form>
        </div>
    </main>
    
    <script>
        function toggleQuantity(checkbox, index) {
            const qtyInput = document.getElementById('qty_' + index);
            if (checkbox.checked) {
                qtyInput.disabled = false;
                qtyInput.required = true;
            } else {
                qtyInput.disabled = true;
                qtyInput.required = false;
                qtyInput.value = '';
            }
        }
    </script>
</body>
</html>