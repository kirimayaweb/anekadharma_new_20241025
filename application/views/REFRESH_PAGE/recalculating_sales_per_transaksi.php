<!DOCTYPE html>
<html>

<head>
	<title>
		Proses refresh data RIWAYAT
	</title>
</head>

<body>


	<?php


	$sql = "SELECT * FROM `sys_tingkat` WHERE `id`=$id_tingkat";
	$get_tingkat = $this->db->query($sql)->row()->tingkat;


	// $base_URL = base_url();	
	$get_nama_sales = $nama_sales;
	$get_uuid_sales_selected = $uuid_sales_selected;

	$get_id_tingkat = $id_tingkat;
	// $get_jumlah_record_diproses = 168;
	$get_tahun = $thn_process;
	$get_semester = $semester_process;
	$get_field = $id_field;

	// $get_base_url = base_url(); //DOMAIN

	// print_r($get_base_url);

	// print_r($nama_sales . " | tingkat : " . $get_tingkat);

	// CEK ID systeingkat apakah sudah teratas



	?>

	

	<td style="font-size:0.9vw;width='1px'" align="center">
		<input type="text" class="form-control" name="get_base_url" id="get_base_url" placeholder="get_base_url" value="<?php echo base_url(); ?>" disabled />
	</td>
	<td style="font-size:0.9vw;width='1px'" align="center">
		<input type="text" class="form-control" name="uuid_sales_selected" id="uuid_sales_selected" placeholder="uuid_sales_selected" value="<?php echo $uuid_sales_selected; ?>" disabled />
	</td>

	<br />
	<br />
	<!-- <input type="button" id="btnRedirect" value="Proses Recalculating" /> -->

	<?php
	if ($data_Tingkat) {
		// print_r($data_Tingkat);
		foreach ($data_Tingkat as $get_data_tingkat) {

			$sql = "SELECT id FROM `trans_pesan_jual_retur_pivot` where `uuid_sales`='$uuid_sales_selected' AND `tingkat_pesanan`='$get_data_tingkat->tingkat_pesanan' ORDER by id";
			$jumlah_record = $this->db->query($sql);


			print_r($get_data_tingkat->tingkat_pesanan . " : " . $jumlah_record->num_rows() . " Transaksi");
			print_r("<br/>");
		}
	}
	?>

	<br />
	<div id="dvCountDown" style="display: none">PROSES REKALKULASI DATA, <br /><br /> Sistem akan melakukan proses kalkulasi data: <br /> <?php print_r("Nama Sales : " . $nama_sales . " <br/> Tingkat : " . $get_tingkat); ?> <br />
	</div>




	<br />
	<br />

	<?php

	$sql = "SELECT MAX(id) as MaxId FROM `sys_tingkat`";
	$MaxId_Tingkat = $this->db->query($sql)->row()->MaxId;

	if ($id_tingkat <= $MaxId_Tingkat + 1) {
		$id_tingkat_check = 1;
	} else {
		$id_tingkat_check = 0;
	}

	// print_r("Id proses : " . $id_tingkat . " Max : " . $MaxId_Tingkat . " ==> id_tingkat_check : " . $id_tingkat_check);

	?>

	<br />
	<br />
	<tr style=color:red;font-size:20px;>
		<td colspan="12" align="center"><strong>HARAP TUNGGU </strong>Halaman akan refresh: <span id="lblCount"></span>&nbsp;detik</strong></td>
	</tr>



	<!-- <br />
	<br />
	<div id="dvCountDown" style="display: none">
		You will be redirected after <span id="lblCount"></span>&nbsp;seconds.
	</div> -->


	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> -->
	<script src="<?php echo base_url() ?>jquery/jquery183.min.js"></script>

	<script type="text/javascript">
		// MEMPROSES SETELAH KLIK BUTTON REDIRECT  
		$(function() {
			// $("#btnRedirect").click(function() { //Klik button prosess
			var seconds = 1;

			// PROSES KENDALI DATA ID SALES DARI PHP ------------------------------------
			// var get_id_awal = 167;
			// var get_id_akhir = 167+1;

			var get_base_urlX = document.getElementById('get_base_url').value;
			// var get_base_urlX = <?php //echo $get_base_url ?>; //DOMAIN

			// alert(get_base_urlX);
			
			var uuid_sales_selected = document.getElementById('uuid_sales_selected').value;
			// var uuid_sales_selected = <?php //echo $get_uuid_sales_selected; 
											?>; //ID AWAL
			// alert(uuid_sales_selected);
			var get_id_tingkat = <?php echo $get_id_tingkat; ?>; //ID AWAL		
			// alert(get_id_tingkat);		
			var get_tahun = <?php echo $get_tahun; ?>; //TAHUN
			var get_semester = <?php echo $get_semester; ?>; //SEMESTER
			var get_field = <?php echo $get_field; ?>; //FIELD NUMBER

			var get_id_tingkat_check = <?php echo $id_tingkat_check; ?>; //id tingkat


			$get_base_url_X = get_base_urlX;
			// alert($get_base_url_X);

			$x_id_tingkat_check = get_id_tingkat_check;
			// alert($x_id_tingkat_check);

			// $x_link = "http://localhost/pilarpustakagroup_live_20230709/index.php/Trans_pesan_jual_retur_pivot/Recalculating_pertingkat/";
			// $x_link = "https://pilarpustakagroup.com/index.php/Trans_pesan_jual_retur_pivot/Recalculating_pertingkat/";
			$x_link =  "index.php/Trans_pesan_jual_retur_pivot/Recalculating_pertingkat/";

			// alert($x_link);

			$x_uuid_sales_selected = uuid_sales_selected + "/";
			// alert($x_uuid_sales_selected);

			$x_get_tahun = get_tahun + "/";
			// alert($x_get_tahun);

			$x_get_semester = get_semester + "/";
			// alert($x_get_semester);

			$x_get_id_tingkat = get_id_tingkat + "/"; // + 1 id selanjutnya
			// alert($x_get_id_tingkat);

			$x_get_field = get_field;
			// alert($x_get_field);

			$x_all = $get_base_url_X + $x_link + $x_uuid_sales_selected + $x_get_tahun + $x_get_semester + $x_get_id_tingkat + $x_get_field;
			// alert($x_all)

			$("#dvCountDown").show();
			$("#lblCount").html(seconds);

			// SETELAH 5 DETIK , MEMPROSES REFRESH HALAMAN
			setInterval(function() {
				seconds--;
				$("#lblCount").html(seconds);
				if (seconds == 0) {
					$("#dvCountDown").hide();

					// window.location = "http://localhost/pilarpustakagroup_live_20230709/index.php/Trans_pesan_jual_retur_pivot/update_pivot_all_sales_by_id_between/167/169/2024/1";

					// if (x_id_tingkat_check == 1) {

					// 	alert("id tingkat check = 1");

					// 	$x_Link_riwayat = "http://localhost/pilarpustakagroup_live_20230709/index.php/Tbl_sales/riwayat_per_sales3view/" + $x_uuid_sales_selected;
					// 	// 	// $x_link = "https://pilarpustakagroup.com/index.php/Tbl_sales/riwayat_per_sales3view/";

					// 	alert($x_Link_riwayat);
					// 	window.location = $x_Link_riwayat;
					// } else {

						// alert("id tingkat check = " + $x_id_tingkat_check);

						window.location = $x_all;
					// }


				}
			}, 1000);
		});
		// }); // form button proses
	</script>

</body>

</html>