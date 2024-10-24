<!doctype html>
<html>

<head>
	<title>harviacode.com - codeigniter crud generator</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
	<style>
		body {
			padding: 15px;
		}
	</style>
</head>

<body>
	<h2 style="margin-top:0px">Persediaan List</h2>
	<div class="row" style="margin-bottom: 10px">
		<div class="col-md-4">
			<?php echo anchor(site_url('persediaan/create'), 'Create', 'class="btn btn-primary"'); ?>
		</div>
		<div class="col-md-4 text-center">
			<div style="margin-top: 8px" id="message">
				<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
			</div>
		</div>
		<div class="col-md-1 text-right">
		</div>
		<div class="col-md-3 text-right">
			<form action="<?php echo site_url('persediaan/index'); ?>" class="form-inline" method="get">
				<div class="input-group">
					<input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
					<span class="input-group-btn">
						<?php
						if ($q <> '') {
						?>
							<a href="<?php echo site_url('persediaan'); ?>" class="btn btn-default">Reset</a>
						<?php
						}
						?>
						<button class="btn btn-primary" type="submit">Search</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	<table class="table table-bordered" style="margin-bottom: 10px">
		<tr>
			<th>No</th>
			<th>Tanggal</th>
			<th> Kode</th>
			<th>Namabarang</th>
			<th>Sa</th>
			<th> Hpp</th>
			<th> Sa</th>
			<th>Spop</th>
			<th>Beli</th>
			<th> Tuj</th>
			<th>Tgl Keluar</th>
			<th>Sekret</th>
			<th>Cetak</th>
			<th>Grafikita</th>
			<th>Dinas Umum</th>
			<th>Atk Rsud</th>
			<th>Ppbmp Kbs</th>
			<th>Kbs</th>
			<th>Ppbmp</th>
			<th>Medis</th>
			<th>Siiplah Bosda</th>
			<th>Sembako</th>
			<th>Fc Gose</th>
			<th>Fc Manding</th>
			<th>Fc Psamya</th>
			<th>Total 10</th>
			<th> Nilai Persediaan</th>
			<th>Action</th>
		</tr>
		<?php
				foreach ($persediaan_data as $persediaan) {
				?>
			<tr>
				<td width="80px"><?php echo ++$start ?></td>
				<td><?php echo $persediaan->tanggal ?></td>
				<td><?php echo $persediaan->kode ?></td>
				<td><?php echo $persediaan->namabarang ?></td>
				<td><?php echo $persediaan->sa ?></td>
				<td><?php echo $persediaan->hpp ?></td>
				<td><?php echo $persediaan->sa ?></td>
				<td><?php echo $persediaan->spop ?></td>
				<td><?php echo $persediaan->beli ?></td>
				<td><?php echo $persediaan->tuj ?></td>
				<td><?php echo $persediaan->tgl_keluar ?></td>
				<td><?php echo $persediaan->sekret ?></td>
				<td><?php echo $persediaan->cetak ?></td>
				<td><?php echo $persediaan->grafikita ?></td>
				<td><?php echo $persediaan->dinas_umum ?></td>
				<td><?php echo $persediaan->atk_rsud ?></td>
				<td><?php echo $persediaan->ppbmp_kbs ?></td>
				<td><?php echo $persediaan->kbs ?></td>
				<td><?php echo $persediaan->ppbmp ?></td>
				<td><?php echo $persediaan->medis ?></td>
				<td><?php echo $persediaan->siiplah_bosda ?></td>
				<td><?php echo $persediaan->sembako ?></td>
				<td><?php echo $persediaan->fc_gose ?></td>
				<td><?php echo $persediaan->fc_manding ?></td>
				<td><?php echo $persediaan->fc_psamya ?></td>
				<td><?php echo $persediaan->total_10 ?></td>
				<td><?php echo $persediaan->nilai_persediaan ?></td>
				<td style="text-align:center" width="200px">
					<?php
					echo anchor(site_url('persediaan/read/' . $persediaan->id), 'Read');
					echo ' | ';
					echo anchor(site_url('persediaan/update/' . $persediaan->id), 'Update');
					echo ' | ';
					echo anchor(site_url('persediaan/delete/' . $persediaan->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
					?>
				</td>
			</tr>
		<?php
				}
		?>
	</table>
	<div class="row">
		<div class="col-md-6">
			<a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
			<?php echo anchor(site_url('persediaan/excel'), 'Excel', 'class="btn btn-primary"'); ?>
			<?php echo anchor(site_url('persediaan/word'), 'Word', 'class="btn btn-primary"'); ?>
		</div>
		<div class="col-md-6 text-right">
			<?php echo $pagination ?>
		</div>
	</div>
</body>

</html>