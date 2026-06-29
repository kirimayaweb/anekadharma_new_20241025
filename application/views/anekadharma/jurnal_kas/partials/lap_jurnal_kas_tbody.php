<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($not_published)) { ?>
    <tr class="lap-jk-empty-row">
        <td colspan="6" class="text-center text-muted py-4">
            Data belum tersedia — bulan ini belum dipublish ke Laporan Buku Kas.
        </td>
    </tr>
<?php } ?>
<?php foreach ((array) $report_rows as $row) {
    $tanggal = isset($row['tanggal']) ? (string) $row['tanggal'] : '';
    $tanggal_order = '';
    if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $tanggal, $m_tgl)) {
        $tanggal_order = $m_tgl[3] . $m_tgl[2] . $m_tgl[1];
    }
    $debet_raw = ($row['debet'] !== null && $row['debet'] !== '') ? (float) $row['debet'] : 0;
    $kredit_raw = ($row['kredit'] !== null && $row['kredit'] !== '') ? (float) $row['kredit'] : 0;
    $row_type = isset($row['type']) ? (string) $row['type'] : 'data';
?>
    <tr data-row-type="<?php echo htmlspecialchars($row_type, ENT_QUOTES, 'UTF-8'); ?>" data-debet="<?php echo $debet_raw; ?>" data-kredit="<?php echo $kredit_raw; ?>">
        <td data-order="<?php echo isset($row['no']) ? (int) $row['no'] : 0; ?>"><?php echo isset($row['no']) ? (int) $row['no'] : ''; ?></td>
        <td data-order="<?php echo htmlspecialchars($tanggal_order, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($tanggal, ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars(isset($row['bukti']) ? $row['bukti'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php echo htmlspecialchars(isset($row['keterangan']) ? $row['keterangan'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="text-right" data-order="<?php echo $debet_raw; ?>">
            <?php
            if ($row['debet'] !== null && $row['debet'] !== '') {
                echo number_format((float) $row['debet'], 2, ',', '.');
            }
            ?>
        </td>
        <td class="text-right" data-order="<?php echo $kredit_raw; ?>">
            <?php
            if ($row['kredit'] !== null && $row['kredit'] !== '') {
                echo number_format((float) $row['kredit'], 2, ',', '.');
            }
            ?>
        </td>
    </tr>
<?php } ?>
