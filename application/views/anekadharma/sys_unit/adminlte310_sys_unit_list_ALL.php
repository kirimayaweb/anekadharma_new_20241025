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
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="col-12" text-align="center"> <strong>DATA UNIT</strong></div>
                                    </div>
                                    <div class="col-6">
                                        <?php echo anchor(site_url('Sys_unit/create'), 'Tambah Unit Baru', 'class="btn btn-danger"'); 
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>




                    </div>
                    <!-- <br /> -->



                    <div class="card-body">




                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="card card-primary card-tabs">

                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-one-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">

                                                <div class="row">
                                                    <!-- <div class="col-1"></div> -->
                                                    <div class="col-6">
                                                        <?php //echo anchor(site_url('Sys_unit_produk/create_unit/'.$uuid_unit_selected), 'Input Hasil / Produk Unit: ' . $nama_unit, 'class="btn btn-success"'); 
                                                        ?>
                                                    </div>
                                                </div>

                                                <table id="example" class="display nowrap table-unit-left" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:left" width="60px">No</th>
                                                            <th style="text-align:left" width="160px">Action</th>
                                                            <th style="text-align:left">kode_unit</th>
                                                            <th style="text-align:left">nama_unit</th>
                                                            <th style="text-align:left">Alamat</th>
                                                            <th style="text-align:left">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;

                                                        foreach ($data_unit as $list_data) {
                                                            $alamat_val = isset($list_data->alamat) ? $list_data->alamat : '';
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:left"><?php echo ++$start ?></td>
                                                                <td style="text-align:left">
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm btn-ubah-unit"
                                                                        data-id="<?php echo (int) $list_data->id; ?>"
                                                                        title="Ubah">
                                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-hapus-unit"
                                                                        data-id="<?php echo (int) $list_data->id; ?>"
                                                                        data-nama="<?php echo htmlspecialchars(isset($list_data->nama_unit) ? $list_data->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?>"
                                                                        title="Hapus">
                                                                        <i class="fa fa-trash-o" aria-hidden="true"></i> Hapus
                                                                    </button>
                                                                </td>
                                                                <td style="text-align:left"><?php echo htmlspecialchars(isset($list_data->kode_unit) ? $list_data->kode_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td style="text-align:left"><?php echo htmlspecialchars(isset($list_data->nama_unit) ? $list_data->nama_unit : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td style="text-align:left"><?php echo htmlspecialchars($alamat_val, ENT_QUOTES, 'UTF-8'); ?></td>
                                                                <td style="text-align:left"><?php echo htmlspecialchars(isset($list_data->keterangan) ? $list_data->keterangan : '', ENT_QUOTES, 'UTF-8'); ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>

                                                </table>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card -->
                                </div>
                            </div>

                        </div>



                    </div>
                    <!-- /.card-body -->
                </div>
            </div>





        </div>




    </section>
</div>

<!-- Modal Ubah Data Unit -->
<div class="modal fade" id="modal-ubah-unit" tabindex="-1" role="dialog" aria-labelledby="modalUbahUnitLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-ubah-unit" autocomplete="off">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalUbahUnitLabel">Ubah Data Unit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="ubah_unit_id" value="">
                    <div class="form-group">
                        <label for="ubah_kode_unit">Kode Unit</label>
                        <input type="text" class="form-control" name="kode_unit" id="ubah_kode_unit" placeholder="Kode Unit">
                    </div>
                    <div class="form-group">
                        <label for="ubah_nama_unit">Nama Unit <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_unit" id="ubah_nama_unit" placeholder="Nama Unit" required>
                    </div>
                    <div class="form-group">
                        <label for="ubah_alamat">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat" id="ubah_alamat" rows="3" placeholder="Alamat unit" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ubah_keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="ubah_keterangan" rows="3" placeholder="Keterangan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan-ubah-unit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
    table.table-unit-left th,
    table.table-unit-left td {
        text-align: left !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    (function() {
        var urlAjaxGet = <?php echo json_encode(site_url('Sys_unit/ajax_get')); ?>;
        var urlAjaxUpdate = <?php echo json_encode(site_url('Sys_unit/ajax_update')); ?>;
        var urlAjaxDelete = <?php echo json_encode(site_url('Sys_unit/ajax_delete')); ?>;

        $(document).ready(function() {
            $('#example').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                "scrollY": "420px",
                "scrollX": true,
                "scrollCollapse": true,
                "columnDefs": [
                    { "className": "dt-left", "targets": "_all" }
                ]
            });
        });

        function showSuccessAutoClose(title, text, onDone) {
            if (typeof Swal === 'undefined') {
                alert(text || title);
                if (typeof onDone === 'function') {
                    onDone();
                }
                return;
            }
            Swal.fire({
                icon: 'success',
                title: title || 'Berhasil',
                text: text || '',
                confirmButtonText: 'OK',
                timer: 2000,
                timerProgressBar: true
            }).then(function() {
                if (typeof onDone === 'function') {
                    onDone();
                }
            });
        }

        $(document).on('click', '.btn-ubah-unit', function() {
            var id = parseInt($(this).data('id'), 10) || 0;
            if (id <= 0) {
                return;
            }

            $.ajax({
                url: urlAjaxGet,
                type: 'GET',
                dataType: 'json',
                data: { id: id }
            }).done(function(res) {
                if (!res || !res.ok || !res.data) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Data unit tidak ditemukan.' });
                    } else {
                        alert((res && res.message) ? res.message : 'Data unit tidak ditemukan.');
                    }
                    return;
                }

                var d = res.data;
                $('#ubah_unit_id').val(d.id || '');
                $('#ubah_kode_unit').val(d.kode_unit || '');
                $('#ubah_nama_unit').val(d.nama_unit || '');
                $('#ubah_alamat').val(d.alamat || '');
                $('#ubah_keterangan').val(d.keterangan || '');
                $('#modal-ubah-unit').modal('show');
            }).fail(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Gagal memuat data unit.' });
                } else {
                    alert('Gagal memuat data unit.');
                }
            });
        });

        $('#form-ubah-unit').on('submit', function(e) {
            e.preventDefault();

            var nama = $.trim($('#ubah_nama_unit').val());
            var alamat = $.trim($('#ubah_alamat').val());

            if (!nama) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Nama unit wajib diisi.' });
                } else {
                    alert('Nama unit wajib diisi.');
                }
                return false;
            }

            if (!alamat) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Data belum lengkap', text: 'Alamat wajib diisi sebelum disimpan.' });
                } else {
                    alert('Alamat wajib diisi sebelum disimpan.');
                }
                $('#ubah_alamat').focus();
                return false;
            }

            var $btn = $('#btn-simpan-ubah-unit');
            var btnText = $btn.text();
            $btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: urlAjaxUpdate,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize()
            }).done(function(res) {
                if (res && res.ok) {
                    $('#modal-ubah-unit').modal('hide');
                    showSuccessAutoClose('Berhasil', res.message || 'Data unit berhasil diubah.', function() {
                        window.location.reload();
                    });
                    return;
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal menyimpan data unit.' });
                } else {
                    alert((res && res.message) ? res.message : 'Gagal menyimpan data unit.');
                }
            }).fail(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menyimpan data.' });
                } else {
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            }).always(function() {
                $btn.prop('disabled', false).text(btnText);
            });
        });

        $(document).on('click', '.btn-hapus-unit', function() {
            var id = parseInt($(this).data('id'), 10) || 0;
            var nama = $(this).data('nama') || '';
            if (id <= 0) {
                return;
            }

            var doDelete = function() {
                $.ajax({
                    url: urlAjaxDelete,
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id }
                }).done(function(res) {
                    if (res && res.ok) {
                        showSuccessAutoClose('Berhasil', res.message || 'Proses hapus unit berhasil', function() {
                            window.location.reload();
                        });
                        return;
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak dapat dihapus',
                            text: (res && res.message) ? res.message : 'Gagal menghapus data unit.'
                        });
                    } else {
                        alert((res && res.message) ? res.message : 'Gagal menghapus data unit.');
                    }
                }).fail(function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Terjadi kesalahan saat menghapus data.' });
                    } else {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            };

            if (typeof Swal === 'undefined') {
                if (confirm('Apakah yakin akan menghapus data unit ini?')) {
                    doDelete();
                }
                return;
            }

            Swal.fire({
                icon: 'error',
                title: 'Konfirmasi Hapus',
                html: 'Apakah yakin akan menghapus data unit ini?' + (nama ? ('<br><strong>' + $('<div>').text(nama).html() + '</strong>') : ''),
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    doDelete();
                }
            });
        });
    })();
</script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 900,
            "scrollX": true
        });
    });
</script>
