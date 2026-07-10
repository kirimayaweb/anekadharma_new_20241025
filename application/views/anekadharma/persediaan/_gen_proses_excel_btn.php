<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$excel_jenis = isset($excel_jenis) ? trim((string) $excel_jenis) : '';
$excel_title = isset($excel_title) ? trim((string) $excel_title) : 'Cetak tabel ini ke Excel';
if ($excel_jenis === '') {
	return;
}
?>
<div class="d-flex justify-content-end mb-2 gen-proses-excel-toolbar">
	<button type="button"
		class="btn btn-sm btn-success btn-gen-proses-excel shadow-sm"
		data-jenis="<?php echo htmlspecialchars($excel_jenis, ENT_QUOTES, 'UTF-8'); ?>"
		title="<?php echo htmlspecialchars($excel_title, ENT_QUOTES, 'UTF-8'); ?>">
		<i class="fas fa-file-excel mr-1"></i> Excel
	</button>
</div>
