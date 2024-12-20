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

<body>



	<table id="customers">




		<!-- BARIS KE 1 -->
		<tr>
			<!-- <th style="font-size:0.550em; width=60px"></th> -->
			<th style="font-size:1vw;text-align:center; width: 1000px;border: 1px solid black;border-collapse: collapse;" colspan="1000">
				<strong><u>PERMOHONAN PEMBAYARAN</u></strong>
			</th>

		</tr>






		<!-- baris 2 -->
		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size: 0.550em;text-align:left; width: 150px;" colspan="150">DIBAYARKAN KEPADA</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 248px;" colspan="348">
				<?php echo $supplier_nama; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x3</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">NO.</th>
			<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
				<?php echo ": " . $nomor_permohonan; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
		</tr>


		<?php

		// $jumlah_nominal = 1530093;
		// echo terbilang($angka);

		?>


		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">

			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">JUMLAH</th>

			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>

			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348">
				<?php echo  nominal($nominal_pengajuan); ?>
			</th>

			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">TANGGAL</th>

			<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
				<?php
				if (date("Y", strtotime($tgl_permohonan)) > 2020) {
					echo ": " . date("d M Y", strtotime($tgl_permohonan));
				} else {
					echo ":";
				}

				?>
			</th>

			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->

		</tr>

		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">TERBILANG</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="848">
				<?php echo  $terbilang; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">TANGGAL</th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2">:</th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="500"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>

		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<!-- <th style="font-size:0.550em; width=60px"></th> -->
			<th style="font-size:1vw;text-align:center; width: 1000px;height:25px" colspan="1000" align="center">
				<strong></strong>
			</th>
		</tr>

		<tr style="border: 1px solid black; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">KETERANGAN</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="450">
				<?php echo  $keterangan; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">JATUH TEMPO</th>
			<th style="font-size:0.550em; width: 100px;" colspan="298">
				<?php
				if (date("Y", strtotime($tgl_jatuh_tempo)) > 2020) {
					echo ": " . date("d M Y", strtotime($tgl_jatuh_tempo));
				} else {
					echo ":";
				}

				?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>


		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">No. Faktur</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="450">
				<?php echo  $nomor_faktur; ?>
			</th>

			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">ditransfer ke Rek.
				<?php echo $nama_bank; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>



		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; width: 602px;" colspan="602"></th>
			<!-- <th style="font-size:0.550em; width: 2px" colspan="2">:</th> -->
			<!-- <th style="font-size:0.550em; width: 348px;" colspan="450"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">
				<?php echo $nomor_rekening; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>



		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; width: 302px;" colspan="302"></th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<th style="font-size:0.550em; width: 100px;" colspan="100"></th>
			<th style="font-size:0.550em; width: 100px;" colspan="100"></th>
			<th style="font-size:0.550em; width: 100px;" colspan="100"></th>


			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="398">a.n
				<?php echo $atas_nama_rekening; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="298"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>

		<tr style="border: 1px solid black; border-top: none;  border-collapse: collapse;">
			<!-- <th style="font-size:0.550em; width=60px"></th> -->
			<th style="font-size:1vw;text-align:center; width: 1000px;height:25px" colspan="1000" align="center">
				<strong></strong>
			</th>
		</tr>



		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">PO. NO</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348"><?php echo $spop; ?></th>
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">BKK NO.</th>
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">
				<?php echo ": " . $nomor_bkk; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="98"></th> -->
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">TANGGAL</th>
			<th style="font-size:0.550em; text-align:left; width: 200px;" colspan="200">
				<?php
				if (date("Y", strtotime($tgl_nomor_bkk)) > 2020) {
					echo ": " . date("d M Y", strtotime($tgl_nomor_bkk));
				} else {
					echo ":";
				}

				?>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
		<script type="text/javascript">
			// the selector will match all input controls of type :checkbox
			// and attach a click event handler 
			$("input:checkbox").on('click', function() {
				// in the handler, 'this' refers to the box clicked on
				var $box = $(this);
				if ($box.is(":checked")) {
					// the name of the box is retrieved using the .attr() method
					// as it is assumed and expected to be immutable
					var group = "input:checkbox[name='" + $box.attr("name") + "']";
					// the checked state of the group/box on the other hand will change
					// and the current value is retrieved using .prop() method
					$(group).prop("checked", false);
					$box.prop("checked", true);
				} else {
					$box.prop("checked", false);
				}
			});
		</script>



		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">TANGGAL</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348">
				<?php
				if (date("Y", strtotime($tgl_po)) > 2020) {
					echo  date("d M Y", strtotime($tgl_po));
				} else {
					// echo ":";
				}

				?>
			</th>


			<?php
			if ($uuid_bank_bkk) {
			?>
				<!-- <th style="font-size:0.550em; text-align:left; width: 100px;" colspan="102">
											BANK : &ensp; <?php //echo $nama_bank; 
															?> &ensp;&ensp;&ensp; <?php //echo $nomor_rekening_bkk; 
																											?> &ensp;&ensp;&ensp; a.n <?php //echo $atas_nama_rekening_bkk; 
																																										?>
										</th> -->


				<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">BANK</th>
				<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
					<?php echo ": " . $nama_bank . "&ensp;&ensp;&ensp;" . $nomor_rekening_bkk . "&ensp;&ensp;&ensp; a.n: " . $atas_nama_rekening_bkk;  ?>
				</th>

			<?php } else {
			?>




				<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">KAS</th>
				<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
					(
					<input type="checkbox" class="radio" value="1" name="kas_checkbox" checked />
					)
				</th>


			<?php
			} ?>

			<!-- <input type="checkbox" class="radio" value="1" name="fooby[1][]" />Bank</label> -->

			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->

			<!-- <th style="font-size:0.550em; text-align:left; width: 198px;" colspan="198"> -->
			<!-- KAS &ensp; ( -->
			<?php
			// if ($kas_checkbox == 1) {
			?>
			<!-- <input type="checkbox" class="radio" value="1" name="kas_checkbox" checked /> -->
			<?php //} 
			?>
			<!-- ) -->

			<!-- </th> -->

			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>

		</tr>

		<script type="text/javascript">
			$('input[type="checkbox"]').on('change', function() {
				$('input[type="checkbox"]').not(this).prop('checked', false);
			});
		</script>



		<tr style="border: 1px solid black; border-top: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:left; width: 150px;" colspan="150">ACCOUNT</th>
			<th style="font-size:0.550em; text-align:left; width: 2px" colspan="2">:</th>
			<th style="font-size:0.550em; text-align:left; width: 348px;" colspan="348">
				<?php echo $account; ?>
			</th>
			<th style="font-size:0.550em; text-align:left; width: 100px;" colspan="100">CEK/GIRO NO</th>
			<th style="font-size:0.550em; text-align:left; width: 400px;" colspan="400">
				<?php echo ": " . $nomor_cek_giro; ?>
			</th>
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="398">KAS &ensp; (&ensp;&ensp;)</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>


		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em;text-align:center; width: 602px;border: 1px solid black; border-bottom: none; border-collapse: collapse;" colspan="602">PERSETUJUAN</th>
			<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 348px;" colspan="348"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-bottom: none; border-collapse: collapse;" colspan="398">PEMOHON</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>

		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;">
			<th style="font-size:0.550em;text-align:center; width: 602px;height: 50px;border: 1px solid black; border-top: none;border-bottom: none; border-collapse: collapse;" colspan="602"></th>
			<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 348px;" colspan="348"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 398px;height: 50px;border: 1px solid black;border-top: none;border-bottom: none;  border-collapse: collapse;" colspan="398"></th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>


		<tr style="border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;text-align:center">
			<th style="font-size:0.550em; text-align:center;width: 300px;border: 1px solid black; border-top: none; border-bottom: none;border-right: none; border-collapse: collapse;" colspan="300">
				<u>
					<?php echo $nama_direktur; ?>
				</u>
			</th>
			<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 148px;" colspan="148"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 302px;border: 1px solid black; border-top: none; border-bottom: none;border-left: none; border-collapse: collapse;" colspan="302">
				<u>
					<?php echo $nama_kabagkeuangan; ?>
				</u>
			</th>
			<!-- <th style="font-size:0.550em; width: 400px;" colspan="400"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;" colspan="398">
				<u><?php echo $nama_kasirpemebelian; ?></u>
			</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
		</tr>


		<tr style="border: 1px solid black; border-top: none; border-collapse: collapse;">
			<th style="font-size:0.550em; text-align:center;width: 300px;border: 1px solid black; border-top: none; border-bottom: none;border-right: none; border-collapse: collapse;" colspan="300">(DIREKTUR)</th>
			<!-- <th style="font-size:0.550em; width: 2px" colspan="2"></th> -->
			<!-- <th style="font-size:0.550em; width: 148px;" colspan="148"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 302px;border: 1px solid black; border-top: none; border-bottom: none;border-left: none; border-collapse: collapse;" colspan="302">(KA. BAG. KEUANGAN)</th>
			<!-- <th style="font-size:0.550em; width: 400px;" colspan="400"></th> -->
			<!-- <th style="font-size:0.550em; width: 2px;" colspan="2"></th> -->
			<th style="font-size:0.550em; text-align:center;width: 398px;border: 1px solid black; border-top: none; border-bottom: none; border-collapse: collapse;" colspan="398">(KASIR & PEMBELIAN)</th>
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 200px;" colspan="200">:</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x5</th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100"></th> -->
			<!-- <th style="font-size:0.550em; width: 100px;" colspan="100">x6</th> -->
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