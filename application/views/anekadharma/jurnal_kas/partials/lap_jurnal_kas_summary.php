<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
$Get_month_from_date = (int) $month_selected;
?>
<?php if (!empty($is_published)) { ?>
<div class="lap-jk-summary-scroll" id="lap-jk-summary-scroll">
    <div class="jurnal-kas-summary-wrap lap-jk-summary-wrap">
        <table class="table table-bordered jurnal-kas-grid-table jurnal-kas-summary-table lap-jk-summary-table mb-0">
            <colgroup>
                <col class="lap-jk-w-no">
                <col class="lap-jk-w-tanggal">
                <col class="lap-jk-w-bukti">
                <col class="lap-jk-w-keterangan">
                <col class="lap-jk-w-debet">
                <col class="lap-jk-w-kredit">
            </colgroup>
            <tbody>
                <tr class="jk-summary-row">
                    <td colspan="4" class="jk-summary-label text-center font-weight-bold">JUMLAH DEBET / KREDIT</td>
                    <td class="text-right font-weight-bold" id="lap-jk-summary-total-debet"><?php echo number_format((float) $TOTAL_debet, 2, ',', '.'); ?></td>
                    <td class="text-right font-weight-bold" id="lap-jk-summary-total-kredit"><?php echo number_format((float) $TOTAL_kredit, 2, ',', '.'); ?></td>
                </tr>
                <tr class="jk-summary-row jk-summary-row-saldo">
                    <td colspan="4" class="jk-summary-label text-center font-weight-bold">Saldo akhir Kas Bulan <?php echo jurnal_kas_bulan_teks($Get_month_from_date); ?></td>
                    <td class="text-right font-weight-bold"></td>
                    <td class="text-right font-weight-bold" id="lap-jk-summary-saldo-akhir"><?php echo number_format((float) $SALDO_AKHIR, 2, ',', '.'); ?></td>
                </tr>
                <tr class="jk-summary-row jk-summary-row-last">
                    <td colspan="4" class="jk-summary-label text-center font-weight-bold">JUMLAH SEIMBANG</td>
                    <td class="text-right font-weight-bold" id="lap-jk-summary-seimbang-debet">
                        <?php
                        if ($SALDO_AKHIR >= 0) {
                            echo number_format((float) $TOTAL_debet, 2, ',', '.');
                        } else {
                            echo number_format((float) $SALDO_AKHIR, 2, ',', '.');
                        }
                        ?>
                    </td>
                    <td class="text-right font-weight-bold" id="lap-jk-summary-seimbang-kredit">
                        <?php
                        if ($SALDO_AKHIR >= 0) {
                            echo number_format((float) $TOTAL_debet, 2, ',', '.');
                        } else {
                            echo number_format((float) $SALDO_AKHIR, 2, ',', '.');
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>
