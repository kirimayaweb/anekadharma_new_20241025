<?php
$Data_stock = isset($Data_stock) && is_array($Data_stock) ? $Data_stock : array();
$bulan_produksi_ym = isset($bulan_produksi_ym) && $bulan_produksi_ym !== ''
    ? $bulan_produksi_ym
    : date('Y-m');
?>
<div class="card-body p-0 px-1 pb-0">
    <table id="example" class="table table-bordered table-striped table-sm display nowrap" style="width:1100px; min-width:1100px;">
        <thead>
            <tr>
                <th style="text-align:center" width="40px">No</th>
                <th style="text-align:center" width="110px">Tanggal Beli</th>
                <th style="text-align:center" width="70px">Bulan</th>
                <th style="text-align:center" width="120px">SPOP</th>
                <th style="text-align:left" width="220px">Nama barang</th>
                <th style="text-align:right" width="100px">Harga satuan</th>
                <th style="text-align:center" width="70px">Satuan</th>
                <th style="text-align:center" width="90px">Sisa Stock</th>
                <th style="text-align:center" width="120px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start = 0;
            foreach ($Data_stock as $list_data) {
                if (!$list_data->uuid_barang) {
                    continue;
                }
                $bulan_barang_ym = '';
                if (!empty($list_data->tanggal_beli)) {
                    $ts_beli = strtotime($list_data->tanggal_beli);
                    if ($ts_beli) {
                        $bulan_barang_ym = date('Y-m', $ts_beli);
                    }
                }
                $sesuai_bulan = ($bulan_barang_ym !== '' && $bulan_barang_ym === $bulan_produksi_ym);
                $sisa_stock_data = $list_data->jumlah_sediaan - ($list_data->penjualan + $list_data->pecah_satuan_persediaan + $list_data->bahan_produksi);
                if ($sisa_stock_data <= 0 || !$sesuai_bulan) {
                    continue;
                }
            ?>
                <tr class="row-persediaan-sesuai-bulan" data-bulan-ym="<?php echo htmlspecialchars($bulan_barang_ym, ENT_QUOTES, 'UTF-8'); ?>">
                    <td align="center"><?php echo ++$start ?></td>
                    <td align="center"><?php echo date('d-M-Y', strtotime($list_data->tanggal_beli)); ?></td>
                    <td align="center"><?php echo htmlspecialchars(date('m/Y', strtotime($list_data->tanggal_beli)), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td align="left"><?php echo $list_data->spop; ?></td>
                    <td align="left"><?php echo $list_data->nama_barang_beli; ?></td>
                    <td align="right"><?php echo number_format($list_data->harga_satuan_persediaan, 2, ',', '.'); ?></td>
                    <td align="center"><?php echo $list_data->satuan_persediaan; ?></td>
                    <td align="center"><?php echo nominal($sisa_stock_data); ?></td>
                    <td align="center">
                        <button type="button"
                            class="btn btn-success btn-xs btn-pilih-barang-bahan"
                            data-id-persediaan="<?php echo (int) $list_data->id; ?>"
                            data-uuid-persediaan="<?php echo htmlspecialchars($list_data->uuid_persediaan, ENT_QUOTES, 'UTF-8'); ?>"
                            data-nama-barang="<?php echo htmlspecialchars($list_data->nama_barang_beli, ENT_QUOTES, 'UTF-8'); ?>"
                            data-harga-satuan="<?php echo htmlspecialchars(number_format($list_data->harga_satuan_persediaan, 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?>"
                            data-sisa-stock="<?php echo (int) $sisa_stock_data; ?>"
                            data-tanggal-beli="<?php echo htmlspecialchars(date('d-M-Y', strtotime($list_data->tanggal_beli)), ENT_QUOTES, 'UTF-8'); ?>">
                            PILIH BARANG
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
