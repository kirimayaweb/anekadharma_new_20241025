<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$rows_belum = isset($rows_belum) && is_array($rows_belum) ? $rows_belum : array();
$rows_manual = isset($rows_manual) && is_array($rows_manual) ? $rows_manual : array();
$rows_otomatis = isset($rows_otomatis) && is_array($rows_otomatis) ? $rows_otomatis : array();
$count_belum = isset($count_belum) ? (int) $count_belum : count($rows_belum);
$count_manual = isset($count_manual) ? (int) $count_manual : count($rows_manual);
$count_otomatis = isset($count_otomatis) ? (int) $count_otomatis : count($rows_otomatis);
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
        'tab_id' => 'subtab-pecah-persediaan-belum',
        'link_id' => 'subtab-pecah-persediaan-belum-link',
        'title' => 'Belum Terverifikasi',
        'badge_class' => 'badge-warning',
        'table_id' => 'table-pecah-verifikasi-belum',
        'data' => $rows_belum,
        'count' => $count_belum,
        'show_referensi' => true,
        'btn_class' => 'btn-pecah-referensi-persediaan',
        'btn_label' => 'Referensi',
        'status_badge_class' => 'badge-danger',
        'show_refered_manual_cols' => false,
        'active' => true,
    ),
    array(
        'key' => 'manual',
        'tab_id' => 'subtab-pecah-persediaan-manual',
        'link_id' => 'subtab-pecah-persediaan-manual-link',
        'title' => 'Terverifikasi Manual',
        'badge_class' => 'badge-info',
        'table_id' => 'table-pecah-verifikasi-manual',
        'data' => $rows_manual,
        'count' => $count_manual,
        'show_referensi' => true,
        'btn_class' => 'btn-pecah-update-referensi-persediaan',
        'btn_label' => 'Update',
        'status_badge_class' => 'badge-info',
        'show_refered_manual_cols' => true,
        'active' => false,
    ),
    array(
        'key' => 'otomatis',
        'tab_id' => 'subtab-pecah-persediaan-otomatis',
        'link_id' => 'subtab-pecah-persediaan-otomatis-link',
        'title' => 'Verifikasi Otomatis',
        'badge_class' => 'badge-success',
        'table_id' => 'table-pecah-verifikasi-otomatis',
        'data' => $rows_otomatis,
        'count' => $count_otomatis,
        'show_referensi' => false,
        'btn_class' => '',
        'btn_label' => '',
        'status_badge_class' => 'badge-success',
        'show_refered_manual_cols' => false,
        'active' => false,
    ),
);
?>
<div class="pecah-verifikasi-persediaan-inner">
    <?php if ($message !== '') { ?>
        <div class="alert alert-danger small py-2 mb-2"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php } ?>

    <div class="alert alert-info small py-2 mb-2">
        Verifikasi koneksi <strong>tbl_pembelian_pecah_satuan</strong> → <strong>persediaan</strong> bulan <strong><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></strong>.
        Sumber: <code>pecah_satuan += jumlah</code>, <code>total_10 -= jumlah</code>.
        Target: <code>sa += jumlah_barang_baru</code>, <code>total_10 += jumlah_barang_baru</code>.
        Matching otomatis memakai <code>uuid_barang</code> / <code>uuid_barang_baru</code> (stabil setelah generate/recalculate).
        Referensi manual hanya jika sumber persediaan memang berbeda dari data pecah.
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
        <div class="mb-1">
            <button type="button" class="btn btn-success btn-sm" id="btn-pecah-auto-verifikasi-bulan" title="Sinkronkan link, terapkan ke persediaan, tandai otomatis">
                <i class="fas fa-magic"></i> Proses Otomatis Semua
            </button>
        </div>
        <span class="small text-muted" id="info-jumlah-pecah-verifikasi-bulan">
            Belum: <?php echo (int) $count_belum; ?> | Manual: <?php echo (int) $count_manual; ?> | Otomatis: <?php echo (int) $count_otomatis; ?> — bulan <?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <?php if (!empty($rekap)) { ?>
            <span class="small">
                Total pecah: <strong><?php echo isset($rekap['count_pecah_satuan']) ? (int) $rekap['count_pecah_satuan'] : 0; ?></strong>
                &nbsp;|&nbsp; Cocok: <strong class="text-success"><?php echo isset($rekap['count_update']) ? (int) $rekap['count_update'] : 0; ?></strong>
                &nbsp;|&nbsp; Belum cocok: <strong class="text-danger"><?php echo isset($rekap['count_tidak_cocok']) ? (int) $rekap['count_tidak_cocok'] : 0; ?></strong>
            </span>
        <?php } ?>
    </div>

    <ul class="nav nav-pills mb-3" id="pecah-persediaan-subtabs" role="tablist">
        <?php foreach ($sections as $sec) :
            $nav_active = !empty($sec['active']) ? ' active' : '';
        ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $nav_active; ?>" id="<?php echo htmlspecialchars($sec['link_id'], ENT_QUOTES, 'UTF-8'); ?>"
                data-toggle="tab" href="#<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tab"
                data-table-id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($sec['title'], ENT_QUOTES, 'UTF-8'); ?>
                <span class="badge <?php echo htmlspecialchars($sec['badge_class'], ENT_QUOTES, 'UTF-8'); ?> ml-1 pecah-verifikasi-count-<?php echo htmlspecialchars($sec['key'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $sec['count']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content" id="pecah-persediaan-subtabs-content">
        <?php foreach ($sections as $sec) :
            $pane_active = !empty($sec['active']) ? ' show active' : '';
        ?>
        <div class="tab-pane fade<?php echo $pane_active; ?>" id="<?php echo htmlspecialchars($sec['tab_id'], ENT_QUOTES, 'UTF-8'); ?>" role="tabpanel">
            <div class="table-responsive pecah-verifikasi-dt-wrap">
                <table id="<?php echo htmlspecialchars($sec['table_id'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="table table-bordered table-striped table-sm display nowrap pecah-verifikasi-dt-table" style="width:100%">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <?php if (!empty($sec['show_referensi'])) { ?><th width="90">Aksi</th><?php } ?>
                            <th>Tgl Proses</th>
                            <th>ID</th>
                            <th>Nama Barang (Sumber)</th>
                            <th>Satuan</th>
                            <th>HPP Sumber</th>
                            <th>Jml Dipecah</th>
                            <th>Nama Barang Baru</th>
                            <th>Satuan Baru</th>
                            <th>HPP Baru</th>
                            <th>Jml Baru</th>
                            <th>ID Pers. Sumber</th>
                            <th>ID Pers. Target</th>
                            <th>Status</th>
                            <?php if (!empty($sec['show_refered_manual_cols'])) { ?>
                            <th>Nama Refered</th>
                            <th>Satuan Refered</th>
                            <th>HPP Refered</th>
                            <th>Jml Refered</th>
                            <?php } ?>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        foreach ($sec['data'] as $row) {
                            $no++;
                            $id_pecah = isset($row->id) ? (int) $row->id : 0;
                            $tgl_proses = !empty($row->proses_input) ? $row->proses_input : (isset($row->tgl_po) ? $row->tgl_po : '');
                            $nama_sumber = isset($row->uraian) ? (string) $row->uraian : '';
                            if ($nama_sumber === '' && isset($row->kode_barang)) {
                                $nama_sumber = (string) $row->kode_barang;
                            }
                            $satuan = isset($row->satuan) ? (string) $row->satuan : '';
                            $hpp = (float) persediaan_parse_angka(isset($row->harga_satuan) ? $row->harga_satuan : 0);
                            $jumlah = (float) persediaan_parse_angka(isset($row->jumlah) ? $row->jumlah : 0);
                            $nama_baru = isset($row->nama_barang_baru) ? (string) $row->nama_barang_baru : '';
                            $satuan_baru = isset($row->satuan_barang_baru) ? (string) $row->satuan_barang_baru : '';
                            $hpp_baru = (float) persediaan_parse_angka(isset($row->harga_satuan_barang_baru) ? $row->harga_satuan_barang_baru : 0);
                            $jumlah_baru = (float) persediaan_parse_angka(isset($row->jumlah_barang_baru) ? $row->jumlah_barang_baru : 0);
                            $id_pers_sumber = isset($row->id_persediaan_sumber) ? (int) $row->id_persediaan_sumber : (isset($row->id_persediaan_barang) ? (int) $row->id_persediaan_barang : 0);
                            $id_pers_target = isset($row->id_persediaan_target) ? (int) $row->id_persediaan_target : (isset($row->id_persediaan_baru) ? (int) $row->id_persediaan_baru : 0);
                            $status = isset($row->status_label_verifikasi) ? (string) $row->status_label_verifikasi : '';
                            $ket = isset($row->keterangan_verifikasi) ? (string) $row->keterangan_verifikasi : '';
                            $ref_uuid = isset($row->uuid_persediaan_refered_manual) ? (string) $row->uuid_persediaan_refered_manual : '';
                            $ref_nama = isset($row->nama_barang_refered_manual) ? trim((string) $row->nama_barang_refered_manual) : '';
                            $ref_satuan = isset($row->satuan_refered_manual) ? trim((string) $row->satuan_refered_manual) : '';
                            $ref_hpp_raw = isset($row->hpp_refered_manual) ? trim((string) $row->hpp_refered_manual) : '';
                            $ref_jumlah = isset($row->jumlah_terpecah_dari_refered) ? (float) persediaan_parse_angka($row->jumlah_terpecah_dari_refered) : 0;
                            $ref_hpp_tampil = ($ref_hpp_raw !== '') ? persediaan_gen_proses_pembelian_format_nominal((float) persediaan_parse_angka($ref_hpp_raw)) : '-';
                            $ref_nama_diff = (!empty($sec['show_refered_manual_cols']) && $ref_nama !== '' && strcasecmp($ref_nama, $nama_sumber) !== 0);
                            $ref_satuan_diff = (!empty($sec['show_refered_manual_cols']) && $ref_satuan !== '' && strcasecmp($ref_satuan, $satuan) !== 0);
                            $ref_hpp_diff = (!empty($sec['show_refered_manual_cols']) && $ref_hpp_raw !== '' && abs((float) persediaan_parse_angka($ref_hpp_raw) - $hpp) > 0.009);
                        ?>
                        <tr data-pecah-id="<?php echo (int) $id_pecah; ?>">
                            <td style="text-align:center"><?php echo (int) $no; ?></td>
                            <?php if (!empty($sec['show_referensi'])) { ?>
                            <td style="text-align:center">
                                <?php if ($id_pecah > 0 && !empty($sec['btn_class'])) { ?>
                                <button type="button" class="btn btn-xs btn-info <?php echo htmlspecialchars($sec['btn_class'], ENT_QUOTES, 'UTF-8'); ?>"
                                    data-id-pecah="<?php echo (int) $id_pecah; ?>"
                                    data-nama-sumber="<?php echo htmlspecialchars($nama_sumber, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-satuan="<?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jumlah="<?php echo htmlspecialchars(persediaan_gen_proses_pembelian_format_jumlah($jumlah), ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jumlah-baru="<?php echo htmlspecialchars(persediaan_gen_proses_pembelian_format_jumlah($jumlah_baru), ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-baru="<?php echo htmlspecialchars($nama_baru, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jumlah-refered="<?php echo $ref_jumlah > 0 ? htmlspecialchars(persediaan_gen_proses_pembelian_format_jumlah($ref_jumlah), ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    data-ref-uuid="<?php echo htmlspecialchars($ref_uuid, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($sec['btn_label'], ENT_QUOTES, 'UTF-8'); ?>
                                </button>
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <td><?php echo $tgl_proses !== '' ? date('d-m-Y H:i', strtotime($tgl_proses)) : ''; ?></td>
                            <td><?php echo (int) $id_pecah; ?></td>
                            <td><?php echo htmlspecialchars($nama_sumber, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($hpp); ?></td>
                            <td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah); ?></td>
                            <td><?php echo htmlspecialchars($nama_baru, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($satuan_baru, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_nominal($hpp_baru); ?></td>
                            <td class="text-right"><?php echo persediaan_gen_proses_pembelian_format_jumlah($jumlah_baru); ?></td>
                            <td><?php echo $id_pers_sumber > 0 ? (int) $id_pers_sumber : '—'; ?></td>
                            <td><?php echo $id_pers_target > 0 ? (int) $id_pers_target : '—'; ?></td>
                            <td><span class="badge <?php echo htmlspecialchars($sec['status_badge_class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <?php if (!empty($sec['show_refered_manual_cols'])) { ?>
                            <td class="<?php echo $ref_nama_diff ? 'pecah-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_nama !== '' ? $ref_nama : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="<?php echo $ref_satuan_diff ? 'pecah-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_satuan !== '' ? $ref_satuan : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right <?php echo $ref_hpp_diff ? 'pecah-ref-manual-diff' : ''; ?>"><?php echo htmlspecialchars($ref_hpp_tampil, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="text-right"><?php echo $ref_jumlah > 0 ? persediaan_gen_proses_pembelian_format_jumlah($ref_jumlah) : '-'; ?></td>
                            <?php } ?>
                            <td><small><?php echo htmlspecialchars($ket, ENT_QUOTES, 'UTF-8'); ?></small></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
