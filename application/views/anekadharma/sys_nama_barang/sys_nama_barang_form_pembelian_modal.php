<form action="<?php echo $action; ?>" method="post" id="form-input-barang-baru-modal">
    <div class="alert d-none" id="input_barang_baru_info"></div>

    <div class="row">
        <div class="col-12">
            <div class="form-group mb-2">
                <div class="d-flex align-items-end flex-nowrap" style="gap: 8px;">
                    <div class="flex-grow-1" style="min-width: 0;">
                        <label for="modal_kategori" class="d-block">Kategori <small class="text-muted">(opsional)</small></label>
                        <div class="modal-kategori-select-wrap">
                            <select class="form-control select2-kategori-modal" name="kategori" id="modal_kategori" data-placeholder="-- Pilih Kategori --" autocomplete="off">
                                <option value=""></option>
                                <?php if (!empty($kategori_barang_options)) { ?>
                                    <?php foreach ($kategori_barang_options as $kat) { ?>
                                        <?php $valKat = isset($kat->kategori) ? $kat->kategori : ''; ?>
                                        <option value="<?php echo htmlspecialchars($valKat, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($kategori == $valKat) ? 'selected' : ''); ?>>
                                            <?php echo htmlspecialchars($valKat, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <label class="d-block">&nbsp;</label>
                        <button type="button" class="btn btn-success" style="white-space: nowrap;" data-toggle="modal" data-target="#modalBarangTambahKategori">Tambah Kategori</button>
                    </div>
                    <div class="flex-shrink-0" style="width: 160px;">
                        <label for="modal_kode_barang" class="d-block">Kode <small class="text-muted">(opsional)</small></label>
                        <input type="text" class="form-control" name="kode_barang" id="modal_kode_barang" placeholder="Kode" value="<?php echo htmlspecialchars($kode_barang, ENT_QUOTES, 'UTF-8'); ?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="modal_nama_barang">Nama</label>
                <textarea class="form-control" rows="2" name="nama_barang" id="modal_nama_barang" placeholder="Nama" required autocomplete="off"><?php echo htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8'); ?></textarea>
                <small class="text-muted">Setelah mengisi nama lalu pindah ke field lain, sistem mengecek persediaan bulan <em>Tgl PO</em>.</small>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="modal_satuan_barang">Satuan</label>
                <input type="text" class="form-control" name="satuan" id="modal_satuan_barang" placeholder="Satuan" value="<?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="modal_harga_satuan_barang">Harga Satuan (HPP)</label>
                <input type="text" class="form-control" name="harga_satuan" id="modal_harga_satuan_barang" placeholder="HPP" value="" autocomplete="off" />
            </div>
        </div>
    </div>

    <input type="hidden" name="uuid_barang_referensi" id="modal_uuid_barang_referensi" value="" />
    <input type="hidden" name="persediaan_id_referensi" id="modal_persediaan_id_referensi" value="" />
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>" />

    <div class="text-right">
        <button type="button" class="btn btn-default" onclick="if (window.closeModalInputBarangBaruPembelian) { closeModalInputBarangBaruPembelian(); } return false;">Batal</button>
        <button type="submit" class="btn btn-primary" id="btn-submit-input-barang-baru"><?php echo $button; ?></button>
    </div>
</form>

<div class="modal fade" id="modalBarangTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalBarangTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangTambahKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="modal_kategori_baru_input">Kategori</label>
                    <input type="text" id="modal_kategori_baru_input" class="form-control" placeholder="Contoh: Kertas">
                    <small id="modal_kategori_baru_info" class="form-text text-muted"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn_simpan_kategori_baru_modal">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBarangDaftarKategori" tabindex="-1" role="dialog" aria-labelledby="modalBarangDaftarKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalBarangDaftarKategoriLabel">Kategori Sudah Ada - Pilih Salah Satu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-2">Kategori yang Anda input sudah tersedia. Pilih kategori berikut agar tidak duplikasi.</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" id="tabel_kategori_duplikat_modal">
                        <thead>
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Kategori</th>
                                <th style="width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function ensureModalKategoriSelected(val) {
            var select = document.getElementById('modal_kategori');
            if (!select || !val) return;
            var exists = false;
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value === val) {
                    exists = true;
                    break;
                }
            }
            if (!exists) {
                var opt = document.createElement('option');
                opt.value = val;
                opt.text = val;
                select.appendChild(opt);
            }
            select.value = val;
            if (window.jQuery) {
                window.jQuery(select).trigger('change');
            }
        }

        function renderModalDuplicateTable(matches) {
            var tbody = document.querySelector('#tabel_kategori_duplikat_modal tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            (matches || []).forEach(function(row, idx) {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (row.kategori || '') + '</td>' +
                    '<td><button type="button" class="btn btn-primary btn-sm btn-pilih-kategori-modal" data-kategori="' + (row.kategori || '') + '">Pilih</button></td>';
                tbody.appendChild(tr);
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn_simpan_kategori_baru_modal') {
                var btn = e.target;
                var info = document.getElementById('modal_kategori_baru_info');
                var input = document.getElementById('modal_kategori_baru_input');
                var namaKategori = (input.value || '').trim();
                if (namaKategori === '') {
                    info.textContent = 'Kategori wajib diisi.';
                    info.classList.remove('text-success');
                    info.classList.add('text-danger');
                    return;
                }

                btn.disabled = true;
                info.textContent = 'Menyimpan...';
                info.classList.remove('text-danger', 'text-success');

                var body = new URLSearchParams();
                body.append('kategori', namaKategori);

                fetch("<?php echo site_url('persediaan/add_kategori_ajax'); ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: body.toString()
                }).then(function(r) {
                    return r.json();
                }).then(function(res) {
                    var kategoriPakai = (res && res.data && res.data.kategori) ? res.data.kategori : namaKategori;

                    if (res && res.exists && kategoriPakai) {
                        ensureModalKategoriSelected(kategoriPakai);
                        info.textContent = res.message || 'Kategori sudah ada di sistem, silahkan digunakan.';
                        info.classList.remove('text-danger', 'text-success');
                        info.classList.add('text-warning');
                        if (window.jQuery) {
                            window.setTimeout(function() {
                                window.jQuery('#modalBarangTambahKategori').modal('hide');
                            }, 1200);
                        }
                        return;
                    }

                    if (res && res.success && kategoriPakai) {
                        ensureModalKategoriSelected(kategoriPakai);
                        input.value = '';
                        info.textContent = res.message || 'Kategori berhasil disimpan dan siap digunakan.';
                        info.classList.remove('text-danger', 'text-warning');
                        info.classList.add('text-success');
                        if (window.jQuery) {
                            window.setTimeout(function() {
                                window.jQuery('#modalBarangTambahKategori').modal('hide');
                            }, 800);
                        }
                        return;
                    }

                    if (res && res.duplicate && kategoriPakai) {
                        ensureModalKategoriSelected(kategoriPakai);
                        info.textContent = res.message || 'Kategori sudah ada di sistem, silahkan digunakan.';
                        info.classList.remove('text-success', 'text-danger');
                        info.classList.add('text-warning');
                        if (window.jQuery) {
                            window.setTimeout(function() {
                                window.jQuery('#modalBarangTambahKategori').modal('hide');
                            }, 1200);
                        }
                        return;
                    }

                    info.textContent = (res && res.message) ? res.message : 'Gagal menambah kategori.';
                    info.classList.remove('text-success', 'text-warning');
                    info.classList.add('text-danger');
                }).catch(function() {
                    info.textContent = 'Terjadi kesalahan saat menyimpan kategori.';
                    info.classList.remove('text-success');
                    info.classList.add('text-danger');
                }).finally(function() {
                    btn.disabled = false;
                });
            }

            if (e.target && e.target.classList.contains('btn-pilih-kategori-modal')) {
                var val = e.target.getAttribute('data-kategori') || '';
                ensureModalKategoriSelected(val);
                if (window.jQuery) {
                    window.jQuery('#modalBarangDaftarKategori').modal('hide');
                    window.jQuery('#modalBarangTambahKategori').modal('hide');
                }
            }
        });

        if (window.jQuery) {
            window.jQuery('#modalBarangTambahKategori').on('shown.bs.modal', function() {
                window.jQuery('#modal_kategori_baru_input').trigger('focus');
            }).on('hidden.bs.modal', function() {
                window.jQuery('#modal_kategori_baru_input').val('');
                window.jQuery('#modal_kategori_baru_info').text('').removeClass('text-danger text-success text-warning');
            });
        }

        function formatHppModal(value) {
            if (window.formatHargaSatuanPembelian) {
                return window.formatHargaSatuanPembelian(value);
            }
            if (value === null || typeof value === 'undefined' || value === '') {
                return '';
            }
            var angka = String(value).replace(/[^0-9]/g, '');
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        document.addEventListener('input', function(e) {
            if (e.target && e.target.id === 'modal_harga_satuan_barang' && window.applyFormatHargaSatuanPembelianModal) {
                window.applyFormatHargaSatuanPembelianModal(e.target);
            }
        });

        if (window.initSelect2KategoriModal) {
            setTimeout(window.initSelect2KategoriModal, 0);
        }
        if (window.initCekNamaBarangModalInput) {
            setTimeout(window.initCekNamaBarangModalInput, 0);
        }

        if (window.jQuery && typeof window.aktifkanSemuaInputModalBarangBaru === 'function') {
            setTimeout(window.aktifkanSemuaInputModalBarangBaru, 0);
        } else if (window.jQuery) {
            setTimeout(function() {
                jQuery('#form-input-barang-baru-modal').find('input, textarea, select, button').each(function() {
                    var $el = jQuery(this);
                    if ($el.attr('type') === 'hidden') {
                        return;
                    }
                    $el.prop('disabled', false).prop('readonly', false);
                });
            }, 0);
        }
    })();
</script>
