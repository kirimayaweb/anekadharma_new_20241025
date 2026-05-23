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
                                            Input Penjualan
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <!-- <form action="<?php //echo $action; 
                                            ?>" method="post"> -->
                        <form action="create_action_inisiasi/new" method="post" id="form-penjualan-create-inisiasi">




                            <div class="form-group">
                                <label for="input_tgl_jual">Tgl Jual <?php echo form_error('tgl_jual') ?></label>
                                <div class="col-4">
                                    <?php
                                    $tgl_jual_tampil = isset($tgl_jual) && $tgl_jual !== '' ? $tgl_jual : date('d-m-Y');
                                    ?>
                                    <div class="input-group date" id="dt_tgl_jual" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#dt_tgl_jual" id="input_tgl_jual" name="tgl_jual" value="<?php echo htmlspecialchars($tgl_jual_tampil, ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off" />
                                        <div class="input-group-append" data-target="#dt_tgl_jual" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" id="info-tgl-jual-penjualan">
                                    Tanggal jual default: hari ini. Ubah sesuai kebutuhan sebelum klik Input Barang Penjualan.
                                </small>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <!-- Unit -->
                                    <div class="col-3">
                                        <label for="unit_nama">Unit <?php echo form_error('unit') ?></label>
                                        <select name="uuid_unit" id="uuid_unit" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Unit</option>
                                            <?php

                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit)  . "</option>";
                                            }

                                            ?>
                                        </select>


                                    </div>


                                    <!-- Konsumen -->

                                    <div class="col-3">
                                        <label for="konsumen_nama">Konsumen <?php echo form_error('konsumen_nama') ?></label>
                                        <select name="uuid_konsumen" id="uuid_konsumen" class="form-control select2" style="width: 100%; height: 40px;" required>
                                            <option value="">Pilih Konsumen</option>
                                            <?php

                                            $sql = "select * from sys_unit order by nama_unit ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_unit' ";
                                                echo ">  " . strtoupper($m->nama_unit)  . "  ==> [UNIT] </option>";
                                            }

                                            $sql = "select * from sys_konsumen order by nama_konsumen ASC ";
                                            foreach ($this->db->query($sql)->result() as $m) {
                                                echo "<option value='$m->uuid_konsumen' ";
                                                echo ">  " . strtoupper($m->nama_konsumen) . " <strong> ==> (" . strtoupper($m->kelompok_dipersediaan) . ")</strong> " . strtoupper($m->alamat_konsumen) . "</option>";
                                            }
                                            ?>
                                        </select>


                                    </div>

                                    <div class="col-3">
                                        <label for="nmrpesan">Nomor Pesan <?php echo form_error('nmrpesan') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrpesan" id="nmrpesan" placeholder="nmrpesan" required>
                                    </div>

                                    <div class="col-3">
                                        <label for="nmrkirim">Nomor Kirim <?php echo form_error('nmrkirim') ?></label>
                                        <input type="text" class="form-control" rows="3" name="nmrkirim" id="nmrkirim" placeholder="nmrkirim" required>
                                    </div>


                                </div>

                            </div>



                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12" align="center">
                                        <button type="submit" class="btn btn-success" id="btn-input-barang-penjualan" title="Isi Tgl Jual terlebih dahulu">
                                            Input Barang Penjualan
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12" align="center">
                                        <!-- <input type="hidden" name="id" value="<?php //echo $id; 
                                                                                    ?>" /> -->
                                        <!-- <button type="submit" class="btn btn-primary"><?php //echo $button 
                                                                                            ?></button> -->
                                        <a href="<?php echo site_url('tbl_penjualan') ?>" class="btn btn-default">Cancel</a>
                                    </div>
                                </div>
                            </div>


                        </form>


                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>





<!-- ============== -->




<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>





<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#example9').DataTable({
            "scrollY": 500,
            "scrollX": true
        });
    });
</script>
<script>
    (function($) {
        function getTglJualVal() {
            return $.trim($('input[name="tgl_jual"]').val() || '');
        }

        function updateBtnInputBarangPenjualan() {
            var tglJual = getTglJualVal();
            var $btn = $('#btn-input-barang-penjualan');
            var boleh = tglJual !== '';
            $btn.prop('disabled', !boleh);
            if (boleh) {
                $btn.removeAttr('title');
                $('#info-tgl-jual-penjualan').removeClass('text-danger').addClass('text-muted');
            } else {
                $btn.attr('title', 'Isi Tgl Jual terlebih dahulu');
                $('#info-tgl-jual-penjualan').removeClass('text-muted').addClass('text-danger')
                    .text('Tgl Jual wajib diisi sebelum klik Input Barang Penjualan.');
            }
        }

        function initDatepickerTglJual() {
            var $picker = $('#dt_tgl_jual');
            if (!$picker.length || $picker.data('DateTimePicker')) {
                return;
            }
            $picker.datetimepicker({
                format: 'D-M-YYYY'
            });
            $picker.on('change.datetimepicker', function() {
                updateBtnInputBarangPenjualan();
            });
        }

        $(document).ready(function() {
            initDatepickerTglJual();
            updateBtnInputBarangPenjualan();

            $('input[name="tgl_jual"]').on('change blur keyup', function() {
                updateBtnInputBarangPenjualan();
            });
        });

        $('#form-penjualan-create-inisiasi').on('submit', function(e) {
            if (getTglJualVal()) {
                return true;
            }
            e.preventDefault();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tgl Jual belum diisi',
                    text: 'Pilih atau isi tanggal jual terlebih dahulu sebelum Input Barang Penjualan.'
                });
            } else {
                alert('Pilih atau isi Tgl Jual terlebih dahulu.');
            }
            $('input[name="tgl_jual"]').focus();
            return false;
        });
    })(jQuery);
</script>