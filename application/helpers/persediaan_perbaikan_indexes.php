<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper untuk menambahkan index database yang diperlukan untuk mempercepat
 * proses Generate & Recalculate Persediaan.
 * 
 * Jalankan: $this->load->helper('persediaan_perbaikan_indexes');
 *           persediaan_perbaikan_tambah_indexes($this);
 */

function persediaan_perbaikan_tambah_indexes($CI)
{
    $indexes = array(
        // Tabel persediaan - index untuk filter tanggal_beli
        array(
            'table' => 'persediaan',
            'name' => 'idx_persediaan_tanggal_beli',
            'columns' => array('tanggal_beli'),
        ),
        // Tabel persediaan - index untuk pencarian uuid_barang
        array(
            'table' => 'persediaan',
            'name' => 'idx_persediaan_uuid_barang',
            'columns' => array('uuid_barang'),
        ),
        // Tabel persediaan - index untuk pencarian nama barang
        array(
            'table' => 'persediaan',
            'name' => 'idx_persediaan_namabarang',
            'columns' => array('namabarang(100)'),
        ),
        // Tabel persediaan - index untuk spop
        array(
            'table' => 'persediaan',
            'name' => 'idx_persediaan_spop',
            'columns' => array('spop'),
        ),
        // Tabel persediaan - composite index untuk query umum
        array(
            'table' => 'persediaan',
            'name' => 'idx_persediaan_tgl_beli_uuid',
            'columns' => array('tanggal_beli', 'uuid_barang'),
        ),
        // Tabel tbl_pembelian
        array(
            'table' => 'tbl_pembelian',
            'name' => 'idx_pembelian_uuid_barang',
            'columns' => array('uuid_barang'),
        ),
        array(
            'table' => 'tbl_pembelian',
            'name' => 'idx_pembelian_tgl_po',
            'columns' => array('tgl_po'),
        ),
        array(
            'table' => 'tbl_pembelian',
            'name' => 'idx_pembelian_spop',
            'columns' => array('spop'),
        ),
        // Tabel tbl_pembelian_jasa
        array(
            'table' => 'tbl_pembelian_jasa',
            'name' => 'idx_pembelian_jasa_uuid_barang',
            'columns' => array('uuid_barang'),
        ),
        array(
            'table' => 'tbl_pembelian_jasa',
            'name' => 'idx_pembelian_jasa_tgl_po',
            'columns' => array('tgl_po'),
        ),
        // Tabel tbl_penjualan
        array(
            'table' => 'tbl_penjualan',
            'name' => 'idx_penjualan_uuid_persediaan',
            'columns' => array('uuid_persediaan'),
        ),
        array(
            'table' => 'tbl_penjualan',
            'name' => 'idx_penjualan_tgl_jual',
            'columns' => array('tgl_jual'),
        ),
        array(
            'table' => 'tbl_penjualan',
            'name' => 'idx_penjualan_uuid_barang',
            'columns' => array('uuid_barang'),
        ),
        // Tabel sys_unit_produk
        array(
            'table' => 'sys_unit_produk',
            'name' => 'idx_unit_produk_tgl_transaksi',
            'columns' => array('tgl_transaksi'),
        ),
        // Tabel sys_unit_produk_bahan
        array(
            'table' => 'sys_unit_produk_bahan',
            'name' => 'idx_unit_produk_bahan_tgl',
            'columns' => array('tgl_transaksi'),
        ),
        // Tabel tbl_pembelian_pecah_satuan
        array(
            'table' => 'tbl_pembelian_pecah_satuan',
            'name' => 'idx_pecah_satuan_tgl',
            'columns' => array('tanggal'),
        ),
    );

    $added = 0;
    $errors = array();

    foreach ($indexes as $idx) {
        // Cek apakah index sudah ada
        $check = $CI->db->query("SHOW INDEX FROM `{$idx['table']}` WHERE Key_name = '{$idx['name']}'");
        if ($check->num_rows() > 0) {
            continue; // Index sudah ada
        }

        $cols = implode(', ', $idx['columns']);
        $sql = "ALTER TABLE `{$idx['table']}` ADD INDEX `{$idx['name']}` ({$cols})";
        
        if ($CI->db->query($sql)) {
            $added++;
        } else {
            $err = $CI->db->error();
            $errors[] = "Gagal menambah index {$idx['name']} di {$idx['table']}: " . ($err['message'] ?? 'unknown');
        }
    }

    return array(
        'added' => $added,
        'total' => count($indexes),
        'errors' => $errors,
    );
}