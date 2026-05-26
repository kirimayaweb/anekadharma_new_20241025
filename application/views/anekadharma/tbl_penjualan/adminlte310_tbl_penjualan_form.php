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




<style type="text/css">
    #dt_tgl_jual .form-control {
        background-color: #fff;
        cursor: text;
    }
</style>
<script>
window.addEventListener('load', function() {
    if (!window.jQuery || typeof jQuery.fn.datetimepicker !== 'function') {
        console.error('Datepicker Tgl Jual: jQuery / Tempusdominus belum dimuat.');
        return;
    }
    var $ = window.jQuery;

    function getTglJualVal() {
        return $.trim($('#input_tgl_jual').val() || '');
    }

    function updateBtnInputBarangPenjualan() {
        var tglJual = getTglJualVal();
        var $btn = $('#btn-input-barang-penjualan');
        var boleh = tglJual !== '';
        $btn.prop('disabled', !boleh);
        if (boleh) {
            $btn.removeAttr('title');
            $('#info-tgl-jual-penjualan').removeClass('text-danger').addClass('text-muted')
                .text('Tanggal jual default: hari ini. Ubah sesuai kebutuhan sebelum klik Input Barang Penjualan.');
        } else {
            $btn.attr('title', 'Isi Tgl Jual terlebih dahulu');
            $('#info-tgl-jual-penjualan').removeClass('text-muted').addClass('text-danger')
                .text('Tgl Jual wajib diisi sebelum klik Input Barang Penjualan.');
        }
    }

    function initDatepickerTglJual() {
        var $picker = $('#dt_tgl_jual');
        var $input = $('#input_tgl_jual');
        if (!$picker.length || !$input.length) {
            return;
        }

        $input.prop('readonly', false);

        if ($picker.data('DateTimePicker')) {
            try {
                $picker.datetimepicker('destroy');
            } catch (eDestroy) { /* abaikan */ }
        }

        $picker.datetimepicker({
            format: 'DD-MM-YYYY',
            useCurrent: false,
            allowInputToggle: true
        });

        var valAwal = $.trim($input.val());
        if (valAwal && typeof moment !== 'undefined') {
            var mAwal = moment(valAwal, ['DD-MM-YYYY', 'D-M-YYYY', 'DD-M-YYYY'], true);
            if (mAwal.isValid()) {
                $picker.datetimepicker('date', mAwal);
                $input.val(mAwal.format('DD-MM-YYYY'));
            }
        }

        $picker.off('change.datetimepicker.tglJualCreate hide.datetimepicker.tglJualCreate')
            .on('change.datetimepicker.tglJualCreate hide.datetimepicker.tglJualCreate', function() {
                updateBtnInputBarangPenjualan();
            });

        $input.off('change.tglJualCreate blur.tglJualCreate keyup.tglJualCreate')
            .on('change.tglJualCreate blur.tglJualCreate keyup.tglJualCreate', function() {
                updateBtnInputBarangPenjualan();
            });
    }

    initDatepickerTglJual();
    updateBtnInputBarangPenjualan();

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
        $('#input_tgl_jual').focus();
        return false;
    });
});
</script>