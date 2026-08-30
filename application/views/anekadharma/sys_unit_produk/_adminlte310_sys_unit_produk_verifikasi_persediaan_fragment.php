<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!isset($rows_bahan_belum) || !is_array($rows_bahan_belum)) {
    $rows_bahan_belum = isset($rows_bahan_tidak_ada) && is_array($rows_bahan_tidak_ada) ? $rows_bahan_tidak_ada : array();
}
if (!isset($rows_bahan_manual) || !is_array($rows_bahan_manual)) {
    $rows_bahan_manual = array();
}
if (!isset($rows_bahan_otomatis) || !is_array($rows_bahan_otomatis)) {
    $rows_bahan_otomatis = array();
}

$count_belum = isset($count_bahan_belum) ? (int) $count_bahan_belum : count($rows_bahan_belum);
$count_manual = isset($count_bahan_manual) ? (int) $count_bahan_manual : count($rows_bahan_manual);
$count_otomatis = isset($count_bahan_otomatis) ? (int) $count_bahan_otomatis : count($rows_bahan_otomatis);
$bulan_label = isset($bulan_label) ? (string) $bulan_label : '';
$rekap = isset($rekap) && is_array($rekap) ? $rekap : array();
$message = isset($message) ? trim((string) $message) : '';

if (!function_exists('persediaan_gen_proses_pembelian_format_tgl')) {
    $CI = function_exists('get_instance') ? get_instance() : null;
    if ($CI) {
        $CI->load->helper('persediaan_display');
    }
}

