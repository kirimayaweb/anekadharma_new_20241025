<?php
/**
 * Modal form sys_unit_kode_akun — tetap di DOM (tidak ikut reload panel).
 */
if (!isset($data_unit)) {
    $data_unit = array();
}
if (!isset($data_kode_akun)) {
    $data_kode_akun = array();
}
if (!isset($tbl_source_options)) {
    $tbl_source_options = array();
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
                    <div class="form-group">
                        <label for="bb_unit_ska_uuid_unit"><i class="fas fa-building text-primary mr-1"></i> Unit <span class="text-danger">*</span></label>
                        <select name="uuid_unit" id="bb_unit_ska_uuid_unit" class="form-control form-control-sm bb-unit-ska-select2" required>
                            <option value="">-- Pilih Unit --</option>
                            <?php foreach ($data_unit as $unit): ?>
                            <option value="<?php echo htmlspecialchars($unit->uuid_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                data-kode="<?php echo htmlspecialchars($unit->kode_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                data-nama="<?php echo htmlspecialchars($unit->nama_unit, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($unit->kode_unit . ' - ' . $unit->nama_unit); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bb_unit_ska_kode_unit">Kode Unit</label>
                                <input type="text" class="form-control form-control-sm" name="kode_unit" id="bb_unit_ska_kode_unit" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bb_unit_ska_nama_unit">Nama Unit</label>
                                <input type="text" class="form-control form-control-sm" name="nama_unit" id="bb_unit_ska_nama_unit" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bb_unit_ska_tbl_source"><i class="fas fa-database text-dark mr-1"></i> Tabel Sumber <span class="text-danger">*</span></label>
                                <select name="tbl_source" id="bb_unit_ska_tbl_source" class="form-control form-control-sm bb-unit-ska-select2" required>
                                    <option value="">-- Pilih Tabel Sumber --</option>
                                    <?php foreach ($tbl_source_options as $tbl_key => $tbl_label): ?>
                                    <option value="<?php echo htmlspecialchars($tbl_key, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars($tbl_label); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
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
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="bb_unit_ska_keterangan">Keterangan</label>
                                <textarea class="form-control form-control-sm" name="keterangan" id="bb_unit_ska_keterangan" rows="2"></textarea>
                            </div>
                        </div>
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
