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
        <h2 style="margin-top:0px">Tbl_neraca_data Read</h2>
        <table class="table">
	    <tr><td>Date Input</td><td><?php echo $date_input; ?></td></tr>
	    <tr><td>Date Transaksi</td><td><?php echo $date_transaksi; ?></td></tr>
	    <tr><td>Tahun Transaksi</td><td><?php echo $tahun_transaksi; ?></td></tr>
	    <tr><td>Bulan Transaksi</td><td><?php echo $bulan_transaksi; ?></td></tr>
	    <tr><td>Uuid Data Neraca</td><td><?php echo $uuid_data_neraca; ?></td></tr>
	    <tr><td>Kas</td><td><?php echo $kas; ?></td></tr>
	    <tr><td>Bank</td><td><?php echo $bank; ?></td></tr>
	    <tr><td>Piutang Usaha</td><td><?php echo $piutang_usaha; ?></td></tr>
	    <tr><td>Piutang Non Usaha</td><td><?php echo $piutang_non_usaha; ?></td></tr>
	    <tr><td>Persediaan</td><td><?php echo $persediaan; ?></td></tr>
	    <tr><td>Uang Muka Pajak</td><td><?php echo $uang_muka_pajak; ?></td></tr>
	    <tr><td>Aktiva Tetap</td><td><?php echo $aktiva_tetap; ?></td></tr>
	    <tr><td>Aktiva Tetap Berwujud</td><td><?php echo $aktiva_tetap_berwujud; ?></td></tr>
	    <tr><td>Akumulasi Depresiasi Atb</td><td><?php echo $akumulasi_depresiasi_atb; ?></td></tr>
	    <tr><td>Piutang Non Usaha Pihak Ketiga</td><td><?php echo $piutang_non_usaha_pihak_ketiga; ?></td></tr>
	    <tr><td>Piutang Non Usaha Radio</td><td><?php echo $piutang_non_usaha_radio; ?></td></tr>
	    <tr><td>Ljpj-taman Gedung Kesenian Gabusan</td><td><?php echo $ljpj_taman_gedung_kesenian_gabusan; ?></td></tr>
	    <tr><td>Ljpj Kompleks Gedung Kesenian</td><td><?php echo $ljpj_kompleks_gedung_kesenian; ?></td></tr>
	    <tr><td>Ljpj Radio</td><td><?php echo $ljpj_radio; ?></td></tr>
	    <tr><td>Ljpj Kerjasama Operasi Apotek Dharma Usaha</td><td><?php echo $ljpj_kerjasama_operasi_apotek_dharma_usaha; ?></td></tr>
	    <tr><td>Ljpj Peternakan</td><td><?php echo $ljpj_peternakan; ?></td></tr>
	    <tr><td>Ljpj Kerjasama Adwm</td><td><?php echo $ljpj_kerjasama_adwm; ?></td></tr>
	    <tr><td>Ljpj Kerjasama Pdu Cabean Panggungharjo</td><td><?php echo $ljpj_kerjasama_pdu_cabean_panggungharjo; ?></td></tr>
	    <tr><td>Utang Usaha</td><td><?php echo $utang_usaha; ?></td></tr>
	    <tr><td>Utang Pajak</td><td><?php echo $utang_pajak; ?></td></tr>
	    <tr><td>Utang Lain Lain</td><td><?php echo $utang_lain_lain; ?></td></tr>
	    <tr><td>Utang Afiliasi</td><td><?php echo $utang_afiliasi; ?></td></tr>
	    <tr><td>Modal Dasar Dan Penyertaan</td><td><?php echo $modal_dasar_dan_penyertaan; ?></td></tr>
	    <tr><td>Cadangan Umum</td><td><?php echo $cadangan_umum; ?></td></tr>
	    <tr><td>Laba Bumd Pad</td><td><?php echo $laba_bumd_pad; ?></td></tr>
	    <tr><td>Laba Rugi Tahun Lalu</td><td><?php echo $laba_rugi_tahun_lalu; ?></td></tr>
	    <tr><td>Laba Rugi Tahun Berjalan</td><td><?php echo $laba_rugi_tahun_berjalan; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tbl_neraca_data') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>