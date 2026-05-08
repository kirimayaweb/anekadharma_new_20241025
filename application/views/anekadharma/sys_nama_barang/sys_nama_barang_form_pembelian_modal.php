<form action="<?php echo $action; ?>" method="post" id="form-input-barang-baru-modal">
    <div class="alert d-none" id="input_barang_baru_info"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="modal_kategori">Kategori Barang</label>
                <div class="input-group">
                    <select class="form-control" name="kategori" id="modal_kategori">
                        <option value="">-- Pilih Kategori --</option>
                        <?php if (!empty($kategori_barang_options)) { ?>
                            <?php foreach ($kategori_barang_options as $kat) { ?>
                                <?php $valKat = isset($kat->kategori) ? $kat->kategori : ''; ?>
                                <option value="<?php echo htmlspecialchars($valKat, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($kategori == $valKat) ? 'selected' : ''); ?>>
                                    <?php echo htmlspecialchars($valKat, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalBarangTambahKategori">Tambah Kategori</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="modal_kode_barang">Kode Barang</label>
                <input type="text" class="form-control" name="kode_barang" id="modal_kode_barang" placeholder="Kode Barang" value="<?php echo htmlspecialchars($kode_barang, ENT_QUOTES, 'UTF-8'); ?>" required />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="modal_nama_barang">Nama Barang</label>
                <textarea class="form-control" rows="2" name="nama_barang" id="modal_nama_barang" placeholder="Nama Barang" required><?php echo htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="modal_satuan_barang">Satuan</label>
                <input type="text" class="form-control" name="satuan" id="modal_satuan_barang" placeholder="Satuan" value="<?php echo htmlspecialchars($satuan, ENT_QUOTES, 'UTF-8'); ?>" />
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="modal_keterangan_barang">Keterangan</label>
        <textarea class="form-control" rows="2" name="keterangan" id="modal_keterangan_barang" placeholder="Keterangan"><?php echo htmlspecialchars($keterangan, ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

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
                <h5 class="modal-title" id="modalBarangTambahKategoriLabel">Tambah Kategori Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-0">
                    <label for="modal_kategori_baru_input">Nama Kategori</label>
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

                fetch("<?php echo site_url('sys_nama_barang/add_kategori_ajax'); ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: body.toString()
                }).then(function(r) {
                    return r.json();
                }).then(function(res) {
                    if (res && res.success && res.data && res.data.kategori) {
                        ensureModalKategoriSelected(res.data.kategori);
                        input.value = '';
                        info.textContent = res.message || 'Kategori berhasil ditambahkan.';
                        info.classList.remove('text-danger');
                        info.classList.add('text-success');
                        if (window.jQuery) {
                            window.jQuery('#modalBarangTambahKategori').modal('hide');
                        }
                        return;
                    }

                    if (res && res.duplicate) {
                        info.textContent = res.message || 'Kategori sudah ada.';
                        info.classList.remove('text-success');
                        info.classList.add('text-danger');
                        renderModalDuplicateTable(res.matches || []);
                        if (window.jQuery) {
                            window.jQuery('#modalBarangDaftarKategori').modal('show');
                        }
                        return;
                    }

                    info.textContent = (res && res.message) ? res.message : 'Gagal menambah kategori.';
                    info.classList.remove('text-success');
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
                window.jQuery('#modal_kategori_baru_info').text('').removeClass('text-danger text-success');
            });
        }
    })();
</script>
