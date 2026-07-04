<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-pen-fancy mr-2 text-primary"></i>
                        <?php echo ($button === 'UPDATE') ? 'Ubah' : 'Tambah'; ?> Setting Kode Akun
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo site_url('Setting_kode_akun'); ?>">Setting Kode Akun</a></li>
                        <li class="breadcrumb-item active"><?php echo ($button === 'UPDATE') ? 'Ubah' : 'Tambah'; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-lg border-0 ska-form-card">
                        <div class="card-header ska-form-header">
                            <h3 class="card-title text-white mb-0 font-weight-bold">
                                <i class="fas fa-file-invoice mr-2"></i>Form Input Data
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo $action; ?>" method="post" id="formSkaPage">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                                <div class="form-group">
                                    <label for="uuid_unit"><i class="fas fa-building text-primary mr-1"></i> Unit <?php echo form_error('uuid_unit'); ?> <span class="text-danger">*</span></label>
                                    <select name="uuid_unit" id="uuid_unit" class="form-control select2-page" required>
                                        <option value="">-- Pilih Unit --</option>
                                        <?php foreach ($data_unit as $unit): ?>
                                            <option value="<?php echo htmlspecialchars($unit->uuid_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-kode="<?php echo htmlspecialchars($unit->kode_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-nama="<?php echo htmlspecialchars($unit->nama_unit, ENT_QUOTES, 'UTF-8'); ?>"
                                                <?php echo ($uuid_unit === $unit->uuid_unit) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($unit->kode_unit . ' - ' . $unit->nama_unit); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_unit">Kode Unit</label>
                                            <input type="text" class="form-control" name="kode_unit" id="kode_unit" value="<?php echo htmlspecialchars($kode_unit); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_unit">Nama Unit</label>
                                            <input type="text" class="form-control" name="nama_unit" id="nama_unit" value="<?php echo htmlspecialchars($nama_unit); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tbl_source"><i class="fas fa-database text-dark mr-1"></i> Tabel Sumber <?php echo form_error('tbl_source'); ?> <span class="text-danger">*</span></label>
                                            <select name="tbl_source" id="tbl_source" class="form-control select2-page" required>
                                                <option value="">-- Pilih Tabel Sumber --</option>
                                                <?php
                                                if (!isset($tbl_source_options)) {
                                                    $tbl_source_options = array();
                                                }
                                                foreach ($tbl_source_options as $tbl_key => $tbl_label): ?>
                                                    <option value="<?php echo htmlspecialchars($tbl_key, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php echo ($tbl_source === $tbl_key) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($tbl_label); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted">Sumber data transaksi yang diproses ke buku besar.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_akun">Kode Akun <?php echo form_error('kode_akun'); ?> <span class="text-danger">*</span></label>
                                            <select name="kode_akun" id="kode_akun" class="form-control select2-page" required>
                                                <option value="">-- Pilih Kode Akun --</option>
                                                <?php foreach ($data_kode_akun as $akun): ?>
                                                    <option value="<?php echo htmlspecialchars($akun->kode_akun, ENT_QUOTES, 'UTF-8'); ?>"
                                                        <?php echo ($kode_akun === $akun->kode_akun) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($akun->kode_akun . ' - ' . $akun->nama_akun); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="mutiply_processing">Koefisien Pengali (×) <?php echo form_error('mutiply_processing'); ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light font-weight-bold">×</span>
                                        </div>
                                        <input type="text"
                                            class="form-control text-right font-weight-bold"
                                            name="mutiply_processing"
                                            id="mutiply_processing"
                                            value="<?php echo htmlspecialchars($mutiply_processing); ?>"
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
                                    <div id="mutiply_preview" class="alert alert-info border-0 mt-2 mb-0 py-2 small shadow-sm">
                                        <i class="fas fa-calculator mr-1"></i>
                                        Contoh: Rp 1.000.000 × <span id="preview_coef"><?php echo htmlspecialchars($mutiply_processing); ?></span> =
                                        <strong id="preview_result">-</strong>
                                        <span class="text-muted">(<span id="preview_pct">-</span>% dari nominal asli)</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Catatan tambahan"><?php echo htmlspecialchars($keterangan); ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="<?php echo site_url('Setting_kode_akun'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="btnSubmitPage">
                                        <i class="fas fa-save"></i> <?php echo $button; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .ska-form-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .ska-form-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: 0;
    }

    .form-control:focus,
    .select2-container--default .select2-selection--single:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, 0.18);
    }

    .ska-preset-group .btn.active {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    #mutiply_preview {
        background: linear-gradient(135deg, #e8f4fd 0%, #f0f7ff 100%);
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/AdminLTE310/plugins/select2/js/select2.full.min.js"></script>
<script>
(function () {
    var MUTIPLY_MIN = 0.000001;
    var MUTIPLY_MAX = 100;

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
        var parsed = parseMutiplyValue($('#mutiply_processing').val());
        if (!parsed.ok) {
            $('#preview_coef').text('-');
            $('#preview_result').text('-');
            $('#preview_pct').text('-');
            return;
        }

        var base = 1000000;
        var result = base * parsed.value;
        $('#preview_coef').text(formatMutiplyLabel(parsed.value));
        $('#preview_result').text(formatRupiah(result));
        $('#preview_pct').text(formatPercentHint(parsed.value));
    }

    function highlightPreset(value) {
        $('.ska-preset').removeClass('active');
        $('.ska-preset').each(function () {
            if (isSameMutiply($(this).data('value'), value)) {
                $(this).addClass('active');
            }
        });
    }

    function fillUnitFields() {
        var selected = $('#uuid_unit option:selected');
        $('#kode_unit').val(selected.data('kode') || '');
        $('#nama_unit').val(selected.data('nama') || '');
    }

    $(document).ready(function () {
        if ($.fn.select2) {
            $('.select2-page').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }

        fillUnitFields();
        $('#uuid_unit').on('change', fillUnitFields);

        updateMutiplyPreview();
        highlightPreset($('#mutiply_processing').val());

        $('#mutiply_processing').on('input blur', function () {
            updateMutiplyPreview();
            highlightPreset($(this).val());
        });

        $(document).on('click', '.ska-preset', function () {
            var val = $(this).data('value');
            $('#mutiply_processing').val(val).trigger('input');
            highlightPreset(val);
        });

        $('#formSkaPage').on('submit', function (e) {
            var mutiplyCheck = parseMutiplyValue($('#mutiply_processing').val());
            if (!mutiplyCheck.ok) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: mutiplyCheck.message,
                    confirmButtonColor: '#007bff'
                });
                return false;
            }

            $('#mutiply_processing').val(formatMutiplyLabel(mutiplyCheck.value));

            $('#btnSubmitPage').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
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
