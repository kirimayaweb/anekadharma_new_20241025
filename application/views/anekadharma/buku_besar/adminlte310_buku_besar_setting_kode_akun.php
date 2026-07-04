<?php
if (!isset($bulan_ns_value)) {
    $bulan_ns_value = sprintf('%04d-%02d', (int) date('Y'), (int) date('m'));
}
if (!isset($values) || !is_array($values)) {
    $values = array();
}
if (!isset($selected_tables) || !is_array($selected_tables)) {
    $selected_tables = array();
}
$url_self = isset($url_self) ? $url_self : site_url('Buku_besar_setting_kode_akun');
$url_json = isset($url_json) ? $url_json : site_url('Buku_besar_setting_kode_akun/json');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-primary"><strong>Nilai Kode Akun — Buku Besar Setting</strong></h1>
                    <small class="text-muted">Data real-time dari tabel transaksi yang di-setting per record di Buku Besar tab Setting Tabel dan Kode Akun</small>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="<?php echo site_url('Buku_besar'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali ke Buku Besar</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary">
            <div class="card-header py-2">
                <form id="form-bb-ska-filter" method="post" action="<?php echo htmlspecialchars($url_self, ENT_QUOTES, 'UTF-8'); ?>" class="form-inline">
                    <label for="bb_ska_bulan_ns" class="mr-2 mb-0">Bulan:</label>
                    <input type="month" class="form-control form-control-sm mr-2" id="bb_ska_bulan_ns" name="bulan_ns" value="<?php echo htmlspecialchars($bulan_ns_value, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-danger btn-sm mr-2"><i class="fa fa-search"></i> Tampilkan</button>
                    <button type="button" class="btn btn-info btn-sm" id="btn-bb-ska-refresh-json"><i class="fa fa-refresh"></i> Refresh JSON</button>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2">
                    <strong>Tabel aktif:</strong>
                    <?php if (empty($selected_tables)) { ?>
                        <span class="text-danger">Belum ada tabel — setting di <a href="<?php echo site_url('Buku_besar'); ?>">Buku Besar → tab Setting Tabel dan Kode Akun</a></span>
                    <?php } else { ?>
                        <?php
                        $tbl_labels = array();
                        foreach ($selected_tables as $t) {
                            $tbl_labels[] = htmlspecialchars($t['nama_tabel'], ENT_QUOTES, 'UTF-8');
                        }
                        echo implode(', ', $tbl_labels);
                        ?>
                    <?php } ?>
                </div>
                <p class="small text-muted mb-2">
                    API JSON: <code><?php echo htmlspecialchars($url_json, ENT_QUOTES, 'UTF-8'); ?></code>
                    — dapat dipanggil dari halaman manapun (POST/GET <code>bulan_ns=YYYY-MM</code>).
                </p>
                <div class="table-responsive">
                    <table id="table-bb-ska-values" class="table table-bordered table-striped table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th class="text-right">Nilai</th>
                                <th class="text-center">Jumlah Record</th>
                                <th>Sumber Tabel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            foreach ($values as $row) {
                                $no++;
                                $tables_txt = '';
                                if (!empty($row['tables']) && is_array($row['tables'])) {
                                    $parts = array();
                                    foreach ($row['tables'] as $tbl => $cnt) {
                                        $parts[] = $tbl . ' (' . (int) $cnt . ')';
                                    }
                                    $tables_txt = implode(', ', $parts);
                                }
                                ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['kode_akun'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['nama_akun'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="text-right bb-ska-nominal" data-kode="<?php echo htmlspecialchars($row['kode_akun'], ENT_QUOTES, 'UTF-8'); ?>" data-nominal="<?php echo (float) $row['nominal']; ?>">
                                        <?php echo htmlspecialchars($row['nominal_formatted'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td class="text-center"><?php echo (int) $row['record_count']; ?></td>
                                    <td class="small"><?php echo htmlspecialchars($tables_txt, ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
(function() {
    var urlJson = <?php echo json_encode($url_json); ?>;
    function initDt() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) return;
        var $t = jQuery('#table-bb-ska-values');
        if (!$t.length || jQuery.fn.DataTable.isDataTable($t)) return;
        $t.DataTable({
            pageLength: 50,
            order: [[1, 'asc']],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
        });
    }
    jQuery('#btn-bb-ska-refresh-json').on('click', function() {
        jQuery.ajax({
            url: urlJson,
            type: 'POST',
            dataType: 'json',
            data: { bulan_ns: jQuery('#bb_ska_bulan_ns').val() || '' }
        }).done(function(res) {
            if (!res || !res.ok) return;
            if (jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#table-bb-ska-values')) {
                jQuery('#table-bb-ska-values').DataTable().destroy();
            }
            var html = '', no = 0;
            (res.values || []).forEach(function(row) {
                no++;
                var tblParts = [];
                if (row.tables) {
                    Object.keys(row.tables).forEach(function(k) {
                        tblParts.push(k + ' (' + row.tables[k] + ')');
                    });
                }
                html += '<tr><td>' + no + '</td><td><strong>' + (row.kode_akun || '') + '</strong></td>'
                    + '<td>' + (row.nama_akun || '') + '</td>'
                    + '<td class="text-right">' + (row.nominal_formatted || '0,00') + '</td>'
                    + '<td class="text-center">' + (row.record_count || 0) + '</td>'
                    + '<td class="small">' + tblParts.join(', ') + '</td></tr>';
            });
            jQuery('#table-bb-ska-values tbody').html(html);
            initDt();
        });
    });
    jQuery(document).ready(initDt);
})();
</script>
