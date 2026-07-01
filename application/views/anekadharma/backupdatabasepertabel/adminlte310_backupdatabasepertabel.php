<div class="content-wrapper">
    <section class="content">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-database"></i> Backup Database Per Tabel (Manual)</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Database aktif:</strong> <?php echo html_escape($database_name); ?><br>
                        <strong>Format SQL:</strong> Satu file SQL per tabel. Jika data &gt; <?php echo (int) $chunk_size; ?> baris,
                        file dibagi per <?php echo (int) $chunk_size; ?> record
                        (contoh: <code>tbl_user.sql</code>, <code>tbl_user_1001.sql</code>, <code>tbl_user_2001.sql</code>).<br>
                        <strong>Format file:</strong> Pilih <em>Asli</em> untuk menyimpan file <code>.sql</code> langsung,
                        atau pilih <em>Zip</em> agar setiap tabel disimpan sebagai <code>nama_tabel.zip</code>
                        (bisa diekstrak kembali menjadi file SQL asli).<br>
                        <strong>Folder:</strong> Sistem akan membuat subfolder
                        <code>BD_anekadharma_YYYYMMDD_HHMM</code> di dalam folder lokal yang Anda pilih.<br>
                        <strong>Browser:</strong> Gunakan Chrome atau Edge agar pemilihan folder lokal berfungsi.
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-8 d-flex flex-wrap align-items-center">
                            <button type="button" id="btnBackupDatabase" class="btn btn-danger btn-lg mr-3">
                                <i class="fas fa-download"></i> Backup Database
                            </button>
                            <div class="form-group mb-0 mr-3">
                                <label class="mb-1 d-block"><strong>Format file</strong></label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-outline-primary active" id="lblFormatAsli">
                                        <input type="radio" name="backupFileFormat" id="formatAsli" value="asli" autocomplete="off" checked> Asli (.sql)
                                    </label>
                                    <label class="btn btn-outline-primary" id="lblFormatZip">
                                        <input type="radio" name="backupFileFormat" id="formatZip" value="zip" autocomplete="off"> Zip (.zip)
                                    </label>
                                </div>
                            </div>
                            <span id="backupStatusLabel" class="text-muted"></span>
                        </div>
                    </div>

                    <div id="backupProgressWrap" class="mb-4" style="display:none;">
                        <label>Progress backup</label>
                        <div class="progress">
                            <div id="backupProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                role="progressbar" style="width:0%;">0%</div>
                        </div>
                        <small id="backupProgressDetail" class="text-muted d-block mt-1"></small>
                    </div>

                    <hr>

                    <h5><i class="fas fa-history"></i> Riwayat Backup Database</h5>
                    <div class="table-responsive">
                        <table id="tblBackupLog" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>User</th>
                                    <th>Folder Backup</th>
                                    <th>Database</th>
                                    <th>Jml Tabel</th>
                                    <th>Jml File</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="backupLogBody">
                                <?php $no = 0; foreach ($backup_logs as $log): ?>
                                <tr>
                                    <td><?php echo ++$no; ?></td>
                                    <td><?php echo html_escape($log->created_at); ?></td>
                                    <td><?php echo html_escape($log->finished_at ?: '-'); ?></td>
                                    <td><?php echo html_escape($log->user_name ?: '-'); ?></td>
                                    <td><code><?php echo html_escape($log->folder_path_label); ?></code></td>
                                    <td><?php echo html_escape($log->database_name); ?></td>
                                    <td class="text-center"><?php echo (int) $log->total_tables; ?></td>
                                    <td class="text-center"><?php echo (int) $log->total_files; ?></td>
                                    <td>
                                        <?php
                                        $badge = 'secondary';
                                        if ($log->status === 'completed') {
                                            $badge = 'success';
                                        } elseif ($log->status === 'failed') {
                                            $badge = 'danger';
                                        } elseif ($log->status === 'processing') {
                                            $badge = 'warning';
                                        }
                                        ?>
                                        <span class="badge badge-<?php echo $badge; ?>"><?php echo html_escape($log->status); ?></span>
                                    </td>
                                    <td><?php echo html_escape($log->note ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($backup_logs)): ?>
                                <tr id="backupLogEmpty">
                                    <td colspan="10" class="text-center text-muted">Belum ada riwayat backup.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url('assets/AdminLTE310/plugins/jszip/jszip.min.js'); ?>"></script>
