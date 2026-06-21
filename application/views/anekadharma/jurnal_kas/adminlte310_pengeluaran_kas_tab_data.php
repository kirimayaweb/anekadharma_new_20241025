<?php
if (!isset($pengeluaran_rows) || !is_array($pengeluaran_rows)) {
    $pengeluaran_rows = array();
}
if (!isset($can_input_pengeluaran_kas)) {
    $can_input_pengeluaran_kas = false;
}
if (!isset($TOTAL_debet_21101_SEMUA)) {
    $TOTAL_debet_21101_SEMUA = 0;
}
if (!isset($TOTAL_serba_serbi_jumlah_SEMUA)) {
    $TOTAL_serba_serbi_jumlah_SEMUA = 0;
}
if (!isset($TOTAL_kredit_11101_SEMUA)) {
    $TOTAL_kredit_11101_SEMUA = 0;
}
if (!isset($modal_pgk_tanggal_default)) {
    $modal_pgk_tanggal_default = date('d-m-Y');
}
if (!isset($list_kode_pl) || !is_array($list_kode_pl)) {
    $list_kode_pl = array();
}
$pgk_footer_totals = pengeluaran_kas_compute_footer_totals(
    $TOTAL_debet_21101_SEMUA,
    $TOTAL_serba_serbi_jumlah_SEMUA,
    $TOTAL_kredit_11101_SEMUA
);
?>
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pengeluaran-kas-tab1-toolbar">
                            <div>
                                <h5 class="mb-0 text-primary"><strong>Data Jurnal Pengeluaran Kas</strong> <span class="text-muted" id="pengeluaran-kas-label-periode">(<?php echo htmlspecialchars($pengeluaran_bulan_label, ENT_QUOTES, 'UTF-8'); ?>)</span></h5>
                                <small class="text-muted">Periode: <span id="pengeluaran-kas-label-range"><?php echo htmlspecialchars($pengeluaran_periode_label, ENT_QUOTES, 'UTF-8'); ?></span> — pilih bulan di atas untuk memuat ulang otomatis</small>
                                <div id="pengeluaran-kas-month-loading" class="text-info d-none mt-1"><i class="fas fa-spinner fa-spin"></i> Memuat data jurnal pengeluaran kas...</div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                                <?php if ($can_input_pengeluaran_kas) { ?>
                                <button type="button" class="btn btn-warning mr-2 mb-2 mb-md-0" id="btn-pengeluaran-kas-input-data" data-toggle="modal" data-target="#modal-pengeluaran-kas-form">
                                    <i class="fa fa-plus"></i> Input Data
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-success mb-2 mb-md-0" id="btn-pengeluaran-kas-excel">
                                    <i class="fa fa-file-excel-o"></i> Cetak ke Excel
                                </button>
                            </div>
                        </div>

                        <div id="pengeluaran-kas-table-wrap" class="pengeluaran-kas-dt-wrap">
                        <table id="table-pengeluaran-kas" class="table table-bordered display nowrap pengeluaran-kas-dt-table" style="width:100%">
                            <colgroup>
                                <col class="pgk-col-no">
                                <col class="pgk-col-tanggal">
                                <col class="pgk-col-bukti">
                                <col class="pgk-col-pl">
                                <col class="pgk-col-ket">
                                <col class="pgk-col-debit">
                                <col class="pgk-col-rek">
                                <col class="pgk-col-jumlah">
                                <col class="pgk-col-kredit">
                            </colgroup>
                            <thead>
                                <tr class="pgk-head-group">
                                    <th rowspan="3" class="pgk-th-no-sort">No</th>
                                    <th rowspan="3" class="pgk-th-no-sort">Tanggal</th>
                                    <th rowspan="3" class="pgk-th-no-sort">No. Bukti BKK</th>
                                    <th rowspan="3" class="pgk-th-no-sort">PL</th>
                                    <th rowspan="3" class="pgk-th-no-sort">KETERANGAN</th>
                                    <th colspan="3" class="pgk-th-debit-group pgk-th-no-sort">Debit</th>
                                    <th colspan="1" class="pgk-th-kredit-group pgk-th-no-sort">KREDIT</th>
                                </tr>
                                <tr class="pgk-head-group">
                                    <th rowspan="2" class="text-right pgk-th-no-sort">21101-UU Dagang</th>
                                    <th colspan="2" class="pgk-th-serba-group pgk-th-no-sort">Serba - serbi</th>
                                    <th rowspan="2" class="text-right pgk-th-no-sort">11101-Kas Besar</th>
                                </tr>
                                <tr class="pgk-head-leaf">
                                    <th class="pgk-th-no-sort">No. Rek</th>
                                    <th class="text-right pgk-th-no-sort">Jumlah Serba-Serbi</th>
                                </tr>
                            </thead>
                            <tbody id="pengeluaran-kas-tbody">
                                <?php foreach ($pengeluaran_rows as $row) { ?>
                                <tr data-pk="<?php echo htmlspecialchars((string) $row['pk'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <td><?php echo (int) $row['no']; ?></td>
                                    <td class="pgk-cell-tanggal">
                                        <div class="pgk-tanggal-text"><?php echo htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php if (!empty($can_input_pengeluaran_kas)) { ?>
                                        <div class="pgk-row-actions">
                                            <button type="button" class="btn btn-xs btn-warning btn-pgk-action btn-pgk-ubah" data-pk="<?php echo htmlspecialchars((string) $row['pk'], ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button type="button" class="btn btn-xs btn-danger btn-pgk-action btn-pgk-hapus" data-pk="<?php echo htmlspecialchars((string) $row['pk'], ENT_QUOTES, 'UTF-8'); ?>"><i class="fa fa-trash"></i> Hapus</button>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nomor_bukti_bkk'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['pl'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['keterangan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-right"><?php echo pengeluaran_kas_format_rupiah($row['debet_21101uu_dagang']); ?></td>
                                    <td><?php echo htmlspecialchars($row['serba_serbi_nomor_rekening'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-right"><?php echo pengeluaran_kas_format_rupiah($row['serba_serbi_jumlah']); ?></td>
                                    <td class="text-right"><?php echo pengeluaran_kas_format_rupiah($row['kredit_11101_kas_besar']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="pgk-row-footer-grand">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">GRAND TOTAL</th>
                                    <th class="text-right" id="pgk-total-debet"><?php echo pengeluaran_kas_format_rupiah($pgk_footer_totals['debet_21101'], true); ?></th>
                                    <th></th>
                                    <th class="text-right" id="pgk-total-jumlah"><?php echo pengeluaran_kas_format_rupiah($pgk_footer_totals['serba_serbi_jumlah'], true); ?></th>
                                    <th class="text-right" id="pgk-total-kredit"><?php echo pengeluaran_kas_format_rupiah($pgk_footer_totals['kredit_kas'], true); ?></th>
                                </tr>
                                <tr class="pgk-row-footer-balance">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">TOTAL</th>
                                    <th class="text-right" id="pgk-total-combined-debet"><?php echo pengeluaran_kas_format_rupiah($pgk_footer_totals['combined_debet_21101'], true); ?></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right" id="pgk-total-balance-kredit"><?php echo pengeluaran_kas_format_rupiah($pgk_footer_totals['kredit_kas'], true); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        </div><!-- /#pengeluaran-kas-table-wrap -->

<?php if ($can_input_pengeluaran_kas) { ?>
    <div class="modal fade" id="modal-pengeluaran-kas-form" tabindex="-1" role="dialog" aria-labelledby="modal-pengeluaran-kas-form-title" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="max-width:960px;">
            <div class="modal-content">
                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title" id="modal-pengeluaran-kas-form-title"><i class="fa fa-edit"></i> Input Pengeluaran Kas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="form-pengeluaran-kas-modal" autocomplete="off">
                    <div class="modal-body">
                        <input type="hidden" name="pk" id="modal_pgk_pk" value="">
                        <div id="pengeluaran-kas-modal-errors" class="alert alert-danger d-none" role="alert"></div>
                        <p class="text-muted small mb-3">Field wajib: <strong>Tanggal</strong>, <strong>PL</strong>, <strong>Keterangan</strong>.</p>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_tanggal">Tanggal <span class="text-danger">*</span></label>
                                <div class="input-group date" id="modal_pgk_tanggal_wrap" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#modal_pgk_tanggal_wrap" id="modal_pgk_tanggal" name="tanggal" value="<?php echo htmlspecialchars($modal_pgk_tanggal_default, ENT_QUOTES, 'UTF-8'); ?>" required />
                                    <div class="input-group-append" data-target="#modal_pgk_tanggal_wrap" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_bukti">No. Bukti BKK</label>
                                <input type="text" name="nomor_bukti_bkk" id="modal_pgk_bukti" class="form-control" placeholder="Opsional">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_pl">PL <span class="text-danger">*</span></label>
                                <select name="pl" id="modal_pgk_pl" class="form-control modal-pgk-select2" style="width:100%;" required>
                                    <option value="">Pilih Kode PL</option>
                                    <?php foreach ($list_kode_pl as $pl_row) { ?>
                                        <option value="<?php echo htmlspecialchars($pl_row->kode_pl, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo strtoupper($pl_row->kode_pl) . ' ==> ' . strtoupper($pl_row->keterangan); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_keterangan">Keterangan <span class="text-danger">*</span></label>
                                <input type="text" name="keterangan" id="modal_pgk_keterangan" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_debet">21101 - UU Dagang</label>
                                <input type="text" name="debet_21101uu_dagang" id="modal_pgk_debet" class="form-control" placeholder="0">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_rekening">Serba-Serbi No. Rekening</label>
                                <input type="text" name="serba_serbi_nomor_rekening" id="modal_pgk_rekening" class="form-control" placeholder="No. rekening">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_jumlah">Serba-Serbi Jumlah</label>
                                <input type="text" name="serba_serbi_jumlah" id="modal_pgk_jumlah" class="form-control" placeholder="0">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 mb-2">
                                <label for="modal_pgk_kredit">11101 - Kas Besar</label>
                                <input type="text" name="kredit_11101_kas_besar" id="modal_pgk_kredit" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-pengeluaran-kas-modal-simpan">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
