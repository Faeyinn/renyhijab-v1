<?php
// delete_transaction.php (create separate file)
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete transaction details first
        $conn->query("DELETE FROM Transaction_Detail WHERE id_inv = $id");
        
        // Delete transaction header
        $conn->query("DELETE FROM Transaction_Header WHERE id_inv = $id");
        
        $conn->commit();
        echo "<script>alert('Transaksi berhasil dihapus!'); window.location.href='transactions.php';</script>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: Gagal menghapus transaksi!'); window.location.href='transactions.php';</script>";
    }
}
?>