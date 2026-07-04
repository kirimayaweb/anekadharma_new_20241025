<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-cogs mr-2 text-primary"></i>Setting Kode Akun Unit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Setting Kode Akun</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php
            if (!isset($tbl_source_options)) {
                $tbl_source_options = array();
            }
            ?>
            <?php if ($this->session->flashdata('message')): ?>
                <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-info-circle mr-1"></i> <?php echo $this->session->flashdata('message'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card card-outline card-primary shadow-lg border-0 ska-card">
                <div class="card-header ska-card-header">
                    <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                        <div>
                            <h3 class="card-title mb-0 text-white font-weight-bold">
                                <i class="fas fa-table mr-2"></i>Data Mapping Unit &amp; Kode Akun
                            </h3>
                            <small class="text-white-50">Kelola relasi kode akun per unit bisnis dan tabel sumber transaksi</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <button type="button" class="btn btn-light btn-sm shadow-sm mr-1" id="btn-tambah-data">
                                <i class="fas fa-plus-circle text-success"></i> Tambah Data
                            </button>
                            <a href="<?php echo htmlspecialchars($url_excel, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-success btn-sm shadow-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table id="tblSettingKodeAkun" class="table table-striped table-hover table-bordered w-100">
                            <thead class="ska-thead">
                                <tr>
                                    <th width="50">No</th>
                                    <th width="130" class="text-center">Aksi</th>
                                    <th width="160">Tabel Sumber</th>
                                    <th>Kode Unit</th>
                                    <th>Nama Unit</th>
                                    <th>Kode Akun</th>
                                    <th width="150" class="text-center">Pengali (×)</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0; foreach ($data_list as $row): ?>
                                    <tr>
                                        <td class="text-center"><?php echo ++$no; ?></td>
                                        <td class="text-center text-nowrap">
                                            <button type="button"
                                                class="btn btn-warning btn-xs btn-edit-ska shadow-sm"
                                                title="Ubah"
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
                                            <button type="button"
                                                class="btn btn-danger btn-xs btn-delete-ska shadow-sm"
                                                title="Hapus"
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
                                            <span class="badge badge-dark px-2 py-1" title="<?php echo htmlspecialchars($tbl_src_label); ?>">
                                                <i class="fas fa-database mr-1"></i><?php echo htmlspecialchars($tbl_src); ?>
                                            </span>
                                        </td>
                                        <td><span class="badge badge-info px-2 py-1"><?php echo htmlspecialchars($row->kode_unit); ?></span></td>
                                        <td><?php echo htmlspecialchars($row->nama_unit); ?></td>
                                        <td><span class="badge badge-primary px-2 py-1"><?php echo htmlspecialchars($row->kode_akun); ?></span></td>
                                        <td class="text-center">
                                            <?php
                                            $mutiply_val = (float) $row->mutiply_processing;
                                            $mutiply_label = rtrim(rtrim(number_format($mutiply_val, 6, '.', ''), '0'), '.');
                                            if ($mutiply_label === '' || $mutiply_label === '0') {
                                                $mutiply_label = '0.000001';
                                            }
                                            $pct_hint = rtrim(rtrim(number_format($mutiply_val * 100, 4, '.', ''), '0'), '.');
                                            if ($mutiply_val == 1) {
                                                $badge_class = 'badge-success';
                                            } elseif ($mutiply_val > 1) {
                                                $badge_class = 'badge-info';
                                            } elseif ($mutiply_val >= 0.5) {
                                                $badge_class = 'badge-primary';
                                            } else {
                                                $badge_class = 'badge-warning';
                                            }
                                            ?>
                                            <span class="badge <?php echo $badge_class; ?> px-2 py-1 d-inline-block"
                                                title="Nominal akhir = nominal asli × <?php echo htmlspecialchars($mutiply_label); ?> (<?php echo htmlspecialchars($pct_hint); ?>%)">
                                                × <?php echo htmlspecialchars($mutiply_label); ?>
                                            </span>
                                            <small class="d-block text-muted mt-1"><?php echo htmlspecialchars($pct_hint); ?>%</small>
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
    </section>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalSkaForm" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content ska-modal-content border-0 shadow-lg">
            <div class="modal-header ska-modal-header">
                <h5 class="modal-title text-white font-weight-bold" id="modalSkaTitle">
                    <i class="fas fa-pen-square mr-2"></i>Form Data
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formSka">
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="id" id="ska_id" value="">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ska_uuid_unit"><i class="fas fa-building text-primary mr-1"></i> Unit <span class="text-danger">*</span></label>
                                <select name="uuid_unit" id="ska_uuid_unit" class="form-control select2-ska" style="width:100%" required>
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ska_kode_unit"><i class="fas fa-barcode text-info mr-1"></i> Kode Unit</label>
                                <input type="text" class="form-control ska-input" name="kode_unit" id="ska_kode_unit" placeholder="Otomatis dari unit" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ska_nama_unit"><i class="fas fa-tag text-info mr-1"></i> Nama Unit</label>
                                <input type="text" class="form-control ska-input" name="nama_unit" id="ska_nama_unit" placeholder="Otomatis dari unit" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ska_tbl_source"><i class="fas fa-database text-dark mr-1"></i> Tabel Sumber <span class="text-danger">*</span></label>
                                <select name="tbl_source" id="ska_tbl_source" class="form-control select2-ska" style="width:100%" required>
                                    <option value="">-- Pilih Tabel Sumber --</option>
                                    <?php
                                    if (!isset($tbl_source_options)) {
                                        $tbl_source_options = array();
                                    }
                                    foreach ($tbl_source_options as $tbl_key => $tbl_label): ?>
                                        <option value="<?php echo htmlspecialchars($tbl_key, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($tbl_label); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Sumber data transaksi yang diproses ke buku besar.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ska_kode_akun"><i class="fas fa-book text-success mr-1"></i> Kode Akun <span class="text-danger">*</span></label>
                                <select name="kode_akun" id="ska_kode_akun" class="form-control select2-ska" style="width:100%" required>
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
                                <label for="ska_mutiply"><i class="fas fa-times-circle text-warning mr-1"></i> Koefisien Pengali (×) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light font-weight-bold">×</span>
                                    </div>
                                    <input type="text"
                                        class="form-control ska-input text-right font-weight-bold"
                                        name="mutiply_processing"
                                        id="ska_mutiply"
                                        value="1"
                                        placeholder="contoh: 1, 0.875, 0.125, 0.000001"
                                        inputmode="decimal"
                                        autocomplete="off"
                                        required>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Nilai desimal (DOUBLE). Rumus: <strong>nominal akhir = nominal asli × pengali</strong>.
                                    Contoh: <strong>0.875</strong> = 87,5% dari nominal, <strong>0.125</strong> = 12,5%.
                                    Range: <strong>0.000001</strong> s/d <strong>100</strong>.
                                </small>
                                <div class="btn-group btn-group-sm mt-2 ska-preset-group flex-wrap" role="group">
                                    <button type="button" class="btn btn-outline-secondary ska-preset" data-value="1" title="100% penuh">× 1</button>
                                    <button type="button" class="btn btn-outline-secondary ska-preset" data-value="0.875" title="87,5%">× 0.875</button>
                                    <button type="button" class="btn btn-outline-secondary ska-preset" data-value="0.5" title="50%">× 0.5</button>
                                    <button type="button" class="btn btn-outline-secondary ska-preset" data-value="0.125" title="12,5%">× 0.125</button>
                                    <button type="button" class="btn btn-outline-secondary ska-preset" data-value="0.000001" title="Minimum">× 0.000001</button>
                                </div>
                                <div id="ska_mutiply_preview" class="alert alert-info border-0 mt-2 mb-0 py-2 small shadow-sm">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Contoh: Rp 1.000.000 × <span id="ska_preview_coef">1</span> =
                                    <strong id="ska_preview_result">Rp 1.000.000</strong>
                                    <span class="text-muted">(<span id="ska_preview_pct">100</span>% dari nominal asli)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="ska_keterangan"><i class="fas fa-sticky-note text-secondary mr-1"></i> Keterangan</label>
                        <textarea class="form-control ska-input" name="keterangan" id="ska_keterangan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary ska-btn-submit" id="btnSkaSubmit">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ska-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .ska-card-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 55%, #003d80 100%);
        border: 0;
        padding: 1.1rem 1.25rem;
    }

    .ska-thead th {
        background: linear-gradient(180deg, #f8f9fc 0%, #eef2f7 100%);
        color: #334155;
        font-weight: 700;
        vertical-align: middle !important;
        border-bottom: 2px solid #dbeafe !important;
    }

    .ska-modal-header {
        background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
        border: 0;
    }

    .ska-modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    .ska-input,
    .select2-container--default .select2-selection--single {
        border-radius: 8px !important;
        min-height: 38px;
    }

    .ska-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.18);
    }

    #tblSettingKodeAkun tbody tr {
        transition: all 0.2s ease;
    }

    #tblSettingKodeAkun tbody tr:hover {
        background-color: #f0f7ff !important;
        transform: scale(1.002);
    }

    .btn-xs {
        padding: 0.2rem 0.45rem;
        font-size: 0.78rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding: 0.35rem 0.85rem;
        border: 1px solid #ced4da;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
    }

    .ska-preset-group .btn.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    #ska_mutiply_preview {
        background: linear-gradient(135deg, #e8f4fd 0%, #f0f7ff 100%);
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/AdminLTE310/plugins/select2/js/select2.full.min.js"></script>
<script>
(function () {
    var urlCreate = '<?php echo $url_create; ?>';
    var urlUpdate = '<?php echo $url_update; ?>';
    var urlDelete = '<?php echo $url_delete; ?>';
    var urlIndex = '<?php echo site_url('Setting_kode_akun'); ?>';
    var tableSka;
    var modeForm = 'create';

    var modeForm = 'create';
    var MUTIPLY_MIN = 0.000001;
    var MUTIPLY_MAX = 100;
    var MUTIPLY_DEFAULT = 1;

    function normalizeDecimalInput(raw) {
        return String(raw || '').trim().replace(/\s/g, '').replace(',', '.');
    }

    function parseMutiplyValue(raw) {
        var text = normalizeDecimalInput(raw);
        if (text === '') {
            return { ok: false, message: 'Koefisien pengali wajib diisi.' };
        }
        if (!/^-?\d*\.?\d+$/.test(text)) {
            return { ok: false, message: 'Koefisien pengali harus berupa angka desimal (contoh: 0.875, 0.125).' };
        }
        var num = parseFloat(text);
        if (isNaN(num)) {
            return { ok: false, message: 'Koefisien pengali harus berupa angka desimal.' };
        }
        if (num < MUTIPLY_MIN || num > MUTIPLY_MAX) {
            return {
                ok: false,
                message: 'Koefisien pengali harus antara ' + MUTIPLY_MIN + ' sampai ' + MUTIPLY_MAX + '.'
            };
        }
        return { ok: true, value: num };
    }

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function formatMutiplyLabel(num) {
        var text = num.toFixed(6).replace(/\.?0+$/, '');
        return text === '' ? '0' : text;
    }

    function formatPercentHint(num) {
        var pct = num * 100;
        var text = pct.toFixed(4).replace(/\.?0+$/, '');
        return text === '' ? '0' : text;
    }

    function isSameMutiply(a, b) {
        return Math.abs(parseFloat(a) - parseFloat(b)) < 0.0000001;
    }

    function updateMutiplyPreview() {
        var parsed = parseMutiplyValue($('#ska_mutiply').val());
        if (!parsed.ok) {
            $('#ska_preview_coef').text('-');
            $('#ska_preview_result').text('-');
            $('#ska_preview_pct').text('-');
            return;
        }

        var base = 1000000;
        var result = base * parsed.value;
        $('#ska_preview_coef').text(formatMutiplyLabel(parsed.value));
        $('#ska_preview_result').text(formatRupiah(result));
        $('#ska_preview_pct').text(formatPercentHint(parsed.value));
    }

    function highlightPreset(value) {
        $('.ska-preset').removeClass('active');
        $('.ska-preset').each(function () {
            if (isSameMutiply($(this).data('value'), value)) {
                $(this).addClass('active');
            }
        });
    }

    function initSelect2() {
        if ($.fn.select2) {
            $('.select2-ska').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modalSkaForm'),
                width: '100%'
            });
        }
    }

    function fillUnitFields() {
        var selected = $('#ska_uuid_unit option:selected');
        $('#ska_kode_unit').val(selected.data('kode') || '');
        $('#ska_nama_unit').val(selected.data('nama') || '');
    }

    function resetForm() {
        $('#formSka')[0].reset();
        $('#ska_id').val('');
        $('#ska_uuid_unit, #ska_kode_akun, #ska_tbl_source').val('').trigger('change');
        $('#ska_tbl_source').val('tbl_penjualan').trigger('change');
        $('#ska_mutiply').val(String(MUTIPLY_DEFAULT));
        updateMutiplyPreview();
        highlightPreset(MUTIPLY_DEFAULT);
        fillUnitFields();
    }

    function openModalCreate() {
        modeForm = 'create';
        resetForm();
        $('#modalSkaTitle').html('<i class="fas fa-plus-circle mr-2"></i>Tambah Setting Kode Akun');
        $('.ska-btn-submit').removeClass('btn-warning').addClass('btn-primary')
            .html('<i class="fas fa-save"></i> Simpan');
        $('#modalSkaForm').modal('show');
    }

    function openModalEdit(data) {
        modeForm = 'update';
        resetForm();
        $('#ska_id').val(data.id);
        $('#ska_uuid_unit').val(data.uuidUnit).trigger('change');
        fillUnitFields();
        $('#ska_kode_unit').val(data.kodeUnit);
        $('#ska_nama_unit').val(data.namaUnit);
        $('#ska_kode_akun').val(data.kodeAkun).trigger('change');
        $('#ska_tbl_source').val(data.tblSource || 'tbl_penjualan').trigger('change');
        $('#ska_mutiply').val(data.mutiply || String(MUTIPLY_DEFAULT));
        updateMutiplyPreview();
        highlightPreset(data.mutiply || MUTIPLY_DEFAULT);
        $('#ska_keterangan').val(data.keterangan);
        $('#modalSkaTitle').html('<i class="fas fa-edit mr-2"></i>Ubah Setting Kode Akun');
        $('.ska-btn-submit').removeClass('btn-primary').addClass('btn-warning')
            .html('<i class="fas fa-save"></i> Update');
        $('#modalSkaForm').modal('show');
    }

    function showSwal(type, title, text, callback) {
        Swal.fire({
            icon: type,
            title: title,
            text: text,
            confirmButtonColor: '#007bff',
            allowOutsideClick: false
        }).then(function () {
            if (typeof callback === 'function') {
                callback();
            }
        });
    }

    $(document).ready(function () {
        tableSka = $('#tblSettingKodeAkun').DataTable({
            scrollX: true,
            scrollY: '420px',
            scrollCollapse: true,
            paging: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
            order: [[3, 'asc']],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            },
            columnDefs: [
                { orderable: false, targets: [1] }
            ]
        });

        initSelect2();

        $('#modalSkaForm').on('shown.bs.modal', function () {
            initSelect2();
        });

        $('#btn-tambah-data').on('click', openModalCreate);

        $('#ska_uuid_unit').on('change', fillUnitFields);

        $('#ska_mutiply').on('input blur', function () {
            updateMutiplyPreview();
            highlightPreset($(this).val());
        });

        $(document).on('click', '.ska-preset', function () {
            var val = $(this).data('value');
            $('#ska_mutiply').val(val).trigger('input');
            highlightPreset(val);
        });

        $(document).on('click', '.btn-edit-ska', function () {
            var btn = $(this);
            openModalEdit({
                id: btn.data('id'),
                uuidUnit: btn.data('uuid-unit'),
                kodeUnit: btn.data('kode-unit'),
                namaUnit: btn.data('nama-unit'),
                kodeAkun: btn.data('kode-akun'),
                tblSource: btn.data('tbl-source'),
                mutiply: String(btn.data('mutiply')),
                keterangan: btn.data('keterangan')
            });
        });

        $(document).on('click', '.btn-delete-ska', function () {
            var id = $(this).data('id');
            var label = $(this).data('label');

            Swal.fire({
                icon: 'warning',
                title: 'Konfirmasi Hapus',
                html: 'Yakin hapus data <strong>' + label + '</strong>?',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: urlDelete,
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id },
                    success: function (res) {
                        if (res && res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 1800,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(function () {
                                window.location.href = urlIndex;
                            });
                        } else {
                            showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal menghapus data.');
                        }
                    },
                    error: function () {
                        showSwal('error', 'Gagal', 'Tidak dapat menghubungi server.');
                    }
                });
            });
        });

        $('#formSka').on('submit', function (e) {
            e.preventDefault();

            var uuidUnit = $.trim($('#ska_uuid_unit').val());
            var kodeAkun = $.trim($('#ska_kode_akun').val());
            var tblSource = $.trim($('#ska_tbl_source').val());

            if (uuidUnit === '' || kodeAkun === '' || tblSource === '') {
                showSwal('warning', 'Perhatian', 'Unit, kode akun, dan tabel sumber wajib diisi.');
                return;
            }

            var mutiplyCheck = parseMutiplyValue($('#ska_mutiply').val());
            if (!mutiplyCheck.ok) {
                showSwal('warning', 'Perhatian', mutiplyCheck.message);
                return;
            }
            $('#ska_mutiply').val(formatMutiplyLabel(mutiplyCheck.value));

            var urlTarget = (modeForm === 'update') ? urlUpdate : urlCreate;
            $('#btnSkaSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: urlTarget,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function (res) {
                    $('#btnSkaSubmit').prop('disabled', false)
                        .html(modeForm === 'update'
                            ? '<i class="fas fa-save"></i> Update'
                            : '<i class="fas fa-save"></i> Simpan');

                    if (res && res.success) {
                        $('#modalSkaForm').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function () {
                            window.location.href = urlIndex;
                        });
                    } else {
                        showSwal('error', 'Gagal', (res && res.message) ? res.message : 'Gagal menyimpan data.');
                    }
                },
                error: function () {
                    $('#btnSkaSubmit').prop('disabled', false)
                        .html(modeForm === 'update'
                            ? '<i class="fas fa-save"></i> Update'
                            : '<i class="fas fa-save"></i> Simpan');
                    showSwal('error', 'Gagal', 'Tidak dapat menghubungi server.');
                }
            });
        });

        <?php if ($this->session->flashdata('message')): ?>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: <?php echo json_encode(strip_tags($this->session->flashdata('message'))); ?>,
            confirmButtonColor: '#007bff'
        });
        <?php endif; ?>
    });
})();
</script>
