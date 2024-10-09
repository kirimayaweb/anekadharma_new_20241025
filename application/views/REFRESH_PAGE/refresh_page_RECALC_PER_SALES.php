<!DOCTYPE html>
<html>

<head>
	<title>
		Proses refresh data RIWAYAT
	</title>
</head>

<body>
	<input type="button" id="btnRedirect" value="Proses" />
	<td style="font-size:0.9vw;width='1px'" align="center">
		<input type="text" class="form-control" name="get_base_url" id="get_base_url" placeholder="get_base_url" value="<?php echo base_url(); ?>" disabled />
	</td>
	<?php
	// $base_URL = base_url();	
	$get_awal = $id_awal;


	$get_tahun = $thn_process;
	$get_semester = $semester_process;
	// $x_semester_session = $_SESSION['semester_selected'];

	print_r($get_awal);
	// die;
	?>

	<br />
	<br />
	<div id="dvCountDown" style="display: none">
		You will be redirected after <span id="lblCount"></span>&nbsp;seconds.
	</div>
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


			var get_id_awal = <?php echo $get_awal; ?>; //ID AWAL
			var get_tahun = <?php echo $get_tahun; ?>; //TAHUN
			var get_semester = <?php echo $get_semester; ?>; //SEMESTER
			// var get_uuid_sales = <?php echo $get_uuid_sales; ?>; //SEMESTER

			// alert(get_id_awal);
			// $x = get_id_awal + 5;
			// alert($x);

			// $x_base_url = get_base_url;
			// alert($x_base_url);

			$get_base_url_X = get_base_urlX;

			$x_1 = "index.php/Rekap_per_sales/Recalc_rekap_sales_tahun_semester_tingkat/"; //uuid_sales/tahun/semester/TINGKAT

			$x_get_tahun = get_tahun + "/";

			$x_get_semester = get_semester + "/";

			$x_id_awal = get_id_awal + "/";

			$x_id_akhir = get_id_awal + 1; // + 1 id selanjutnya



			// $x_all = $x_base_url + $x_1 + $x_2 + $x_3 + $x_get_tahun + $x_get_semester;
			$x_all = $get_base_url_X + $x_1 + $x_get_tahun + $x_get_semester + $x_id_awal + $x_id_akhir;
			// alert($x_all)
			// --------------------------------------------------------------------

			$("#dvCountDown").show();
			$("#lblCount").html(seconds);

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


		// MEMPROSES  TANPA BUTTON, LANGSUNG COUNTING SAAT HALAMAN DI BUKA
		// $(function() {
		// 	// $("#btnRedirect").click(function () {  
		// 	var seconds = 5;

		// 	// var get_id_awal = 167;
		// 	// var get_id_akhir = 167+1;
		// 	var get_id_awal = <?php //echo $get_awal; 
									?>;
		// 	// alert(get_id_awal);
		// 	$x = get_id_awal + 5;
		// 	// alert($x);

		// 	$("#dvCountDown").show();
		// 	$("#lblCount").html(seconds);
		// 	setInterval(function() {
		// 		seconds--;
		// 		$("#lblCount").html(seconds);
		// 		if (seconds == 0) {
		// 			$("#dvCountDown").hide();
		// 			//// PINDAH HALAMAN KE :
		// 			// window.location = "http://localhost/pilarpustakagroup_live_20230709/index.php/Trans_pesan_jual_retur_pivot/update_pivot_all_sales_by_id_between/167/169/2024/1";  
		// 		}
		// 	}, 1000);
		// 	// });  
		// });
	</script>

</body>

</html>