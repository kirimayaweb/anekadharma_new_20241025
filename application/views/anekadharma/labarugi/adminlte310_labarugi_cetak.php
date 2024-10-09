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
	<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
		<strong>PERUMDA ANEKA DHARMA KABUPATEN BANTUL</strong>
	</th>
</tr>

<tr>
	<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
		<strong>LAPORAN LABA - RUGI</strong>
	</th>
</tr>

<tr>
	<th style="border: 1px solid black;  border-top:none;border-bottom:none;border-right:none;border-left:none;  border-collapse: collapse; font-size:1vw;text-align:center; width: 1000px;" colspan="1000">
		<strong>Per Tanggal 31 Juli 2024</strong>
	</th>
</tr>






<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PENJUALAN</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border-top:none;border-bottom:none; width: 200px;" colspan="200"><?php echo nominal(234323432) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN POKOK PENJUALAN</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(234324323432) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">LABA RUGI BRUTO</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"><?php echo nominal(23423423445645) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

</tr>



<!-- buat jarak dengan baris atasnya -->
<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black; border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">BEBAN OPERASIONAL</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>
	<th style="font-size:0.550em; text-align:right;border-top:none;border-bottom:none;  width: 200px;" colspan="200"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Operasional Promosi</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(234323234324432) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>







<tr>

	<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Perjalanan Dinas</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(23432442) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Transportasi</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(23432442) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Beban Pemeliharaan</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(2343242) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>








<tr>

	<th style="font-size: 0.550em;text-align:left; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; width: 325px;" colspan="325">Total Beban Operaisonal umum</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>







<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">Total Beban Operasional</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(23432987874332) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400">Laba / Rugi Operasional</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(23432987874332) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>


<!-- Garis double : bprder-top : 1px -->
<tr>

	<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>




<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total Pendapatan Lain-lain</strong></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>



<!-- Garis double : bprder-top : 1px -->
<tr>

	<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325">Pendapatan Bunga Bank</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 75px;" colspan="75"></th>
	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 325px;" colspan="325"><strong>Total beban Lain-Lain</strong></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"><?php echo nominal(2342343445) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>





<!-- Garis double : bprder-top : 1px -->
<tr>

	<th style="font-size: 0.550em;text-align:left;border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-left: none;border-right: none; border-collapse: collapse;width: 200px;" colspan="200"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">LABA RUGI SEBELUM PAJAK</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(234323432) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400">PAJAK</th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; width: 200px;" colspan="200"><?php echo nominal(234323432) ?></th>
	<th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th>

</tr>





<tr>

	<th style="font-size: 0.550em;text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse;width: 400px;" colspan="400">LABA RUGI SETELAH PAJAK</th>

	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 10px;" colspan="10"></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20">Rp.</th>
	<th style="font-size:0.550em; text-align:right; border: 1px solid black;  border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 200px;" colspan="200"><?php echo nominal(234323432) ?></th>
	<th style="font-size:0.550em; text-align:left; border: 1px solid black;  border-top:none;border-bottom: none;border-left: none;border-right: none; border-collapse: collapse; width: 20px;" colspan="20"></th>

</tr>




<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Bantul, 31 juli 2024</th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Perusahaan Umum Daerah Aneka Dharma</th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Kabupaten Bantul</th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

</tr>




<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Direktur</th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

</tr>



<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

</tr>


<tr>

	<th style="font-size: 0.550em;text-align:left; width: 400px;" colspan="400"></th>

	<th style="font-size:0.550em; text-align:left; width: 10px;" colspan="10"></th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20">Rp.</th> -->
	<th style="font-size:0.550em; text-align:center; width: 240px;" colspan="240">Yuli Budi Sasangka,ST</th>
	<!-- <th style="font-size:0.550em; text-align:left; width: 20px;" colspan="20"></th> -->

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