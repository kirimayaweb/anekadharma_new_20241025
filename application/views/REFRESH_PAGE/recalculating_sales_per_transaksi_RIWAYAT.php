<!DOCTYPE html>
<html>

<head>
	<title>
		Proses refresh data RIWAYAT
	</title>
</head>

<body>


	<?php


	// $sql = "SELECT * FROM `sys_tingkat` WHERE `id`=$id_tingkat";
	// $get_tingkat = $this->db->query($sql)->row()->tingkat;


	// $base_URL = base_url();	
	$get_nama_sales = $nama_sales;
	$get_uuid_sales_selected = $uuid_sales_selected;

	$get_id_tingkat = $id_tingkat;
	// $get_jumlah_record_diproses = 168;
	$get_tahun = $thn_process;
	$get_semester = $semester_process;
	$get_field = $id_field;


	// print_r($nama_sales . " | tingkat : " . $get_tingkat);

	// CEK ID systeingkat apakah sudah teratas



	?>

	<br />
	<br />
	<td style="font-size:0.9vw;width='1px'" align="center">
		<input type="text" class="form-control" name="get_base_url" id="get_base_url" placeholder="get_base_url" value="<?php echo base_url(); ?>" disabled />
	</td>

	<td style="font-size:0.9vw;width='1px'" align="center">
		<input type="text" class="form-control" name="uuid_sales_selected" id="uuid_sales_selected" placeholder="uuid_sales_selected" value="<?php echo $uuid_sales_selected; ?>" disabled />
	</td>

<!-- 
	<br />
	<div id="dvCountDown" style="display: none">PROSES REKALKULASI DATA............................<br /> <?php //print_r("Nama Sales : " . $nama_sales); ?> <br />
	</div> -->

	<br />
	<br />
	<tr style=color:red;font-size:20px;>
		<td colspan="12" align="center"><strong>HARAP TUNGGU ............... </strong></td>
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

			var uuid_sales_selected = document.getElementById('uuid_sales_selected').value;
			var get_tahun = <?php echo $get_tahun; ?>; //TAHUN
			var get_semester = <?php echo $get_semester; ?>; //SEMESTER

			$get_base_url_X = get_base_urlX;
			// alert($get_base_url_X);

			// $x_link = "http://localhost/pilarpustakagroup_live_20230709/index.php/Trans_pesan_jual_retur_pivot/recalculate_pivot_to_pivotriwayat/";
			// $x_link = "https://pilarpustakagroup.com/index.php/Trans_pesan_jual_retur_pivot/recalculate_pivot_to_pivotriwayat/";
			$x_link =  "index.php/Trans_pesan_jual_retur_pivot/recalculate_pivot_to_pivotriwayat/";
			
			// alert($x_link);

			$x_uuid_sales_selected = uuid_sales_selected + "/";
			// alert($x_uuid_sales_selected);

			$x_get_tahun = get_tahun + "/";
			// alert($x_get_tahun);

			$x_get_semester = get_semester + "/";
			// alert($x_get_semester);

			$x_all = $get_base_url_X + $x_link + $x_uuid_sales_selected + $x_get_tahun + $x_get_semester;
			// alert($x_all)

			// $("#dvCountDown").show();
			// $("#lblCount").html(seconds);

			// SETELAH 5 DETIK , MEMPROSES REFRESH HALAMAN
			setInterval(function() {
				seconds--;
				$("#lblCount").html(seconds);
				if (seconds == 0) {
					$("#dvCountDown").hide();

					// window.location = "http://localhost/pilarpustakagroup_live_20230709/index.php/Trans_pesan_jual_retur_pivot/update_pivot_all_sales_by_id_between/167/169/2024/1";

					window.location = $x_all;


				}
			}, 1000);
		});
		// }); // form button proses
	</script>

</body>

</html>