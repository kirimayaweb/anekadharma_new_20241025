<head>
	<style>
		#customers {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#customers td,
		#customers th {
			border: 0px solid #ddd;
			padding: 3px;
		}

		#customers tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		#customers tr:hover {
			background-color: #ddd;
		}

		#customers th {
			padding-top: 1px;
			padding-bottom: 1px;
			/* text-align: left; */
			background-color: white;
			color: black;
		}
	</style>
</head>


<?php
$TOTAL_AKIVA_LANCAR = 0;
$Total_Aktiva_Tetap_Bersih = 0;
$Aktiva_Lain_Lain = 0;
$TOTAL_Utang_Lancar = 0;
$TOTAL_Utang_Jangka_Panjang = 0;
$TOTAL_Modal_dan_Laba_ditahan = 0;

?>

<body>



	<table id="customers">




		<!-- BARIS KE 1 -->
		<tr>
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"></th>

			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size: 1.250em;font-family: Times New Roman; text-align:left; width: 900px;" colspan="900">
				PERUMDA
			</th>
		</tr>

		<tr>
			<th style="font-size: 0.550em;text-align:left; width: 100px; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"></th>

			<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size: 2.250em; font-family: Arial; text-align:left; width: 900px;" colspan="900">
				<strong>ANEKA DHARMA</strong>
			</th>
		</tr>









		<tr>

			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"><strong></strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 675px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse;" colspan="675"><strong>TERUS BERGERAK MAJU BERSAMA </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th> -->


			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 125px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="125"><strong>NO. PSN </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="200"><strong><?php echo " : " .  $nmr_pesan_selected; ?></strong></th>


		</tr>


		<tr>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse; width: 775px;" colspan="775"><i><strong>Jl. Jendral Sudirman No. 36 Bantul Yogyakarta </strong></i></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th> -->


			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 125px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="125"><strong>TANGGAL </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="200"><strong><?php echo " : " .  $tgl_jual_selected; ?></strong></th>


		</tr>



		<tr>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none;  border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse; width: 775;" colspan="775">  <i><strong>0274 367123  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 08112657123 </strong></i></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-right:none;  border-collapse: collapse;" colspan="375"><i><strong>08112657123</strong></i></th> -->


			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 125px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="125"><strong>KEPADA YTH. </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong></strong></th> -->

			<th style="font-size: 0.550em;text-align:left; width: 200px;border: 1px solid black; border-bottom:none; border-top:none;  border-left:none;border-right:none;   border-collapse: collapse;" colspan="200"><strong><?php echo " : " . $konsumen_nama_selected; ?></strong></th>


		</tr>




		<tr>

			<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>NO</strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>HARGA</strong></th>

			<th style="font-size: 0.550em;text-align:center; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>SUB TOTAL</strong></th>


		</tr>



		<!-- LOOPING DATA -->
		<?php
		$start = 0;
		$x_total = 0;
		$TOTAL_PENJUALAN = 0;

		foreach ($data_penjualan as $list_data) {


		?>
			<tr>

				<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong><?php echo ++$start ?></strong></th>

				<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>
						<?php
						if ($list_data->kode_barang) {
							echo $list_data->kode_barang;
						} else {
							// echo $list_data->uuid_barang;
							// $gh = $this->Sys_nama_barang_model->get_by_uuid_barang($list_data->uuid_barang);
							// echo $gh->kode_barang;


							$uuidbarang_selected = $list_data->uuid_barang;

							$this->db->where('uuid_barang', $uuidbarang_selected);
							$data_barang = $this->db->get('sys_nama_barang');

							if ($data_barang->num_rows() > 0) {

								$data_barang = $data_barang->row_array();
								echo $data_barang['kode_barang'];
							}
						}

						?>
					</strong></th>

				<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong><?php echo $list_data->nama_barang; ?></strong></th>


				<th style="font-size: 0.550em;text-align:right; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong><?php echo nominal($list_data->jumlah); ?></strong></th>

				<th style="font-size: 0.550em;text-align:center; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong><?php echo $list_data->satuan; ?></strong></th>

				<th style="font-size: 0.550em;text-align:right; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong><?php echo nominal($list_data->harga_satuan); ?></strong></th>

				<th style="font-size: 0.550em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong>
						<?php
						$x_total = $list_data->jumlah * $list_data->harga_satuan;
						echo nominal($x_total);

						$TOTAL_PENJUALAN = $TOTAL_PENJUALAN + $x_total;
						?>

					</strong></th>


			</tr>

		<?php
		}
		?>
		<!-- END OF LOOPING DATA -->




		<!-- BARIS TOTAL -->

		<tr>

			<th style="font-size: 0.550em;text-align:left; text-transform: capitalize; width: 775px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="775">Terbilang: <strong><i> <?php  echo terbilang($TOTAL_PENJUALAN); ?> </i></strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>

			<th style="font-size: 0.550em;text-align:right; width: 225px;border: 1px solid black;    border-collapse: collapse;" colspan="225"><strong><?php echo nominal($TOTAL_PENJUALAN); ?></strong></th>


		</tr>



		<!-- TANDA TANGAN -->
		<tr>

			<th style="font-size: 0.550em;text-align:center; width: 775px;border: 1px solid black;  border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;  border-collapse: collapse;" colspan="775"><strong>Penerima </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>  -->

			<th style="font-size: 0.550em;text-align:center; width: 325px;border: 1px solid black; border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;   border-collapse: collapse;" colspan="325"><strong>Hormat Kami</strong></th>


		</tr>

		<tr>

			<th style="font-size: 0.550em;text-align:center;width: 775px;border: 1px solid black;  border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;  border-collapse: collapse;" colspan="775"><strong> .</strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>  -->

			<th style="font-size: 0.550em;text-align:center; width: 325px;border: 1px solid black;border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;   border-collapse: collapse;" colspan="325"><strong></strong></th>


		</tr>

		<tr>

			<th style="font-size: 0.550em;text-align:center;width: 775px;border: 1px solid black;  border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;  border-collapse: collapse;" colspan="775"><strong> .</strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>  -->

			<th style="font-size: 0.550em;text-align:center; width: 325px;border: 1px solid black; border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none;  border-collapse: collapse;" colspan="325"><strong></strong></th>


		</tr>

		<tr>

			<th style="font-size: 0.550em;text-align:center; width: 775px;border: 1px solid black;  border-right:none; border-bottom:none; border-top:none; border-left:none;border-right:none; border-collapse: collapse;" colspan="775"><strong>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ) </strong></th>

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="100"><strong>KODE</strong></th>

			<th style="font-size: 0.550em;text-align:left; width: 375px;border: 1px solid black;  border-right:none;  border-collapse: collapse;" colspan="375"><strong>NAMA BARANG</strong></th>


			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>
			
			<th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>SAT.</strong></th>-->

			<!-- <th style="font-size: 0.550em;text-align:left; width: 100px;border: 1px solid black;    border-collapse: collapse;" colspan="100"><strong>JUMLAH</strong></th>  -->

			<th style="font-size: 0.550em;text-align:center; width: 325px;border: 1px solid black;  border-bottom:none; border-top:none;  border-left:none;border-right:none; border-collapse: collapse;" colspan="325"><strong>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</strong></th>


		</tr>





	</table>


</body>



<?php

function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}


// $angka = 1530093;
// echo terbilang($angka);

?>