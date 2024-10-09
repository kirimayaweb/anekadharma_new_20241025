<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Tbl_neraca_data <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="datetime">Date Input <?php echo form_error('date_input') ?></label>
            <input type="text" class="form-control" name="date_input" id="date_input" placeholder="Date Input" value="<?php echo $date_input; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Date Transaksi <?php echo form_error('date_transaksi') ?></label>
            <input type="text" class="form-control" name="date_transaksi" id="date_transaksi" placeholder="Date Transaksi" value="<?php echo $date_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Tahun Transaksi <?php echo form_error('tahun_transaksi') ?></label>
            <input type="text" class="form-control" name="tahun_transaksi" id="tahun_transaksi" placeholder="Tahun Transaksi" value="<?php echo $tahun_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Bulan Transaksi <?php echo form_error('bulan_transaksi') ?></label>
            <input type="text" class="form-control" name="bulan_transaksi" id="bulan_transaksi" placeholder="Bulan Transaksi" value="<?php echo $bulan_transaksi; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Uuid Data Neraca <?php echo form_error('uuid_data_neraca') ?></label>
            <input type="text" class="form-control" name="uuid_data_neraca" id="uuid_data_neraca" placeholder="Uuid Data Neraca" value="<?php echo $uuid_data_neraca; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Kas <?php echo form_error('kas') ?></label>
            <input type="text" class="form-control" name="kas" id="kas" placeholder="Kas" value="<?php echo $kas; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Bank <?php echo form_error('bank') ?></label>
            <input type="text" class="form-control" name="bank" id="bank" placeholder="Bank" value="<?php echo $bank; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Piutang Usaha <?php echo form_error('piutang_usaha') ?></label>
            <input type="text" class="form-control" name="piutang_usaha" id="piutang_usaha" placeholder="Piutang Usaha" value="<?php echo $piutang_usaha; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Piutang Non Usaha <?php echo form_error('piutang_non_usaha') ?></label>
            <input type="text" class="form-control" name="piutang_non_usaha" id="piutang_non_usaha" placeholder="Piutang Non Usaha" value="<?php echo $piutang_non_usaha; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Persediaan <?php echo form_error('persediaan') ?></label>
            <input type="text" class="form-control" name="persediaan" id="persediaan" placeholder="Persediaan" value="<?php echo $persediaan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Uang Muka Pajak <?php echo form_error('uang_muka_pajak') ?></label>
            <input type="text" class="form-control" name="uang_muka_pajak" id="uang_muka_pajak" placeholder="Uang Muka Pajak" value="<?php echo $uang_muka_pajak; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Aktiva Tetap <?php echo form_error('aktiva_tetap') ?></label>
            <input type="text" class="form-control" name="aktiva_tetap" id="aktiva_tetap" placeholder="Aktiva Tetap" value="<?php echo $aktiva_tetap; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Aktiva Tetap Berwujud <?php echo form_error('aktiva_tetap_berwujud') ?></label>
            <input type="text" class="form-control" name="aktiva_tetap_berwujud" id="aktiva_tetap_berwujud" placeholder="Aktiva Tetap Berwujud" value="<?php echo $aktiva_tetap_berwujud; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Akumulasi Depresiasi Atb <?php echo form_error('akumulasi_depresiasi_atb') ?></label>
            <input type="text" class="form-control" name="akumulasi_depresiasi_atb" id="akumulasi_depresiasi_atb" placeholder="Akumulasi Depresiasi Atb" value="<?php echo $akumulasi_depresiasi_atb; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Piutang Non Usaha Pihak Ketiga <?php echo form_error('piutang_non_usaha_pihak_ketiga') ?></label>
            <input type="text" class="form-control" name="piutang_non_usaha_pihak_ketiga" id="piutang_non_usaha_pihak_ketiga" placeholder="Piutang Non Usaha Pihak Ketiga" value="<?php echo $piutang_non_usaha_pihak_ketiga; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Piutang Non Usaha Radio <?php echo form_error('piutang_non_usaha_radio') ?></label>
            <input type="text" class="form-control" name="piutang_non_usaha_radio" id="piutang_non_usaha_radio" placeholder="Piutang Non Usaha Radio" value="<?php echo $piutang_non_usaha_radio; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj-taman Gedung Kesenian Gabusan <?php echo form_error('ljpj_taman_gedung_kesenian_gabusan') ?></label>
            <input type="text" class="form-control" name="ljpj_taman_gedung_kesenian_gabusan" id="ljpj_taman_gedung_kesenian_gabusan" placeholder="Ljpj-taman Gedung Kesenian Gabusan" value="<?php echo $ljpj_taman_gedung_kesenian_gabusan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Kompleks Gedung Kesenian <?php echo form_error('ljpj_kompleks_gedung_kesenian') ?></label>
            <input type="text" class="form-control" name="ljpj_kompleks_gedung_kesenian" id="ljpj_kompleks_gedung_kesenian" placeholder="Ljpj Kompleks Gedung Kesenian" value="<?php echo $ljpj_kompleks_gedung_kesenian; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Radio <?php echo form_error('ljpj_radio') ?></label>
            <input type="text" class="form-control" name="ljpj_radio" id="ljpj_radio" placeholder="Ljpj Radio" value="<?php echo $ljpj_radio; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Kerjasama Operasi Apotek Dharma Usaha <?php echo form_error('ljpj_kerjasama_operasi_apotek_dharma_usaha') ?></label>
            <input type="text" class="form-control" name="ljpj_kerjasama_operasi_apotek_dharma_usaha" id="ljpj_kerjasama_operasi_apotek_dharma_usaha" placeholder="Ljpj Kerjasama Operasi Apotek Dharma Usaha" value="<?php echo $ljpj_kerjasama_operasi_apotek_dharma_usaha; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Peternakan <?php echo form_error('ljpj_peternakan') ?></label>
            <input type="text" class="form-control" name="ljpj_peternakan" id="ljpj_peternakan" placeholder="Ljpj Peternakan" value="<?php echo $ljpj_peternakan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Kerjasama Adwm <?php echo form_error('ljpj_kerjasama_adwm') ?></label>
            <input type="text" class="form-control" name="ljpj_kerjasama_adwm" id="ljpj_kerjasama_adwm" placeholder="Ljpj Kerjasama Adwm" value="<?php echo $ljpj_kerjasama_adwm; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Ljpj Kerjasama Pdu Cabean Panggungharjo <?php echo form_error('ljpj_kerjasama_pdu_cabean_panggungharjo') ?></label>
            <input type="text" class="form-control" name="ljpj_kerjasama_pdu_cabean_panggungharjo" id="ljpj_kerjasama_pdu_cabean_panggungharjo" placeholder="Ljpj Kerjasama Pdu Cabean Panggungharjo" value="<?php echo $ljpj_kerjasama_pdu_cabean_panggungharjo; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Utang Usaha <?php echo form_error('utang_usaha') ?></label>
            <input type="text" class="form-control" name="utang_usaha" id="utang_usaha" placeholder="Utang Usaha" value="<?php echo $utang_usaha; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Utang Pajak <?php echo form_error('utang_pajak') ?></label>
            <input type="text" class="form-control" name="utang_pajak" id="utang_pajak" placeholder="Utang Pajak" value="<?php echo $utang_pajak; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Utang Lain Lain <?php echo form_error('utang_lain_lain') ?></label>
            <input type="text" class="form-control" name="utang_lain_lain" id="utang_lain_lain" placeholder="Utang Lain Lain" value="<?php echo $utang_lain_lain; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Utang Afiliasi <?php echo form_error('utang_afiliasi') ?></label>
            <input type="text" class="form-control" name="utang_afiliasi" id="utang_afiliasi" placeholder="Utang Afiliasi" value="<?php echo $utang_afiliasi; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Modal Dasar Dan Penyertaan <?php echo form_error('modal_dasar_dan_penyertaan') ?></label>
            <input type="text" class="form-control" name="modal_dasar_dan_penyertaan" id="modal_dasar_dan_penyertaan" placeholder="Modal Dasar Dan Penyertaan" value="<?php echo $modal_dasar_dan_penyertaan; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Cadangan Umum <?php echo form_error('cadangan_umum') ?></label>
            <input type="text" class="form-control" name="cadangan_umum" id="cadangan_umum" placeholder="Cadangan Umum" value="<?php echo $cadangan_umum; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Laba Bumd Pad <?php echo form_error('laba_bumd_pad') ?></label>
            <input type="text" class="form-control" name="laba_bumd_pad" id="laba_bumd_pad" placeholder="Laba Bumd Pad" value="<?php echo $laba_bumd_pad; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Laba Rugi Tahun Lalu <?php echo form_error('laba_rugi_tahun_lalu') ?></label>
            <input type="text" class="form-control" name="laba_rugi_tahun_lalu" id="laba_rugi_tahun_lalu" placeholder="Laba Rugi Tahun Lalu" value="<?php echo $laba_rugi_tahun_lalu; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Laba Rugi Tahun Berjalan <?php echo form_error('laba_rugi_tahun_berjalan') ?></label>
            <input type="text" class="form-control" name="laba_rugi_tahun_berjalan" id="laba_rugi_tahun_berjalan" placeholder="Laba Rugi Tahun Berjalan" value="<?php echo $laba_rugi_tahun_berjalan; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tbl_neraca_data') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>