<script>
(function () {
    var CHUNK_SIZE = <?php echo (int) $chunk_size; ?>;
    var API = {
        getTables: <?php echo json_encode($api_get_tables); ?>,
        exportSql: <?php echo json_encode($api_export_sql); ?>,
        saveLog: <?php echo json_encode($api_save_log); ?>,
        updateLog: <?php echo json_encode($api_update_log); ?>
    };

    var btn = document.getElementById('btnBackupDatabase');
    var statusLabel = document.getElementById('backupStatusLabel');
    var progressWrap = document.getElementById('backupProgressWrap');
    var progressBar = document.getElementById('backupProgressBar');
    var progressDetail = document.getElementById('backupProgressDetail');
    var logBody = document.getElementById('backupLogBody');
    var isRunning = false;

    function pad2(n) {
        return (n < 10 ? '0' : '') + n;
    }

    function buildFolderName(dateObj) {
        return 'BD_anekadharma_'
            + dateObj.getFullYear()
            + pad2(dateObj.getMonth() + 1)
            + pad2(dateObj.getDate())
            + '_'
            + pad2(dateObj.getHours())
            + pad2(dateObj.getMinutes());
    }

    function setProgress(current, total, detail) {
        var pct = total > 0 ? Math.round((current / total) * 100) : 0;
        progressBar.style.width = pct + '%';
        progressBar.textContent = pct + '%';
        progressDetail.textContent = detail || '';
    }

    function statusBadge(status) {
        var cls = 'secondary';
        if (status === 'completed') cls = 'success';
        else if (status === 'failed') cls = 'danger';
        else if (status === 'processing') cls = 'warning';
        return '<span class="badge badge-' + cls + '">' + status + '</span>';
    }

    function prependLogRow(log) {
        var emptyRow = document.getElementById('backupLogEmpty');
        if (emptyRow) {
            emptyRow.parentNode.removeChild(emptyRow);
        }

        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td>1</td>' +
            '<td>' + (log.created_at || '-') + '</td>' +
            '<td>' + (log.finished_at || '-') + '</td>' +
            '<td>' + (log.user_name || '-') + '</td>' +
            '<td><code>' + (log.folder_path_label || log.folder_name || '-') + '</code></td>' +
            '<td>' + (log.database_name || '-') + '</td>' +
            '<td class="text-center">' + (log.total_tables || 0) + '</td>' +
            '<td class="text-center">' + (log.total_files || 0) + '</td>' +
            '<td>' + statusBadge(log.status || 'processing') + '</td>' +
            '<td>' + (log.note || '-') + '</td>';

        logBody.insertBefore(tr, logBody.firstChild);

        var rows = logBody.querySelectorAll('tr');
        for (var i = 0; i < rows.length; i++) {
            rows[i].cells[0].textContent = String(i + 1);
        }
    }

    async function fetchJson(url, options) {
        var resp = await fetch(url, options || {});
        var data = await resp.json();
        if (!resp.ok || !data.success) {
            throw new Error(data.message || 'Permintaan gagal.');
        }
        return data;
    }

    function getSelectedFormat() {
        var zipRadio = document.getElementById('formatZip');
        return (zipRadio && zipRadio.checked) ? 'zip' : 'asli';
    }

    function getFormatLabel(format) {
        return format === 'zip' ? 'Zip (.zip per tabel)' : 'Asli (.sql)';
    }

    async function writeBlobFile(dirHandle, filename, blobOrText) {
        var fileHandle = await dirHandle.getFileHandle(filename, { create: true });
        var writable = await fileHandle.createWritable();
        await writable.write(blobOrText);
        await writable.close();
    }

    async function writeSqlFile(dirHandle, filename, sqlContent) {
        await writeBlobFile(dirHandle, filename, sqlContent);
    }

    async function writeZipFile(dirHandle, zipFilename, zipBlob) {
        await writeBlobFile(dirHandle, zipFilename, zipBlob);
    }

    function countTasksForTable(table, format) {
        var rowCount = parseInt(table.row_count, 10) || 0;
        if (format === 'zip') {
            return 1;
        }
        if (rowCount <= 0) {
            return 1;
        }
        return Math.ceil(rowCount / CHUNK_SIZE);
    }

    function buildOffsets(rowCount) {
        var offsets = [];
        if (rowCount <= 0) {
            offsets.push(0);
        } else {
            for (var off = 0; off < rowCount; off += CHUNK_SIZE) {
                offsets.push(off);
            }
        }
        return offsets;
    }

    async function exportTableChunks(table) {
        var rowCount = parseInt(table.row_count, 10) || 0;
        var offsets = buildOffsets(rowCount);
        var files = [];

        for (var oi = 0; oi < offsets.length; oi++) {
            var offset = offsets[oi];
            var exportUrl = API.exportSql
                + '?table=' + encodeURIComponent(table.name)
                + '&offset=' + encodeURIComponent(offset);
            var exportData = await fetchJson(exportUrl);
            files.push({
                filename: exportData.filename,
                sql: exportData.sql
            });
        }

        return files;
    }

    async function backupTableAsli(backupDir, table, progressCallback) {
        var files = await exportTableChunks(table);
        for (var i = 0; i < files.length; i++) {
            await writeSqlFile(backupDir, files[i].filename, files[i].sql);
            if (progressCallback) {
                progressCallback(files[i].filename);
            }
        }
        return files.length;
    }

    async function backupTableZip(backupDir, table, progressCallback) {
        if (typeof JSZip === 'undefined') {
            throw new Error('Library JSZip belum dimuat. Muat ulang halaman lalu coba lagi.');
        }

        var files = await exportTableChunks(table);
        var zip = new JSZip();

        for (var i = 0; i < files.length; i++) {
            if (progressCallback) {
                progressCallback('Membuat zip: ' + table.name + ' / ' + files[i].filename);
            }
            zip.file(files[i].filename, files[i].sql);
        }

        var zipFilename = table.name + '.zip';
        if (progressCallback) {
            progressCallback('Menyimpan ' + zipFilename);
        }

        var zipBlob = await zip.generateAsync({
            type: 'blob',
            compression: 'DEFLATE',
            compressionOptions: { level: 6 }
        });

        await writeZipFile(backupDir, zipFilename, zipBlob);
        return 1;
    }

    async function startBackup() {
        if (isRunning) {
            return;
        }

        if (!window.showDirectoryPicker) {
            alert('Browser Anda tidak mendukung pemilihan folder lokal.\nGunakan Google Chrome atau Microsoft Edge untuk testing backup ke folder laptop.');
            return;
        }

        isRunning = true;
        btn.disabled = true;
        progressWrap.style.display = 'block';
        statusLabel.textContent = 'Memilih folder...';
        setProgress(0, 100, 'Menunggu pemilihan folder lokal.');

        var logId = null;
        var folderName = '';
        var parentFolderLabel = '';
        var totalFiles = 0;
        var totalTasks = 0;
        var doneTasks = 0;
        var fileFormat = getSelectedFormat();

        try {
            var rootDir = await window.showDirectoryPicker({ mode: 'readwrite' });
            parentFolderLabel = rootDir.name || 'Folder lokal terpilih';

            var startedAt = new Date();
            folderName = buildFolderName(startedAt);
            var backupDir = await rootDir.getDirectoryHandle(folderName, { create: true });

            statusLabel.textContent = 'Mengambil daftar tabel...';
            var tablesData = await fetchJson(API.getTables);
            var tables = tablesData.tables || [];

            tables.forEach(function (t) {
                totalTasks += countTasksForTable(t, fileFormat);
            });

            var formatLabel = getFormatLabel(fileFormat);
            var saveResp = await fetchJson(API.saveLog, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    folder_name: folderName,
                    folder_path_label: parentFolderLabel + ' / ' + folderName,
                    total_tables: tables.length,
                    total_files: 0,
                    status: 'processing',
                    note: 'Backup dimulai | Format: ' + formatLabel
                })
            });
            logId = saveResp.log_id;

            prependLogRow({
                created_at: startedAt.getFullYear() + '-' + pad2(startedAt.getMonth() + 1) + '-' + pad2(startedAt.getDate()) + ' '
                    + pad2(startedAt.getHours()) + ':' + pad2(startedAt.getMinutes()) + ':' + pad2(startedAt.getSeconds()),
                finished_at: '-',
                user_name: <?php echo json_encode($this->session->userdata('full_name') ?: $this->session->userdata('email')); ?>,
                folder_name: folderName,
                folder_path_label: parentFolderLabel + ' / ' + folderName,
                database_name: tablesData.database,
                total_tables: tables.length,
                total_files: 0,
                status: 'processing',
                note: 'Backup dimulai | Format: ' + formatLabel
            });

            for (var ti = 0; ti < tables.length; ti++) {
                var table = tables[ti];
                statusLabel.textContent = 'Backup: ' + table.name + ' (' + formatLabel + ')';

                var savedCount = 0;
                if (fileFormat === 'zip') {
                    savedCount = await backupTableZip(backupDir, table, function (detailText) {
                        statusLabel.textContent = 'Backup: ' + table.name;
                        setProgress(doneTasks, totalTasks, detailText);
                    });
                    doneTasks += 1;
                } else {
                    savedCount = await backupTableAsli(backupDir, table, function (detailText) {
                        statusLabel.textContent = 'Backup: ' + table.name;
                        doneTasks += 1;
                        setProgress(doneTasks, totalTasks, detailText + ' selesai.');
                    });
                }

                totalFiles += savedCount;
                setProgress(doneTasks, totalTasks, table.name + ' selesai.');
            }

            var finishedAt = new Date();
            var fileTypeText = (fileFormat === 'zip') ? 'file ZIP' : 'file SQL';
            var finishNote = 'Backup selesai | Format: ' + formatLabel + ' | Total ' + fileTypeText + ': ' + totalFiles;

            await fetchJson(API.updateLog, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    log_id: logId,
                    total_files: totalFiles,
                    status: 'completed',
                    note: finishNote
                })
            });

            if (logBody.rows.length > 0) {
                var firstRow = logBody.rows[0];
                firstRow.cells[2].textContent = finishedAt.getFullYear() + '-' + pad2(finishedAt.getMonth() + 1) + '-' + pad2(finishedAt.getDate()) + ' '
                    + pad2(finishedAt.getHours()) + ':' + pad2(finishedAt.getMinutes()) + ':' + pad2(finishedAt.getSeconds());
                firstRow.cells[7].textContent = String(totalFiles);
                firstRow.cells[8].innerHTML = statusBadge('completed');
                firstRow.cells[9].textContent = finishNote;
            }

            statusLabel.textContent = 'Backup selesai. ' + totalFiles + ' ' + fileTypeText + ' disimpan di ' + folderName;
            setProgress(totalTasks, totalTasks, 'Selesai.');
            alert('Backup database berhasil!\n\nFormat: ' + formatLabel + '\nFolder: ' + parentFolderLabel + ' / ' + folderName + '\nTotal ' + fileTypeText + ': ' + totalFiles);
        } catch (err) {
            console.error(err);
            if (logId) {
                try {
                    await fetchJson(API.updateLog, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            log_id: logId,
                            total_files: totalFiles,
                            status: 'failed',
                            note: err.message || 'Backup gagal'
                        })
                    });
                } catch (e2) {
                    console.error(e2);
                }
            }

            statusLabel.textContent = 'Backup gagal: ' + (err.message || err.name || 'Unknown error');
            alert('Backup gagal: ' + (err.message || err.name || 'Unknown error'));
        } finally {
            isRunning = false;
            btn.disabled = false;
        }
    }

    btn.addEventListener('click', startBackup);
})();
</script>