$sections = array(
    array(
        'key' => 'belum',
        'tab_id' => 'subtab-produksi-persediaan-belum',
        'link_id' => 'subtab-produksi-persediaan-belum-link',
        'title' => 'Belum Terverifikasi',
        'badge_class' => 'badge-warning',
        'table_id' => 'table-produksi-verifikasi-belum',
        'data' => $rows_bahan_belum,
        'count' => $count_belum,
        'show_referensi' => true,
        'btn_class' => 'btn-prod-referensi-persediaan',
        'btn_label' => 'Referensi',
        'status_badge_class' => 'badge-danger',
        'active' => true,
    ),
    array(
        'key' => 'manual',
        'tab_id' => 'subtab-produksi-persediaan-manual',
        'link_id' => 'subtab-produksi-persediaan-manual-link',
        'title' => 'Terverifikasi Manual',
        'badge_class' => 'badge-info',
        'table_id' => 'table-produksi-verifikasi-manual',
        'data' => $rows_bahan_manual,
        'count' => $count_manual,
        'show_referensi' => true,
        'btn_class' => 'btn-prod-update-referensi-persediaan',
        'btn_label' => 'Update',
        'status_badge_class' => 'badge-info',
        'active' => false,
        'show_refered_manual_cols' => true,
    ),
    array(
        'key' => 'otomatis',
        'tab_id' => 'subtab-produksi-persediaan-otomatis',
        'link_id' => 'subtab-produksi-persediaan-otomatis-link',
        'title' => 'Verifikasi Otomatis',
        'badge_class' => 'badge-success',
        'table_id' => 'table-produksi-verifikasi-otomatis',
        'data' => $rows_bahan_otomatis,
        'count' => $count_otomatis,
        'show_referensi' => false,
        'btn_class' => '',
        'btn_label' => '',
        'status_badge_class' => 'badge-success',
        'active' => false,
    ),
);
?>
<div class="produksi-verifikasi-persediaan-inner">
    <?php if ($message !== '') { ?>
        <div class="alert alert-danger small py-2 mb-2"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <div class="alert alert-warning small py-2 mb-2">
        Verifikasi bahan produksi (<code>sys_unit_produk_bahan</code>) bulan <strong><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></strong>
        ke tabel <strong>persediaan</strong>. Tab <strong>Belum Terverifikasi</strong> perlu referensi manual;
        <strong>Terverifikasi Manual</strong> bisa di-Update (pindah record persediaan);
        <strong>Verifikasi Otomatis</strong> = cocok sistem / generate.
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
        <span class="small text-muted" id="info-jumlah-verifikasi-bulan">
            Belum: <?php echo (int) $count_belum; ?> | Manual: <?php echo (int) $count_manual; ?> | Otomatis: <?php echo (int) $count_otomatis; ?> — bulan <?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <?php if (!empty($rekap)) { ?>
            <span class="small">
                Total bahan: <strong><?php echo isset($rekap['count_bahan']) ? (int) $rekap['count_bahan'] : 0; ?></strong>
                &nbsp;|&nbsp; Cocok: <strong class="text-success"><?php echo isset($rekap['count_bahan_update']) ? (int) $rekap['count_bahan_update'] : 0; ?></strong>
                &nbsp;|&nbsp; Belum cocok: <strong class="text-danger"><?php echo isset($rekap['count_bahan_tidak_ada']) ? (int) $rekap['count_bahan_tidak_ada'] : 0; ?></strong>
            </span>
        <?php } ?>
    </div>

    <ul class="nav nav-pills mb-3" id="produksi-persediaan-subtabs" role="tablist">
        <?php foreach ($sections as $sec) :
            $nav_active = !empty($sec['active']) ? ' active' : '';
        ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $nav_active; ?>" id="<?php echo htmlspecialchars($sec['link_id'], ENT_QUOTES, 'UTF-8'); ?>"
                data-toggle="tab" href="#<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tab"
                data-table-id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($sec['title'], ENT_QUOTES, 'UTF-8'); ?>
                <span class="badge <?php echo htmlspecialchars($sec['badge_class'], ENT_QUOTES, 'UTF-8'); ?> ml-1 prod-verifikasi-count-<?php echo htmlspecialchars($sec['key'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $sec['count']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content" id="produksi-persediaan-subtabs-content">
        <?php foreach ($sections as $sec) :
            $pane_active = !empty($sec['active']) ? ' show active' : '';
            $sum_jumlah = 0.0;
            $sum_total_bahan = 0.0;
        ?>
        <div class="tab-pane fade<?php echo $pane_active; ?>" id="<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tabpanel">
            <div class="table-responsive produksi-verifikasi-dt-wrap">
                <table id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="table table-bordered table-striped table-sm display nowrap produksi-verifikasi-dt-table" style="width:100%">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <?php if (!empty($sec['show_referensi'])) { ?><th width="90">Aksi</th><?php } ?>
                            <th>Tgl Transaksi</th>
                            <th>Nama Unit</th>
                            <th>Nama Produk</th>
                            <th>Nama Bahan</th>
                            <th>Satuan</th>
                            <th>Jumlah Bahan</th>
                            <th>HPP Bahan</th>
                            <th>Total Bahan</th>
                            <th>Status</th>
                            <?php if (!empty($sec['show_refered_manual_cols'])) { ?>
                            <th>Nama Barang Refered</th>
                            <th>Satuan Refered</th>
                            <th>HPP Refered</th>
                            <?php } ?>
                            <th>Keterangan</th>
                            <th>Ref. UUID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        foreach ($sec['data'] as $row) {
                            $no++;
                            $id_bahan = isset($row->id) ? (int) $row->id : 0;
                            $jumlah_num = (float) persediaan_parse_angka(isset($row->jumlah_bahan_num) ? $row->jumlah_bahan_num : (isset($row->jumlah_bahan) ? $row->jumlah_bahan : 0));
                            $hpp = (float) persediaan_parse_angka(isset($row->harga_satuan_bahan) ? $row->harga_satuan_bahan : 0);
                            $total_bahan = isset($row->total_bahan_num) ? (float) $row->total_bahan_num : ($jumlah_num * $hpp);
                            $sum_jumlah += $jumlah_num;
                            $sum_total_bahan += $total_bahan;
                            $nama_bahan = isset($row->nama_barang_bahan) ? (string) $row->nama_barang_bahan : '';
                            $satuan_bahan = isset($row->satuan_bahan) ? (string) $row->satuan_bahan : '';
                            $nama_unit = isset($row->nama_unit) ? (string) $row->nama_unit : '';
                            $nama_produk = isset($row->nama_produk) ? (string) $row->nama_produk : '';
                            $jumlah_tampil = persediaan_gen_proses_pembelian_format_jumlah($jumlah_num);
                            $status = isset($row->status_label) ? (string) $row->status_label : '';
                            $ket = isset($row->keterangan) ? (string) $row->keterangan : '';
                            $ref_uuid = isset($row->uuid_persediaan_refered_manual) ? (string) $row->uuid_persediaan_refered_manual : '';
                            if ($ref_uuid === '' && isset($row->uuid_persediaan_bahan)) {
                                $ref_uuid = (string) $row->uuid_persediaan_bahan;
                            }
                            $ref_nama_manual = isset($row->nama_barang_refered_manual) ? trim((string) $row->nama_barang_refered_manual) : '';
                            $ref_satuan_manual = isset($row->satuan_refered_manual) ? trim((string) $row->satuan_refered_manual) : '';
                            $ref_hpp_manual_raw = isset($row->hpp_refered_manual) ? trim((string) $row->hpp_refered_manual) : '';
                            $ref_hpp_manual_num = ($ref_hpp_manual_raw !== '') ? (float) persediaan_parse_angka($ref_hpp_manual_raw) : 0.0;
                            $ref_hpp_manual_tampil = ($ref_hpp_manual_raw !== '') ? persediaan_gen_proses_pembelian_format_nominal($ref_hpp_manual_num) : '-';
                            $ref_nama_diff = (!empty($sec['show_refered_manual_cols']) && $ref_nama_manual !== '' && strcasecmp($ref_nama_manual, $nama_bahan) !== 0);
                            $ref_satuan_diff = (!empty($sec['show_refered_manual_cols']) && $ref_satuan_manual !== '' && strcasecmp($ref_satuan_manual, $satuan_bahan) !== 0);
                            $ref_hpp_diff = (!empty($sec['show_refered_manual_cols']) && $ref_hpp_manual_raw !== '' && abs($ref_hpp_manual_num - $hpp) > 0.009);
                        ?>
                        <tr data-bahan-id="<?php echo (int) $id_bahan; ?>">
                            <td style="text-align:center"><?php echo (int) $no; ?></td>
                            <?php if (!empty($sec['show_referensi'])) { ?>
                            <td style="text-align:center">
                                <?php if ($id_bahan > 0 && !empty($sec['btn_class'])) { ?>
                                <button type="button" class="btn btn-xs btn-info <?php echo htmlspecialchars($sec['btn_class'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-id-bahan="<?php echo (int) $id_bahan; ?>"
                                    data-nama-bahan="<?php echo htmlspecialchars($nama_bahan, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-satuan="<?php echo htmlspecialchars($satuan_bahan, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jumlah="<?php echo htmlspecialchars($jumlah_tampil, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-unit="<?php echo htmlspecialchars($nama_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-produk="<?php echo htmlspecialchars($nama_produk, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-ref-uuid="<?php echo htmlspecialchars($ref_uuid, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($sec['btn_label'], ENT_QUOTES, 'UTF-8'); ?>
                                </button>
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <td><?php echo persediaan_gen_proses_pembelian_format_tgl(isset($row->tgl_transaksi) ? $row->tgl_transaksi : ''); ?></td>
                            <td><?php echo htmlspecialchars($nama_unit, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($nama_produk, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($nama_bahan, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($satuan_bahan, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right prod-col-jumlah" data-num="<?php echo htmlspecialchars((string) $jumlah_num, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $jumlah_tampil; ?></td>
                            <td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($hpp); ?></td>
                            <td class="text-right prod-col-total" data-num="<?php echo htmlspecialchars((string) $total_bahan, ENT_QUOTES, 'UTF-8'); ?>"><?php echo persediaan_gen_proses_pembelian_format_nominal($total_bahan); ?></td>
                            <td><span class="badge <?php echo htmlspecialchars($sec['status_badge_class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <?php if (!empty($sec['show_refered_manual_cols'])) { ?>
                            <td class="<?php echo $ref_nama_diff ? 'prod-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_nama_manual !== '' ? $ref_nama_manual : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="<?php echo $ref_satuan_diff ? 'prod-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_satuan_manual !== '' ? $ref_satuan_manual : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right <?php echo $ref_hpp_diff ? 'prod-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_hpp_manual_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
                            <?php } ?>
                            <td><small><?php echo htmlspecialchars($ket, ENT_QUOTES, 'UTF-8'); ?></small></td>
                            <td><small><?php echo htmlspecialchars($ref_uuid, ENT_QUOTES, 'UTF-8'); ?></small></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <?php $hpp_rata_footer = ($sum_jumlah > 0) ? ($sum_total_bahan / $sum_jumlah) : 0.0; ?>
                        <tr>
                            <th class="font-weight-bold">TOTAL</th>
                            <?php if (!empty($sec['show_referensi'])) { ?><th></th><?php } ?>
                            <th colspan="5"></th>
                            <th class="text-right font-weight-bold prod-foot-jumlah"><?php echo persediaan_gen_proses_pembelian_format_jumlah($sum_jumlah); ?></th>
                            <th class="text-right font-weight-bold prod-foot-hpp" title="Rata-rata HPP tertimbang"><?php echo persediaan_gen_proses_pembelian_format_nominal($hpp_rata_footer); ?></th>
                            <th class="text-right font-weight-bold prod-foot-total"><?php echo persediaan_gen_proses_pembelian_format_nominal($sum_total_bahan); ?></th>
                            <th colspan="<?php echo !empty($sec['show_refered_manual_cols']) ? '6' : '3'; ?>"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
