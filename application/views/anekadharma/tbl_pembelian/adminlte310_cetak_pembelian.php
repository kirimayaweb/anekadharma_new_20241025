<head>
	<style>
		#customers {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#customers td,
		#customers th {
			border: 1px solid #ddd;
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

		<tr>
			<!-- <td style="font-size:0.9vw" colspan="2"></td> -->
	
		</tr>


		<!-- BARIS KE 2 -->
		<tr>
			<!-- <th style="font-size:0.9vw; width=60px"></th> -->
			<th style="font-size:0.6vw" colspan="<?php //echo $kolom_kop_surat; ?>" align="left">
				NOTA PENGANTAR<br />
				Bersama ini kami titipkan buku tersebut dibawah ini <br/> agar untuk dijual, yaitu kepada: <?php //echo $nama_sales; ?>
			</th>
			<th style="font-size:0.6vw" colspan="<?php //echo $kolom_alamat; ?>"  align="left">
				MODUL :
				<?php 
				// $this->db->where('uuid_cover', $uuid_cover_produk);
				// $get_sys_cover = $this->db->get('sys_cover')->row_array();
				// 			echo $get_sys_cover['nama_cover'];
				//echo $data_nama_cover;
				?>
			</th>
		</tr>





		<!-- baris 3 -->
		<tr>
			<th style="font-size:0.9vw; width=60px"></th>
			<th style="font-size:0.9vw" ></th>
			<th style="font-size:0.9vw" colspan="<?php //echo $jumlah_kelas_X; ?>" align="center">Kelas</th>
			<!-- <th style="font-size:0.9vw"></th>
			<th style="font-size:0.9vw"></th>
			<th style="font-size:0.9vw"></th> -->
		</tr>






	






	</table>
	
</body>