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
                                        <div class="col-12" text-align="center"> <strong>DATA KONSUMEN</strong></div>
                                    </div>
                                    <div class="col-6">
                                        <?php echo anchor(site_url('Sys_konsumen/create'), 'Tambah Konsumen Baru', 'class="btn btn-danger"'); 
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

                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align:center" width="80px">No</th>
                                                            <th width="120px" style="text-align:center">Action</th>
                                                            <th style="text-align:left">Kode Konsumen</th>
                                                            <th style="text-align:left">Nama Konsumen</th>
                                                            <th style="text-align:left">Nmr Kontak</th>
                                                            <th style="text-align:left">Alamat</th>
                                                            <th style="text-align:left">Keterangan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $start = 0;

                                                        foreach ($data_konsumen as $list_data) {
                                                        ?>
                                                            <tr>
                                                                <td style="text-align:center"><?php echo ++$start ?></td>
                                                                <td style="text-align:center">
                                                                    <button type="button"
                                                                        class="btn btn-warning btn-sm btn-ubah-konsumen"
                                                                        data-id="<?php echo (int) $list_data->id; ?>"
                                                                        data-kode="<?php echo htmlspecialchars($list_data->kode_konsumen, ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-nama="<?php echo htmlspecialchars($list_data->nama_konsumen, ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-kontak="<?php echo htmlspecialchars($list_data->nmr_kontak_konsumen, ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-alamat="<?php echo htmlspecialchars($list_data->alamat_konsumen, ENT_QUOTES, 'UTF-8'); ?>"
                                                                        data-keterangan="<?php echo htmlspecialchars($list_data->keterangan, ENT_QUOTES, 'UTF-8'); ?>">
                                                                        Ubah
                                                                    </button>
                                                                </td>
                                                                <td style="text-align:left"><?php echo $list_data->kode_konsumen ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nama_konsumen ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->nmr_kontak_konsumen ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->alamat_konsumen ?> </td>
                                                                <td style="text-align:left"><?php echo $list_data->keterangan ?> </td>
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

<!-- Modal Ubah Konsumen -->
<div class="modal fade" id="modal-ubah-konsumen" tabindex="-1" role="dialog" aria-labelledby="modalUbahKonsumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalUbahKonsumenLabel">Ubah Data Konsumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-ubah-konsumen">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_kode_konsumen">Kode Konsumen</label>
                        <input type="text" class="form-control" name="kode_konsumen" id="edit_kode_konsumen">
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_konsumen">Nama Konsumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_konsumen" id="edit_nama_konsumen" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nmr_kontak">Nmr Kontak</label>
                        <input type="text" class="form-control" name="nmr_kontak_konsumen" id="edit_nmr_kontak">
                    </div>
                    <div class="form-group mb-2">
                        <label for="edit_alamat">Alamat</label>
                        <textarea class="form-control input-satubar" name="alamat_konsumen" id="edit_alamat" rows="1"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea class="form-control input-satubar" name="keterangan" id="edit_keterangan" rows="1"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="btn-simpan-ubah-konsumen">Simpan</button>
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

    #modal-ubah-konsumen .input-satubar {
        height: calc(1.5em + 0.75rem + 2px);
        min-height: unset;
        resize: none;
        overflow-y: auto;
        line-height: 1.5;
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }

    #modal-ubah-konsumen .modal-body {
        padding-bottom: 0.5rem;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    var tableKonsumen;
    var urlUpdateKonsumen = '<?php echo site_url('Sys_konsumen/update_action_ajax'); ?>';

    $(document).ready(function() {
        tableKonsumen = $('#example').DataTable({
            scrollY: 300,
            scrollX: true,
            columnDefs: [
                { orderable: false, targets: 1 }
            ]
        });

        $(document).on('click', '.btn-ubah-konsumen', function() {
            var btn = $(this);
            $('#edit_id').val(btn.data('id'));
            $('#edit_kode_konsumen').val(btn.data('kode'));
            $('#edit_nama_konsumen').val(btn.data('nama'));
            $('#edit_nmr_kontak').val(btn.data('kontak'));
            $('#edit_alamat').val(btn.data('alamat'));
            $('#edit_keterangan').val(btn.data('keterangan'));
            $('#modal-ubah-konsumen').modal('show');
        });

        $('#form-ubah-konsumen').on('submit', function(e) {
            e.preventDefault();

            var nama = $.trim($('#edit_nama_konsumen').val());
            if (nama === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Nama konsumen wajib diisi.'
                });
                return;
            }

            $('#btn-simpan-ubah-konsumen').prop('disabled', true);

            $.ajax({
                url: urlUpdateKonsumen,
                type: 'POST',
                dataType: 'json',
                data: $(this).serialize(),
                success: function(res) {
                    $('#btn-simpan-ubah-konsumen').prop('disabled', false);
                    $('#modal-ubah-konsumen').modal('hide');

                    if (res && res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message || 'Data konsumen berhasil diperbarui.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: true,
                            allowOutsideClick: false
                        }).then(function() {
                            window.location.href = '<?php echo site_url('Sys_konsumen'); ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: (res && res.message) ? res.message : 'Gagal memperbarui data konsumen.'
                        });
                    }
                },
                error: function() {
                    $('#btn-simpan-ubah-konsumen').prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat menghubungi server.'
                    });
                }
            });
        });
    });
</script>
