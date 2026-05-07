<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Persediaan</title>
    <style>
        @page { size: A3 landscape; margin: 8px; }
        body { font-family: sans-serif; font-size: 7px; margin: 0; }
        h3 { margin: 0 0 6px 0; }
        .meta { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 2px 2px; word-wrap: break-word; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Data Persediaan</h3>
    <div class="meta">
        Filter Bulan:
        <strong><?php echo ($bulan_persediaan_selected !== '' ? $bulan_persediaan_selected : 'Semua'); ?></strong>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Namabarang</th>
                <th>Satuan</th>
                <th class="text-right">Hpp</th>
                <th class="text-right">Sa</th>
                <th>Spop</th>
                <th class="text-right">Beli</th>
                <th class="text-right">Tuj</th>
                <th>Tgl Keluar</th>
                <th class="text-right">Sekret</th>
                <th class="text-right">Cetak</th>
                <th class="text-right">Grafikita</th>
                <th class="text-right">Dinas Umum</th>
                <th class="text-right">Atk Rsud</th>
                <th class="text-right">Ppbmp Kbs</th>
                <th class="text-right">Kbs</th>
                <th class="text-right">Ppbmp</th>
                <th class="text-right">Medis</th>
                <th class="text-right">Siiplah Bosda</th>
                <th class="text-right">Sembako</th>
                <th class="text-right">Fc Gose</th>
                <th class="text-right">Fc Manding</th>
                <th class="text-right">Fc Psamya</th>
                <th class="text-right">Total 10</th>
                <th class="text-right">Nilai Persediaan</th>
                <th class="text-right">Terjual</th>
                <th class="text-right">Jumlah Pecah Satuan</th>
                <th class="text-right">Bahan Produksi</th>
                <th class="text-right">Sisa / Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php $total_nilai_persediaan = 0; ?>
            <?php if (!empty($persediaan_data)) : ?>
                <?php foreach ($persediaan_data as $row) : ?>
                    <?php
                    $penjualan = isset($row->penjualan) ? (float) $row->penjualan : 0;
                    $pecah = isset($row->pecah_satuan) ? (float) $row->pecah_satuan : 0;
                    $bahan = isset($row->bahan_produksi) ? (float) $row->bahan_produksi : 0;
                    $total10 = isset($row->total_10) ? (float) $row->total_10 : 0;
                    $sisa = $total10 - ($penjualan + $pecah + $bahan);
                    $nilai_persediaan_row = isset($row->nilai_persediaan) ? (float) str_replace(',', '', $row->nilai_persediaan) : 0;
                    $total_nilai_persediaan += $nilai_persediaan_row;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo ++$start; ?></td>
                        <td><?php echo $row->tanggal; ?></td>
                        <td><?php echo $row->kode; ?></td>
                        <td><?php echo $row->namabarang; ?></td>
                        <td><?php echo $row->satuan; ?></td>
                        <td class="text-right"><?php echo $row->hpp; ?></td>
                        <td class="text-right"><?php echo $row->sa; ?></td>
                        <td><?php echo $row->spop; ?></td>
                        <td class="text-right"><?php echo $row->beli; ?></td>
                        <td class="text-right"><?php echo $row->tuj; ?></td>
                        <td><?php echo $row->tgl_keluar; ?></td>
                        <td class="text-right"><?php echo $row->sekret; ?></td>
                        <td class="text-right"><?php echo $row->cetak; ?></td>
                        <td class="text-right"><?php echo $row->grafikita; ?></td>
                        <td class="text-right"><?php echo $row->dinas_umum; ?></td>
                        <td class="text-right"><?php echo $row->atk_rsud; ?></td>
                        <td class="text-right"><?php echo $row->ppbmp_kbs; ?></td>
                        <td class="text-right"><?php echo $row->kbs; ?></td>
                        <td class="text-right"><?php echo $row->ppbmp; ?></td>
                        <td class="text-right"><?php echo $row->medis; ?></td>
                        <td class="text-right"><?php echo $row->siiplah_bosda; ?></td>
                        <td class="text-right"><?php echo $row->sembako; ?></td>
                        <td class="text-right"><?php echo $row->fc_gose; ?></td>
                        <td class="text-right"><?php echo $row->fc_manding; ?></td>
                        <td class="text-right"><?php echo $row->fc_psamya; ?></td>
                        <td class="text-right"><?php echo $row->total_10; ?></td>
                        <td class="text-right"><?php echo $row->nilai_persediaan; ?></td>
                        <td class="text-right"><?php echo isset($row->penjualan) ? $row->penjualan : 0; ?></td>
                        <td class="text-right"><?php echo isset($row->pecah_satuan) ? $row->pecah_satuan : 0; ?></td>
                        <td class="text-right"><?php echo isset($row->bahan_produksi) ? $row->bahan_produksi : 0; ?></td>
                        <td class="text-right"><?php echo $sisa; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="31" class="text-center">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="26" class="text-right">Total Nilai Persediaan</th>
                <th class="text-right"><?php echo number_format($total_nilai_persediaan, 0, ',', '.'); ?></th>
                <th colspan="4"></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
