<?php
$this->load->helper('neraca_saldo_list');

if (!isset($Get_month_from_date) || $Get_month_from_date === '' || $Get_month_from_date === null) {
    $Get_month_from_date = isset($month_selected) ? (int) $month_selected : (int) date('m');
}
if (!isset($Get_year_Tahun_ini) || $Get_year_Tahun_ini === '' || $Get_year_Tahun_ini === null) {
    $Get_year_Tahun_ini = isset($year_selected) ? (int) $year_selected : (int) date('Y');
}
$Get_month_from_date = (int) $Get_month_from_date;
$Get_year_Tahun_ini = (int) $Get_year_Tahun_ini;
if ($Get_month_from_date < 1 || $Get_month_from_date > 12) {
    $Get_month_from_date = (int) date('m');
}
if ($Get_year_Tahun_ini < 2000) {
    $Get_year_Tahun_ini = (int) date('Y');
}

$ns_data = neraca_saldo_compute_list_data(get_instance(), $Get_month_from_date, $Get_year_Tahun_ini);
foreach ((array) $ns_data['rows'] as $item) {
    $Get_Kode_akun = (int) $item['kode_akun'];
    $saldo_debet_label = neraca_saldo_format_rupiah($item['saldo_debet'], true);
    $saldo_kredit_label = neraca_saldo_format_rupiah($item['saldo_kredit'], true);
    $btn_saldo_debet_class = !empty($item['has_debet_tahun_lalu']) ? 'btn btn-success btn-sm' : 'btn btn-secondary btn-sm';
    $btn_saldo_kredit_class = !empty($item['has_kredit_tahun_lalu']) ? 'btn btn-success btn-sm' : 'btn btn-secondary btn-sm';
?>
    <tr>
        <td align="left"><?php echo (int) $item['no']; ?></td>
        <td align="left"><?php echo htmlspecialchars((string) $item['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td align="left"><?php echo $Get_Kode_akun; ?></td>
        <td align="left"><?php echo htmlspecialchars((string) $item['nama_akun'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td align="right" data-order="<?php echo (float) $item['saldo_debet']; ?>">
            <span class="<?php echo $btn_saldo_debet_class; ?>"><?php echo htmlspecialchars($saldo_debet_label, ENT_QUOTES, 'UTF-8'); ?></span>
        </td>
        <td align="right" data-order="<?php echo (float) $item['saldo_kredit']; ?>">
            <span class="<?php echo $btn_saldo_kredit_class; ?>"><?php echo htmlspecialchars($saldo_kredit_label, ENT_QUOTES, 'UTF-8'); ?></span>
        </td>
        <td align="right" data-order="<?php echo (float) $item['bb_debet']; ?>"><?php echo neraca_saldo_format_rupiah($item['bb_debet'], true); ?></td>
        <td align="right" data-order="<?php echo (float) $item['bb_kredit']; ?>"><?php echo neraca_saldo_format_rupiah($item['bb_kredit'], true); ?></td>
        <td align="right" data-order="<?php echo (float) $item['ns_debet_raw']; ?>"><?php echo neraca_saldo_format_rupiah($item['ns_debet_raw'], true); ?></td>
        <td align="right" data-order="<?php echo (float) $item['ns_kredit_raw']; ?>"><?php echo neraca_saldo_format_rupiah($item['ns_kredit_raw'], true); ?></td>
        <td align="right" data-order="<?php echo (float) $item['ns_debet_raw']; ?>"><?php echo neraca_saldo_format_rupiah($item['ns_debet_raw'], true); ?></td>
        <td align="right" data-order="<?php echo (float) $item['ns_kredit_raw']; ?>"><?php echo neraca_saldo_format_rupiah($item['ns_kredit_raw'], true); ?></td>
    </tr>
<?php
}
?>
