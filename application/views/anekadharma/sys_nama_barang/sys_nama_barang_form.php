<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">





        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-warning">

                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Input Data Barang
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <div class="form-group">


                            <!--  -->

                            <!-- <h2 style="margin-top:0px">Sys_nama_barang <?php //echo $button 
                                                                            ?></h2> -->
                            <form action="<?php echo $action; ?>" method="post">
                                <!-- <div class="form-group">
                                    <label for="varchar">Uuid Barang <?php //echo form_error('uuid_barang') 
                                                                        ?></label>
                                    <input type="text" class="form-control" name="uuid_barang" id="uuid_barang" placeholder="Uuid Barang" value="<?php //echo $uuid_barang; ?>" />
                                </div> -->
                                <div class="form-group">
                                    <label for="kode_barang">Kode Barang <?php echo form_error('kode_barang') ?></label>
                                    <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori Barang</label>
                                    <div class="input-group">
                                        <select class="form-control" name="kategori" id="kategori">
                                            <option value="">-- Pilih Kategori --</option>
                                            <?php if (!empty($kategori_barang_options)) { ?>
                                                <?php foreach ($kategori_barang_options as $kat) { ?>
                                                    <?php $valKat = isset($kat->kategori) ? $kat->kategori : ''; ?>
                                                    <option value="<?php echo $valKat; ?>" <?php echo (($kategori == $valKat) ? 'selected' : ''); ?>>
                                                        <?php echo $valKat; ?>
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalTambahKategori">Tambah Kategori</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang <?php echo form_error('nama_barang') ?></label>
                                    <textarea class="form-control" rows="3" name="nama_barang" id="nama_barang" placeholder="Nama Barang"><?php echo $nama_barang; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan <?php echo form_error('satuan') ?></label>
                                    <textarea class="form-control" rows="3" name="satuan" id="satuan" placeholder="Satuan"><?php echo $satuan; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
                                    <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <?php
                                if ($source_form == "pembelian") {
                                ?>
                                    <a href="<?php echo site_url('Tbl_pembelian/create') ?>" class="btn btn-default">Cancel</a>
                                <?php
                                } else {
                                ?>
                                    <a href="<?php echo site_url('sys_nama_barang') ?>" class="btn btn-default">Cancel</a>
                                <?php
                                }
                                ?>

                            </form>


                            <!--  -->



                        </div>
                    </div>




                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTambahKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label for="kategori_baru_input">Nama Kategori</label>
                            <input type="text" id="kategori_baru_input" class="form-control" placeholder="Contoh: Kertas">
                            <small id="kategori_baru_info" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="btn_simpan_kategori_baru">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalDaftarKategori" tabindex="-1" role="dialog" aria-labelledby="modalDaftarKategoriLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="modalDaftarKategoriLabel">Kategori Sudah Ada - Pilih Salah Satu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning mb-2" id="kategori_duplikat_info">Kategori yang Anda input sudah tersedia. Pilih kategori berikut agar tidak duplikasi.</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="tabel_kategori_duplikat">
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
    </section>
</div>

<script>
    (function() {
        function ensureOptionSelected(val) {
            var select = document.getElementById('kategori');
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

        function renderDuplicateTable(matches) {
            var tbody = document.querySelector('#tabel_kategori_duplikat tbody');
            if (!tbody) return;
            tbody.innerHTML = '';
            (matches || []).forEach(function(row, idx) {
                var tr = document.createElement('tr');
                tr.innerHTML =
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (row.kategori || '') + '</td>' +
                    '<td><button type="button" class="btn btn-primary btn-sm btn-pilih-kategori" data-kategori="' + (row.kategori || '') + '">Pilih</button></td>';
                tbody.appendChild(tr);
            });
        }

        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn_simpan_kategori_baru') {
                var btn = e.target;
                var info = document.getElementById('kategori_baru_info');
                var input = document.getElementById('kategori_baru_input');
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
                        ensureOptionSelected(res.data.kategori);
                        input.value = '';
                        info.textContent = res.message || 'Kategori berhasil ditambahkan.';
                        info.classList.remove('text-danger');
                        info.classList.add('text-success');
                        if (window.jQuery) {
                            window.jQuery('#modalTambahKategori').modal('hide');
                        }
                        return;
                    }

                    if (res && res.duplicate) {
                        info.textContent = res.message || 'Kategori sudah ada.';
                        info.classList.remove('text-success');
                        info.classList.add('text-danger');
                        renderDuplicateTable(res.matches || []);
                        if (window.jQuery) {
                            window.jQuery('#modalDaftarKategori').modal('show');
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

            if (e.target && e.target.classList.contains('btn-pilih-kategori')) {
                var val = e.target.getAttribute('data-kategori') || '';
                ensureOptionSelected(val);
                if (window.jQuery) {
                    window.jQuery('#modalDaftarKategori').modal('hide');
                    window.jQuery('#modalTambahKategori').modal('hide');
                }
            }
        });

        if (window.jQuery) {
            window.jQuery('#modalTambahKategori').on('shown.bs.modal', function() {
                window.jQuery('#kategori_baru_input').trigger('focus');
            }).on('hidden.bs.modal', function() {
                window.jQuery('#kategori_baru_input').val('');
                window.jQuery('#kategori_baru_info').text('').removeClass('text-danger text-success');
            });
        }
    })();
</script>