<?php
/**
 * Modal form sys_unit_kode_akun — tetap di DOM (tidak ikut reload panel).
 */
if (!isset($tbl_source_options)) {
    $tbl_source_options = array();
}
if (!isset($data_kode_akun)) {
    $data_kode_akun = array();
}
?>
<div class="modal fade" id="bbUnitSkaModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bb-unit-ska-modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title font-weight-bold" id="bbUnitSkaModalTitle"><i class="fas fa-pen-square mr-2"></i>Form Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="bbUnitSkaForm">
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="id" id="bb_unit_ska_id" value="">
                    <input type="hidden" name="uuid_unit" id="bb_unit_ska_uuid_unit" value="">
                    <input type="hidden" name="kode_unit" id="bb_unit_ska_kode_unit" value="">
                    <input type="hidden" name="nama_unit" id="bb_unit_ska_nama_unit" value="">
                    <input type="hidden" name="source_value" id="bb_unit_ska_source_value_hidden" value="">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="bb_unit_ska_tbl_source"><i class="fas fa-database text-dark mr-1"></i> Tabel Sumber <span class="text-danger">*</span></label>
                                <select name="tbl_source" id="bb_unit_ska_tbl_source" class="form-control form-control-sm bb-unit-ska-select2" required>
                                    <option value="">-- Pilih Tabel Sumber --</option>
                                    <?php foreach ($tbl_source_options as $tbl_key => $tbl_label): ?>
                                    <option value="<?php echo htmlspecialchars($tbl_key, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($tbl_label); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Awal setting — tentukan transaksi/proses data sumber.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="bb_unit_ska_source_field"><i class="fas fa-columns text-secondary mr-1"></i> Field <span class="text-danger">*</span></label>
                                <select name="source_field" id="bb_unit_ska_source_field" class="form-control form-control-sm bb-unit-ska-select2" required disabled>
                                    <option value="">-- Pilih field --</option>
                                </select>
                                <small class="text-muted">Field dari tabel sumber yang dipakai untuk mapping.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2" id="bb_unit_ska_source_value_wrap">
                        <label for="bb_unit_ska_source_value" id="bb_unit_ska_source_value_label"><i class="fas fa-list text-primary mr-1"></i> Nilai Sumber <span class="text-danger">*</span></label>
                        <select id="bb_unit_ska_source_value" class="form-control form-control-sm bb-unit-ska-select2" required disabled>
                            <option value="">-- Pilih tabel sumber &amp; field terlebih dahulu --</option>
                        </select>
                        <small class="text-muted" id="bb_unit_ska_source_value_hint">Data diambil dari record field terpilih.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="bb_unit_ska_kode_akun"><i class="fas fa-book text-success mr-1"></i> Kode Akun <span class="text-danger">*</span></label>
                                <select name="kode_akun" id="bb_unit_ska_kode_akun" class="form-control form-control-sm bb-unit-ska-select2" required>
                                    <option value="">-- Pilih Kode Akun --</option>
                                    <?php foreach ($data_kode_akun as $akun): ?>
                                    <option value="<?php echo htmlspecialchars($akun->kode_akun, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($akun->kode_akun . ' - ' . $akun->nama_akun); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="bb_unit_ska_mutiply"><i class="fas fa-times-circle text-warning mr-1"></i> Koefisien Pengali (×) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text font-weight-bold">×</span></div>
                                    <input type="text" class="form-control text-right font-weight-bold" name="mutiply_processing" id="bb_unit_ska_mutiply" value="1" required>
                                </div>
                                <small class="text-muted d-block mt-1">Nominal akhir = nominal asli × pengali</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="bb_unit_ska_keterangan"><i class="fas fa-sticky-note text-secondary mr-1"></i> Keterangan</label>
                        <textarea class="form-control form-control-sm" name="keterangan" id="bb_unit_ska_keterangan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="bbUnitSkaBtnSubmit"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
