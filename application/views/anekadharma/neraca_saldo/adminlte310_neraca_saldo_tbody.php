<?php
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
$Get_month_url = sprintf('%02d', $Get_month_from_date);

$start = 0;
foreach ($Data_Kode_Akun as $list_data) {
    $Get_Kode_akun = $list_data->kode_akun;

    $sql = "SELECT sum(`debet`) as debet, sum(`kredit`) as kredit FROM `buku_besar` WHERE MONTH(`tanggal`)=" . $Get_month_from_date . " AND YEAR(`tanggal`)=" . $Get_year_Tahun_ini . " AND `kode_akun`=" . (int) $Get_Kode_akun . " group by `kode_akun` ";
    $Buku_besar_DATA = $this->db->query($sql);

    $sql = "SELECT sum(`debet`) as debet, sum(`kredit`) as kredit FROM `jurnal_penyesuaian` WHERE MONTH(`tanggal`)=" . $Get_month_from_date . " AND YEAR(`tanggal`)=" . $Get_year_Tahun_ini . " AND `kode_akun`=" . (int) $Get_Kode_akun . " group by `kode_akun` ";
    $Jurnal_penyesuaian_DATA = $this->db->query($sql);

    $sql = "SELECT * FROM `neraca_saldo` WHERE MONTH(`tanggal`)=" . $Get_month_from_date . " AND YEAR(`tanggal`)=" . $Get_year_Tahun_ini . " AND `kode_akun`=" . (int) $Get_Kode_akun;
    $Neraca_Saldo_DATA = $this->db->query($sql);
    $ns_row = ($Neraca_Saldo_DATA->num_rows() > 0) ? $Neraca_Saldo_DATA->row() : null;

    $bb_debet = ($Buku_besar_DATA->num_rows() > 0) ? (float) $Buku_besar_DATA->row()->debet : 0;
    $bb_kredit = ($Buku_besar_DATA->num_rows() > 0) ? (float) $Buku_besar_DATA->row()->kredit : 0;
    $jp_debet = ($Jurnal_penyesuaian_DATA->num_rows() > 0) ? (float) $Jurnal_penyesuaian_DATA->row()->debet : 0;
    $jp_kredit = ($Jurnal_penyesuaian_DATA->num_rows() > 0) ? (float) $Jurnal_penyesuaian_DATA->row()->kredit : 0;
    $has_activity = ($Buku_besar_DATA->num_rows() > 0 || $Jurnal_penyesuaian_DATA->num_rows() > 0);
?>
    <tr>
        <td align="left"><?php echo ++$start; ?></td>
        <td align="left"><?php echo $list_data->tanggal; ?></td>
        <td align="left"><?php echo $Get_Kode_akun; ?></td>
        <td align="left"><?php echo $list_data->nama_akun; ?></td>
        <td align="right">
            <?php
            if (!$ns_row || is_null($ns_row->debet_akhir_tahun_lalu)) {
                echo anchor(site_url('Neraca_saldo/input_neraca_saldo_waktu_lalu/' . $Get_Kode_akun . '/debet/' . $Get_year_Tahun_ini . '/' . $Get_month_url), '<i class="fa fa-pencil-square-o">Input Debet</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
            } else {
                echo anchor(site_url('Neraca_saldo/input_neraca_saldo_waktu_lalu/' . $Get_Kode_akun . '/debet/' . $Get_year_Tahun_ini . '/' . $Get_month_url), '<i class="fa fa-pencil-square-o">' . $ns_row->debet_akhir_tahun_lalu . '</i>', array('title' => 'edit', 'class' => 'btn btn-success btn-sm'));
            }
            ?>
        </td>
        <td align="right">
            <?php
            if (!$ns_row || is_null($ns_row->kredit_akhir_tahun_lalu)) {
                echo anchor(site_url('Neraca_saldo/input_neraca_saldo_waktu_lalu/' . $Get_Kode_akun . '/kredit/' . $Get_year_Tahun_ini . '/' . $Get_month_url), '<i class="fa fa-pencil-square-o">Input Kredit</i>', array('title' => 'edit', 'class' => 'btn btn-danger btn-sm'));
            } else {
                echo anchor(site_url('Neraca_saldo/input_neraca_saldo_waktu_lalu/' . $Get_Kode_akun . '/kredit/' . $Get_year_Tahun_ini . '/' . $Get_month_url), '<i class="fa fa-pencil-square-o">' . $ns_row->kredit_akhir_tahun_lalu . '</i>', array('title' => 'edit', 'class' => 'btn btn-success btn-sm'));
            }
            ?>
        </td>
        <td align="right"><?php echo $bb_debet ? $bb_debet : '0'; ?></td>
        <td align="right"><?php echo $bb_kredit ? $bb_kredit : '0'; ?></td>
        <td align="right"><?php echo $has_activity ? ($bb_debet + $jp_debet) : '0'; ?></td>
        <td align="right"><?php echo $has_activity ? ($bb_kredit + $jp_kredit) : '0'; ?></td>
        <td align="right"><?php echo $has_activity ? ($bb_debet + $jp_debet) : '0'; ?></td>
        <td align="right"><?php echo $has_activity ? ($bb_kredit + $jp_kredit) : '0'; ?></td>
    </tr>
<?php
}
?>
