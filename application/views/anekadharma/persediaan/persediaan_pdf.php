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
        <strong><?php echo htmlspecialchars($bulan_persediaan_selected !== '' ? $bulan_persediaan_selected : date('Y-m')); ?></strong>
    </div>

    <table>
        <thead>
            <tr>
                <?php foreach (persediaan_export_headers() as $header) : ?>
                    <th class="<?php echo in_array($header, array('Hpp', 'Sa', 'Beli', 'Tuj', 'Sekret', 'Cetak', 'Grafikita', 'Dinas Umum', 'Atk Rsud', 'Ppbmp Kbs', 'Kbs', 'Ppbmp', 'Medis', 'Siiplah Bosda', 'Sembako', 'Fc Gose', 'Fc Manding', 'Fc Psamya', 'Total 10', 'Nilai Persediaan', 'Terjual', 'Jumlah Pecah Satuan', 'Bahan Produksi', 'Sisa / Stock'), true) ? 'text-right' : ''; ?>">
                        <?php echo htmlspecialchars($header); ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $start = 0;
            $total_nilai_persediaan = 0;
            $bulan_tampil = isset($bulan_persediaan_selected) && $bulan_persediaan_selected !== ''
                ? $bulan_persediaan_selected
                : date('Y-m');
            ?>
            <?php if (!empty($persediaan_data)) : ?>
                <?php foreach ($persediaan_data as $row) : ?>
                    <?php
                    $total_nilai_persediaan += persediaan_parse_angka(isset($row->nilai_persediaan) ? $row->nilai_persediaan : 0);
                    $cells = persediaan_export_row_cells($row, ++$start, $bulan_tampil);
                    ?>
                    <tr>
                        <?php foreach ($cells as $idx => $cell) : ?>
                            <td class="<?php echo $idx === 0 ? 'text-center' : ''; ?>">
                                <?php
                                if ($idx === 30 && is_numeric($cell) && floor((float) $cell) == (float) $cell) {
                                    echo (int) $cell;
                                } else {
                                    echo htmlspecialchars((string) $cell);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
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
