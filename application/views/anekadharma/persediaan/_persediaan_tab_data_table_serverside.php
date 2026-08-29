<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$table_id = isset($table_id) ? (string) $table_id : 'table-persediaan';
$bulan_tampil = isset($bulan_tampil) ? (string) $bulan_tampil : date('Y-m');
$tab_mode = isset($tab_mode) ? (string) $tab_mode : 'barang';
$is_jasa_tab = (strtolower(trim($tab_mode)) === 'jasa');
$url_ajax = isset($url_ajax) ? (string) $url_ajax : '';

$this->load->helper('persediaan_display');
$show_keluar_columns = persediaan_tab_data_show_keluar_columns($tab_mode);
$nama_barang_header = persediaan_tab_data_nama_barang_header($tab_mode);
$persediaan_fields_tgl_total = persediaan_list_fields_tgl_keluar_sampai_total_10();
?>
<div class="persediaan-tab-dt-wrap">
	<table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-striped persediaan-tab-dt<?php echo $is_jasa_tab ? ' persediaan-jasa-dt' : ''; ?>" style="width:100%;font-size:14px;">
		<thead>
			<tr>
				<th width="50px">No</th>
				<th>Tanggal</th>
				<th><?php echo htmlspecialchars($nama_barang_header, ENT_QUOTES, 'UTF-8'); ?></th>
				<th>Satuan</th>
				<th class="text-right">Hpp</th>
				<th>Sa</th>
				<th class="text-right">Sa. Nominal</th>
				<th>SPOP</th>
				<th>Beli</th>
				<th class="text-right">Beli Nmnl</th>
				<th>Tuj</th>
				<?php foreach ($persediaan_fields_tgl_total as $field_tgl_total) { ?>
					<th><?php echo htmlspecialchars(persediaan_field_label($field_tgl_total), ENT_QUOTES, 'UTF-8'); ?></th>
				<?php } ?>
				<th class="text-right">Nilai Persediaan</th>
				<?php if ($show_keluar_columns) { ?>
				<th>Terjual</th>
				<th>Pecah</th>
				<th>Produksi</th>
				<?php } ?>
				<?php if ($is_jasa_tab) { ?>
				<th width="120px">Aksi</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script>
(function($) {
	'use strict';
	var tableId = '<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>';
	var ajaxUrl = '<?php echo htmlspecialchars($url_ajax, ENT_QUOTES, 'UTF-8'); ?>';
	var bulan = '<?php echo htmlspecialchars($bulan_tampil, ENT_QUOTES, 'UTF-8'); ?>';
	var tabMode = '<?php echo htmlspecialchars($tab_mode, ENT_QUOTES, 'UTF-8'); ?>';
	var isJasa = <?php echo $is_jasa_tab ? 'true' : 'false'; ?>;
	var showKeluar = <?php echo $show_keluar_columns ? 'true' : 'false'; ?>;

	if (!$.fn.DataTable) {
		console.warn('DataTables not loaded');
		return;
	}

	if ($.fn.DataTable.isDataTable('#' + tableId)) {
		$('#' + tableId).DataTable().destroy();
		$('#' + tableId).empty();
	}

	var columns = [
		{ data: 'no', orderable: false, searchable: false },
		{ data: 'tanggal' },
		{ data: 'namabarang' },
		{ data: 'satuan' },
		{ data: 'hpp', className: 'text-right' },
		{ data: 'sa' },
		{ data: 'sa_nominal', className: 'text-right' },
		{ data: 'spop' },
		{ data: 'beli' },
		{ data: 'beli_nominal', className: 'text-right' },
		{ data: 'tuj' }
	];

	$('#' + tableId).DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: ajaxUrl,
			type: 'POST',
			data: function(d) {
				d.bulan = bulan;
				d.tab_mode = tabMode;
			},
			dataSrc: function(json) {
				return json.data || [];
			}
		},
		columns: columns,
		pageLength: 10,
		lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
		order: [[2, 'asc']],
		scrollX: true,
		scrollCollapse: true,
		autoWidth: false,
		language: {
			processing: '<i class="fas fa-spinner fa-spin"></i> Memproses...',
			lengthMenu: 'Tampilkan _MENU_ baris',
			zeroRecords: 'Tidak ada data yang cocok',
			info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
			infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
			infoFiltered: '(disaring dari _MAX_ total data)',
			search: 'Cari:',
			paginate: {
				first: 'Awal',
				last: 'Akhir',
				next: 'Berikutnya',
				previous: 'Sebelumnya'
			}
		},
		drawCallback: function(settings) {
			var api = this.api();
			var data = api.rows({ page: 'current' }).data();
			// Re-number rows
			data.each(function(row, idx) {
				var pageInfo = api.page.info();
				row.no = pageInfo.start + idx + 1;
			});
			api.rows({ page: 'current' }).invalidate();
		}
	});
})(jQuery);
</script>