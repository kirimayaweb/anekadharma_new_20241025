<?php
if (empty($rekap_row) || empty($rekap_row->id)) {
    echo '<td></td>';
    return;
}

$action_ubah = isset($action_ubah_per_id) ? $action_ubah_per_id : site_url('tbl_penjualan/create_action_nmrkirim_update_per_id_penjualan/');
$uuid_penjualan = isset($rekap_row->uuid_penjualan) ? trim((string) $rekap_row->uuid_penjualan) : '';
$delete_qs = '';
if (!empty($filter_query_string)) {
    $delete_qs = $filter_query_string;
    if (strpos($delete_qs, '?') === false) {
        $delete_qs = '?' . ltrim($delete_qs, '?&');
    }
    $delete_qs .= '&field_rekap=' . rawurlencode(isset($field_rekap) ? $field_rekap : 'unit');
} elseif (!empty($tgl_awal_param) && !empty($tgl_akhir_param)) {
    $delete_qs = '?tgl_awal=' . rawurlencode($tgl_awal_param) . '&tgl_akhir=' . rawurlencode($tgl_akhir_param);
    $delete_qs .= '&field_rekap=' . rawurlencode(isset($field_rekap) ? $field_rekap : 'unit');
} else {
    $delete_qs = '?field_rekap=' . rawurlencode(isset($field_rekap) ? $field_rekap : 'unit');
}
$delete_url = site_url('tbl_penjualan/delete/' . (int) $rekap_row->id . '/' . $uuid_penjualan . '/rekap') . $delete_qs;
?>
<td class="rekap-col-aksi" nowrap>
    <?php
    echo anchor(
        $delete_url,
        'Hapus',
        'class="btn btn-danger btn-xs" onclick="javascript: return confirm(\'Anda Yakin akan Menghapus Penjualan Barang ini ?\')"'
    );
    ?>
    <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal-rekap-ubah-barang_<?php echo (int) $rekap_row->id; ?>">
        UBAH
    </button>
</td>
