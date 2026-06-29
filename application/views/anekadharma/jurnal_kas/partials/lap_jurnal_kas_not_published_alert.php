<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($not_published)) { ?>
<div class="alert alert-warning mb-3">
    <i class="fas fa-info-circle"></i>
    Laporan Buku Kas <strong><?php echo htmlspecialchars($bulan_label, ENT_QUOTES, 'UTF-8'); ?></strong> belum dipublish.
    Silakan publish dari halaman <strong>Jurnal Kas → Compare Data Manual - Online (Tab 2)</strong> terlebih dahulu.
</div>
<?php } ?>
