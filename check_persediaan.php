<?php
/**
 * Script untuk diagnosa dan fix persediaan sync issues
 */

// Load CodeIgniter
$_SERVER['argv'] = array('', 'tbl_pembelian');
require_once 'index.php';

// Get nama barang yang mau di-check
$nama_barang = 'Piagam Bupati BTL';

echo "==== DIAGNOSA PERSEDIAAN SYNC ====\n\n";

// 1. Check data di tbl_pembelian
echo "1. Data di tbl_pembelian (nama_barang = '$nama_barang'):\n";
$query_pembelian = "SELECT id, uraian, jumlah, id_persediaan_barang, uuid_persediaan FROM tbl_pembelian WHERE uraian = '$nama_barang' ORDER BY id DESC LIMIT 1";
$result = $CI->db->query($query_pembelian);
$row_pembelian = $result->row();

if ($row_pembelian) {
    echo "   ID Pembelian: " . $row_pembelian->id . "\n";
    echo "   Jumlah: " . $row_pembelian->jumlah . "\n";
    echo "   ID Persediaan: " . $row_pembelian->id_persediaan_barang . "\n";
    echo "   UUID Persediaan: " . $row_pembelian->uuid_persediaan . "\n\n";
    
    // 2. Check data di persediaan
    echo "2. Data di persediaan (id = " . $row_pembelian->id_persediaan_barang . "):\n";
    $query_persediaan = "SELECT id, namabarang, sa, beli, total_10, nilai_persediaan FROM persediaan WHERE id = " . $row_pembelian->id_persediaan_barang;
    $result_persediaan = $CI->db->query($query_persediaan);
    $row_persediaan = $result_persediaan->row();
    
    if ($row_persediaan) {
        echo "   ID: " . $row_persediaan->id . "\n";
        echo "   Nama Barang: " . $row_persediaan->namabarang . "\n";
        echo "   SA (Stok Awal): " . $row_persediaan->sa . "\n";
        echo "   Beli: " . $row_persediaan->beli . "\n";
        echo "   Total 10: " . $row_persediaan->total_10 . "\n";
        echo "   Nilai Persediaan: " . $row_persediaan->nilai_persediaan . "\n\n";
        
        // 3. Check apakah ada mismatch
        if ($row_pembelian->jumlah != $row_persediaan->sa) {
            echo "3. ⚠️  MISMATCH DETECTED!\n";
            echo "   tbl_pembelian.jumlah = " . $row_pembelian->jumlah . "\n";
            echo "   persediaan.sa = " . $row_persediaan->sa . "\n";
            echo "   DIFF = " . ($row_pembelian->jumlah - $row_persediaan->sa) . "\n\n";
            
            // FIX: Update persediaan
            echo "4. FIXING: Updating persediaan to match tbl_pembelian...\n";
            $update_sql = "UPDATE persediaan SET sa = " . $row_pembelian->jumlah . ", total_10 = " . $row_pembelian->jumlah . " WHERE id = " . $row_pembelian->id_persediaan_barang;
            $CI->db->query($update_sql);
            echo "   ✓ Updated!\n";
        } else {
            echo "3. ✓ Data sudah sinkron dengan baik\n";
        }
    } else {
        echo "   ⚠️  Persediaan record NOT FOUND!\n";
    }
} else {
    echo "   ⚠️  Pembelian record NOT FOUND!\n";
}
?>
