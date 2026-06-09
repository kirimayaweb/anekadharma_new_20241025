<?php
$Data_stock = isset($Data_stock) && is_array($Data_stock) ? $Data_stock : array();
$bulan_produksi_ym = isset($bulan_produksi_ym) && $bulan_produksi_ym !== ''
    ? $bulan_produksi_ym
    : date('Y-m');
?>
<div class="card-body p-0 px-1 pb-0">
    <table id="example" class="table table-bordered table-striped table-sm display nowrap w-100 tbl-stock-persediaan-produksi">
        <thead>
            <tr>
                <th class="text-center col-no">No</th>
                <th class="text-center col-tgl-beli">Tanggal Beli</th>
                <th class="text-center col-bulan">Bulan</th>
                <th class="text-left col-spop">SPOP</th>
                <th class="text-left col-nama">Nama Barang</th>
                <th class="text-right col-harga">Harga Satuan</th>
                <th class="text-center col-satuan">Satuan</th>
                <th class="text-center col-stock">Stock</th>
                <th class="text-center col-aksi">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start = 0;
            foreach ($Data_stock as $list_data) {
                if (empty($list_data->uuid_barang)) {
                    continue;
                }
                $bulan_barang_ym = '';
                if (!empty($list_data->tanggal_beli)) {
                    $ts_beli = strtotime($list_data->tanggal_beli);
                    if ($ts_beli) {
                        $bulan_barang_ym = date('Y-m', $ts_beli);
                    }
                }
                if ($bulan_barang_ym === '' || $bulan_barang_ym !== $bulan_produksi_ym) {
                    continue;
                }
                $stock_total_10 = (int) preg_replace('/[^0-9]/', '', (string) $list_data->jumlah_sediaan);
                if ($stock_total_10 <= 0) {
                    continue;
                }
            ?>
                <tr class="row-persediaan-sesuai-bulan" data-bulan-ym="<?php echo htmlspecialchars($bulan_barang_ym, ENT_QUOTES, 'UTF-8'); ?>">
                    <td class="text-center"><?php echo ++$start; ?></td>
                    <td class="text-center"><?php echo date('d-M-Y', strtotime($list_data->tanggal_beli)); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars(date('m/Y', strtotime($list_data->tanggal_beli)), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($list_data->spop, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-left"><?php echo htmlspecialchars($list_data->nama_barang_beli, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-right"><?php echo number_format((float) $list_data->harga_satuan_persediaan, 2, ',', '.'); ?></td>
                    <td class="text-center"><?php echo htmlspecialchars($list_data->satuan_persediaan, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td class="text-center"><?php echo number_format($stock_total_10, 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <button type="button"
                            class="btn btn-success btn-xs btn-pilih-barang-bahan"
                            data-id-persediaan="<?php echo (int) $list_data->id; ?>"
                            data-uuid-persediaan="<?php echo htmlspecialchars($list_data->uuid_persediaan, ENT_QUOTES, 'UTF-8'); ?>"
                            data-nama-barang="<?php echo htmlspecialchars($list_data->nama_barang_beli, ENT_QUOTES, 'UTF-8'); ?>"
                            data-harga-satuan="<?php echo htmlspecialchars(number_format((float) $list_data->harga_satuan_persediaan, 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?>"
                            data-sisa-stock="<?php echo $stock_total_10; ?>"
                            data-tanggal-beli="<?php echo htmlspecialchars(date('d-M-Y', strtotime($list_data->tanggal_beli)), ENT_QUOTES, 'UTF-8'); ?>">
                            PILIH BARANG
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
