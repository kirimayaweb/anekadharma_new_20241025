<?php
/**
 * Panel CRUD sys_unit_kode_akun — tabel (bisa di-reload via AJAX).
 */
if (!isset($data_list)) {
    $data_list = array();
}
if (!isset($tbl_source_options)) {
    $tbl_source_options = array();
}
if (!isset($url_excel)) {
    $url_excel = site_url('Setting_kode_akun/excel');
}
?>
<div class="bb-unit-ska-embed">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 bb-unit-ska-toolbar">
        <div>
            <h5 class="mb-0 text-primary"><strong><i class="fas fa-cogs mr-1"></i> Setting Kode Akun Unit</strong></h5>
            <small class="text-muted">Kelola mapping kode akun per unit (<code>sys_unit_kode_akun</code>) — digunakan saat Recalculate Buku Besar</small>
        </div>
        <div class="mt-2 mt-md-0">
            <button type="button" class="btn btn-light btn-sm shadow-sm mr-1" id="bb-unit-ska-btn-tambah">
                <i class="fas fa-plus-circle text-success"></i> Tambah Data
            </button>
            <a href="<?php echo htmlspecialchars($url_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success btn-sm shadow-sm" target="_blank">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm border-0">
        <div class="card-body pt-3">
            <div class="table-responsive">
                <table id="bbUnitSkaTable" class="table table-striped table-hover table-bordered table-sm w-100">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">No</th>
                            <th width="120" class="text-center">Aksi</th>
                            <th width="160">Tabel Sumber</th>
                            <th>Kode Unit</th>
                            <th>Nama Unit</th>
                            <th>Kode Akun</th>
                            <th width="130" class="text-center">Pengali (×)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; foreach ($data_list as $row): ?>
                        <tr>
                            <td class="text-center"><?php echo ++$no; ?></td>
                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-warning btn-xs bb-unit-ska-btn-edit"
                                    data-id="<?php echo (int) $row->id; ?>"
                                    data-uuid-unit="<?php echo htmlspecialchars($row->uuid_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-kode-unit="<?php echo htmlspecialchars($row->kode_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama-unit="<?php echo htmlspecialchars($row->nama_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-kode-akun="<?php echo htmlspecialchars($row->kode_akun, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-tbl-source="<?php echo htmlspecialchars(isset($row->tbl_source) ? $row->tbl_source : 'tbl_penjualan', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-mutiply="<?php echo htmlspecialchars($row->mutiply_processing, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-keterangan="<?php echo htmlspecialchars($row->keterangan, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs bb-unit-ska-btn-delete"
                                    data-id="<?php echo (int) $row->id; ?>"
                                    data-label="<?php echo htmlspecialchars($row->nama_unit . ' - ' . $row->kode_akun, ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td>
                                <?php
                                $tbl_src = isset($row->tbl_source) && $row->tbl_source !== '' ? $row->tbl_source : 'tbl_penjualan';
                                $tbl_src_label = isset($tbl_source_options[$tbl_src]) ? $tbl_source_options[$tbl_src] : $tbl_src;
                                ?>
                                <span class="badge badge-dark" title="<?php echo htmlspecialchars($tbl_src_label); ?>">
                                    <i class="fas fa-database mr-1"></i><?php echo htmlspecialchars($tbl_src); ?>
                                </span>
                            </td>
                            <td><span class="badge badge-info"><?php echo htmlspecialchars($row->kode_unit); ?></span></td>
                            <td><?php echo htmlspecialchars($row->nama_unit); ?></td>
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($row->kode_akun); ?></span></td>
                            <td class="text-center">
                                <?php
                                $mutiply_val = (float) $row->mutiply_processing;
                                $mutiply_label = rtrim(rtrim(number_format($mutiply_val, 6, '.', ''), '0'), '.');
                                if ($mutiply_label === '' || $mutiply_label === '0') {
                                    $mutiply_label = '1';
                                }
                                ?>
                                <span class="badge badge-secondary">× <?php echo htmlspecialchars($mutiply_label); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row->keterangan); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
