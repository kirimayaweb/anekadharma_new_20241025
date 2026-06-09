<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0">Monitoring Sistem</h1>
                    <small class="text-muted">Pantau siapa yang sedang login / bekerja di aplikasi sebelum git push atau git pull ke server.</small>
                </div>
                <div class="col-sm-4 text-right">
                    <span class="badge badge-info" id="monitoring-server-time">Server: <?php echo htmlspecialchars($server_time, ENT_QUOTES, 'UTF-8'); ?></span>
                    <span class="badge badge-secondary ml-1" id="monitoring-refresh-status">Auto refresh 30 detik</span>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="sum-working"><?php echo (int) $summary['working']; ?></h3>
                        <p>Sedang Bekerja</p>
                    </div>
                    <div class="icon"><i class="fa fa-play-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="sum-idle"><?php echo (int) $summary['idle']; ?></h3>
                        <p>Login (Idle)</p>
                    </div>
                    <div class="icon"><i class="fa fa-pause-circle"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="sum-online"><?php echo (int) $summary['online']; ?></h3>
                        <p>Total Online</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3 id="sum-offline"><?php echo (int) $summary['offline']; ?></h3>
                        <p>Offline / Riwayat</p>
                    </div>
                    <div class="icon"><i class="fa fa-power-off"></i></div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-desktop"></i> Daftar Akses User / Komputer</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-light" id="btn-refresh-monitoring">
                        <i class="fa fa-refresh"></i> Refresh Sekarang
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover table-bordered mb-0" id="tbl-monitoring-presence">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status</th>
                            <th>Nama / Email</th>
                            <th>IP Address</th>
                            <th>Perangkat</th>
                            <th>Browser</th>
                            <th>Halaman Terakhir</th>
                            <th>Login</th>
                            <th>Terakhir Aktif</th>
                            <th>Logout</th>
                        </tr>
                    </thead>
                    <tbody id="monitoring-tbody">
                        <?php if (empty($presence_rows)) : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">Belum ada data monitoring. Data akan muncul setelah user login ke aplikasi.</td>
                            </tr>
                        <?php else : ?>
                            <?php $no = 0; foreach ($presence_rows as $row) : ?>
                                <tr>
                                    <td><?php echo ++$no; ?></td>
                                    <td><span class="badge <?php echo $row->activity_badge; ?>"><?php echo htmlspecialchars($row->activity_label, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row->full_name, ENT_QUOTES, 'UTF-8'); ?></strong><br>
                                        <small><?php echo htmlspecialchars($row->email, ENT_QUOTES, 'UTF-8'); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row->ip_address, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row->device_label, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row->browser_label, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <code><?php echo htmlspecialchars($row->last_controller . '/' . $row->last_method, ENT_QUOTES, 'UTF-8'); ?></code>
                                        <?php if ($row->last_uri) : ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($row->last_uri, ENT_QUOTES, 'UTF-8'); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row->login_at, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row->last_seen_at, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $row->logout_at ? htmlspecialchars($row->logout_at, ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted">
                <strong>Sedang bekerja</strong> = aktivitas dalam <?php echo (int) $threshold_minutes; ?> menit terakhir.
                Aman untuk <strong>git push / git pull</strong> jika tidak ada user berstatus <span class="badge badge-success">Sedang bekerja</span>.
            </div>
        </div>
    </section>
</div>

<script>
(function() {
    var ajaxUrl = '<?php echo site_url('Monitoring_system/ajax_data'); ?>';
    var refreshTimer = null;

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function renderRows(rows) {
        var tbody = document.getElementById('monitoring-tbody');
        if (!tbody) return;

        if (!rows || !rows.length) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted">Belum ada data monitoring.</td></tr>';
            return;
        }

        var html = '';
        for (var i = 0; i < rows.length; i++) {
            var r = rows[i];
            html += '<tr>';
            html += '<td>' + (i + 1) + '</td>';
            html += '<td><span class="badge ' + escapeHtml(r.activity_badge) + '">' + escapeHtml(r.activity_label) + '</span></td>';
            html += '<td><strong>' + escapeHtml(r.full_name) + '</strong><br><small>' + escapeHtml(r.email) + '</small></td>';
            html += '<td>' + escapeHtml(r.ip_address) + '</td>';
            html += '<td>' + escapeHtml(r.device_label) + '</td>';
            html += '<td>' + escapeHtml(r.browser_label) + '</td>';
            html += '<td><code>' + escapeHtml(r.last_controller + '/' + r.last_method) + '</code>';
            if (r.last_uri) {
                html += '<br><small class="text-muted">' + escapeHtml(r.last_uri) + '</small>';
            }
            html += '</td>';
            html += '<td>' + escapeHtml(r.login_at) + '</td>';
            html += '<td>' + escapeHtml(r.last_seen_at) + '</td>';
            html += '<td>' + (r.logout_at ? escapeHtml(r.logout_at) : '-') + '</td>';
            html += '</tr>';
        }
        tbody.innerHTML = html;
    }

    function updateSummary(summary) {
        if (!summary) return;
        var el;
        el = document.getElementById('sum-working'); if (el) el.textContent = summary.working || 0;
        el = document.getElementById('sum-idle'); if (el) el.textContent = summary.idle || 0;
        el = document.getElementById('sum-online'); if (el) el.textContent = summary.online || 0;
        el = document.getElementById('sum-offline'); if (el) el.textContent = summary.offline || 0;
    }

    function refreshMonitoring() {
        var statusEl = document.getElementById('monitoring-refresh-status');
        if (statusEl) statusEl.textContent = 'Memuat...';

        fetch(ajaxUrl, { credentials: 'same-origin' })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (!data || !data.ok) return;
                renderRows(data.rows || []);
                updateSummary(data.summary || {});
                var timeEl = document.getElementById('monitoring-server-time');
                if (timeEl && data.server_time) {
                    timeEl.textContent = 'Server: ' + data.server_time;
                }
                if (statusEl) statusEl.textContent = 'Auto refresh 30 detik';
            })
            .catch(function() {
                if (statusEl) statusEl.textContent = 'Gagal refresh';
            });
    }

    function startAutoRefresh() {
        clearInterval(refreshTimer);
        refreshTimer = setInterval(refreshMonitoring, 30000);
    }

    var btn = document.getElementById('btn-refresh-monitoring');
    if (btn) {
        btn.addEventListener('click', refreshMonitoring);
    }

    startAutoRefresh();
})();
</script>
