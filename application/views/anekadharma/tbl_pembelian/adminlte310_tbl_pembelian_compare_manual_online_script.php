<?php if (!isset($url_compare_jurnal_pembelian_run)) { return; } ?>
<script>
    window.addEventListener('load', function() {
        if (!window.jQuery) {
            console.error('Compare Pembelian: jQuery belum dimuat. Muat ulang halaman.');
            return;
        }

        var urlCompareJurnalPembelianRun = <?php echo json_encode($url_compare_jurnal_pembelian_run); ?>;
        var urlCompareJurnalPembelianExcel = <?php echo json_encode($url_compare_jurnal_pembelian_excel); ?>;
        var urlCompareJurnalPembelianExcelAll = <?php echo json_encode($url_compare_jurnal_pembelian_excel_all); ?>;
        var urlCompareJurnalPembelianImportCsv = <?php echo json_encode($url_compare_jurnal_pembelian_import_csv); ?>;
        var urlCompareJurnalPembelianCheckCsv = <?php echo json_encode($url_compare_jurnal_pembelian_check_csv); ?>;
        var urlCompareJurnalPembelianValidateCsv = <?php echo json_encode($url_compare_jurnal_pembelian_validate_csv); ?>;
        var urlCompareJurnalPembelianTabelList = <?php echo json_encode($url_compare_jurnal_pembelian_tabel_list); ?>;
        var urlCompareJurnalPembelianTabelPreview = <?php echo json_encode($url_compare_jurnal_pembelian_tabel_preview); ?>;
        var comparePembelianLastResult = null;
        var comparePembelianDtInstances = {};
        var comparePembelianTablesLoaded = false;
        var comparePembelianCsvLastUpload = null;
        var comparePembelianCsvPreviewDt = null;
        var comparePembelianExcelAllReady = false;

        function toggleComparePembelianExcelAllButton(show) {
            comparePembelianExcelAllReady = !!show;
            jQuery('#btn-compare-pembelian-excel-all').toggleClass('d-none', !comparePembelianExcelAllReady);
        }

        function hideComparePembelianExcelAllButton() {
            toggleComparePembelianExcelAllButton(false);
        }

        function getTglAwalComparePembelianRef() {
            var el = document.querySelector('#form-cari-setting-kode-akun input[name="tgl_awal"]');
            if (el && String(el.value || '').trim() !== '') {
                return String(el.value || '').trim();
            }
            var bulanKey = getBulanKeyComparePembelian();
            return bulanKey ? (bulanKey + '-01') : '';
        }

        function resetComparePembelianCsvInput() {
            jQuery('#compare_pembelian_csv_file').val('');
            jQuery('#compare_pembelian_csv_file').next('.custom-file-label').text('Cari / pilih file CSV...');
        }

        function parseComparePembelianAjaxResponse(xhr) {
            if (!xhr) {
                return null;
            }
            if (xhr.responseJSON) {
                return xhr.responseJSON;
            }
            if (!xhr.responseText) {
                return null;
            }
            try {
                return JSON.parse(xhr.responseText);
            } catch (e) {
                return null;
            }
        }

        function showComparePembelianCsvProcessing(fileName) {
            fileName = fileName || '';
            var safeName = fileName ? jQuery('<span>').text(fileName).html() : '';
            setComparePembelianStatus('info', '<i class="fas fa-spinner fa-spin"></i> <strong>Memproses CSV'
                + (safeName ? ': ' + safeName : '')
                + '</strong> — membuat tabel baru, menyesuaikan kolom, dan meng-upload data...');
            if (!window.Swal) {
                return;
            }
            Swal.fire({
                title: 'Memproses CSV...',
                html: (safeName ? '<p class="mb-2"><strong>File:</strong> ' + safeName + '</p>' : '')
                    + '<p class="mb-0">Membuat tabel baru, menyesuaikan kolom, dan meng-upload data.</p>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        function showComparePembelianCsvError(message, title) {
            title = title || 'Validasi CSV gagal';
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    html: '<div style="text-align:left;white-space:pre-wrap;font-size:14px;">' + jQuery('<div>').text(message || '').html() + '</div>'
                });
            } else {
                alert((title ? title + '\n\n' : '') + (message || 'Validasi CSV gagal.'));
            }
        }

        function showComparePembelianUploadSuccess(res) {
            var tableName = (res && res.table) ? res.table : '—';
            var rows = (res && res.rows) ? res.rows : 0;
            var msg = (res && res.message) ? String(res.message).replace(/\n/g, '<br/>') : 'Tabel baru berhasil dibuat.';
            var html = '<div style="text-align:center;">'
                + '<div style="font-size:48px;line-height:1;margin-bottom:8px;">✅</div>'
                + '<p style="margin:0 0 8px;font-weight:600;color:#155724;">Upload CSV berhasil!</p>'
                + '<p style="margin:0 0 4px;"><strong>Tabel:</strong> <code>' + tableName + '</code></p>'
                + '<p style="margin:0 0 12px;"><strong>Baris data:</strong> ' + rows + '</p>'
                + '<p style="margin:0 0 12px;font-size:13px;color:#495057;">Klik tombol <strong>Cek Data</strong> pada kotak info upload untuk melihat preview isi tabel di database.</p>'
                + '<div style="text-align:left;font-size:13px;color:#495057;background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;padding:10px;">' + msg + '</div>'
                + '</div>';

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tabel Baru Berhasil Dibuat',
                    html: html,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    timerProgressBar: true,
                    allowOutsideClick: true,
                    customClass: {
                        popup: 'swal-pembelian-upload-success'
                    }
                });
            } else {
                alert('Upload berhasil. Tabel: ' + tableName);
            }
        }

        function startImportComparePembelianCsv(file) {
            if (!file) {
                return;
            }

            var ext = (file.name || '').split('.').pop().toLowerCase();
            if (ext !== 'csv') {
                showComparePembelianCsvError('File harus berformat .csv', 'Perhatian');
                resetComparePembelianCsvInput();
                return;
            }

            showComparePembelianCsvProcessing(file.name || '');

            var formData = new FormData();
            formData.append('csv_file', file);
            formData.append('bulan_num', jQuery('#compare_bulan_pembelian').val() || '');
            formData.append('tahun', jQuery('#compare_tahun_pembelian').val() || '');
            formData.append('tgl_awal', getTglAwalComparePembelianRef());

            jQuery.ajax({
                url: urlCompareJurnalPembelianImportCsv,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function(res) {
                if (window.Swal) {
                    Swal.close();
                }
                if (!res || !res.ok) {
                    showComparePembelianCsvError((res && res.message) ? res.message : 'Gagal import CSV.', 'Import CSV gagal');
                    resetComparePembelianCsvInput();
                    return;
                }

                comparePembelianTablesLoaded = false;
                loadComparePembelianTableList(true, res.table || '');
                updateComparePembelianCsvUploadInfo({
                    file: res.file || file.name,
                    table: res.table || '',
                    rows: res.rows || 0
                });
                setComparePembelianStatus('success', (res.message || 'Import CSV berhasil.').replace(/\n/g, '<br/>'));
                showComparePembelianUploadSuccess(res);
                resetComparePembelianCsvInput();
            }).fail(function(xhr) {
                if (window.Swal) {
                    Swal.close();
                }
                var parsed = parseComparePembelianAjaxResponse(xhr);
                var msg = (parsed && parsed.message)
                    ? parsed.message
                    : 'Import CSV gagal. Periksa koneksi server atau muat ulang halaman.';
                if (xhr && xhr.status) {
                    msg += ' (HTTP ' + xhr.status + ')';
                }
                setComparePembelianStatus('danger', msg);
                showComparePembelianCsvError(msg, 'Import CSV gagal');
                resetComparePembelianCsvInput();
            });
        }

        function getBulanKeyComparePembelian() {
            var bulan = parseInt(jQuery('#compare_bulan_pembelian').val(), 10);
            var tahun = parseInt(jQuery('#compare_tahun_pembelian').val(), 10);
            if (!bulan || !tahun) {
                return '';
            }
            return tahun + '-' + String(bulan).padStart(2, '0');
        }

        function toggleComparePembelianButton() {
            var show = getBulanKeyComparePembelian() !== '' && (jQuery('#compare_tabel_pembelian').val() || '') !== '';
            jQuery('#btn-compare-jurnal-pembelian').toggleClass('d-none', !show);
            if (!show) {
                hideComparePembelianExcelAllButton();
            }
        }

        function setComparePembelianStatus(type, html) {
            var $el = jQuery('#compare-pembelian-status');
            $el.removeClass('alert-info alert-success alert-danger alert-warning');
            $el.addClass(type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info')));
            $el.html(html);
        }

        function updateComparePembelianInfoRingkas(res) {
            res = res || comparePembelianLastResult || {};
            var stats = res.stats || {};
            jQuery('#compare-pembelian-label-bulan').text(res.bulan_label || getBulanKeyComparePembelian() || '—');
            jQuery('#compare-pembelian-label-tabel').text(res.table || jQuery('#compare_tabel_pembelian').val() || '—');
            jQuery('#compare-pembelian-count-manual').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : '—');
            jQuery('#compare-pembelian-count-online').text(typeof stats.hanya_online !== 'undefined' ? stats.hanya_online : '—');
            jQuery('#compare-pembelian-count-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : '—');
            jQuery('#compare-pembelian-count-tidak-lengkap-manual').text(typeof stats.tidak_lengkap_manual !== 'undefined' ? stats.tidak_lengkap_manual : '—');
            jQuery('#compare-pembelian-count-tidak-lengkap-online').text(typeof stats.tidak_lengkap_online !== 'undefined' ? stats.tidak_lengkap_online : '—');
            jQuery('#compare-pembelian-badge-manual').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : 0);
            jQuery('#compare-pembelian-badge-online').text(typeof stats.hanya_online !== 'undefined' ? stats.hanya_online : 0);
            jQuery('#compare-pembelian-badge-cocok').text(typeof stats.cocok !== 'undefined' ? stats.cocok : 0);
            jQuery('#compare-pembelian-badge-tidak-lengkap-manual').text(typeof stats.tidak_lengkap_manual !== 'undefined' ? stats.tidak_lengkap_manual : 0);
            jQuery('#compare-pembelian-badge-tidak-lengkap-online').text(typeof stats.tidak_lengkap_online !== 'undefined' ? stats.tidak_lengkap_online : 0);
            jQuery('#compare-pembelian-badge-manual-duplikat').text(typeof stats.hanya_manual !== 'undefined' ? stats.hanya_manual : 0);
        }

        function fillComparePembelianTableSelect(tables, selectTable) {
            var $sel = jQuery('#compare_tabel_pembelian');
            var cur = selectTable || $sel.val();
            $sel.find('option:not(:first)').remove();
            (tables || []).forEach(function(tbl) {
                $sel.append(jQuery('<option>', { value: tbl, text: tbl }));
            });
            if (cur) {
                $sel.val(cur);
            }
            toggleComparePembelianButton();
        }

        function loadComparePembelianTableList(force, selectTable) {
            if (comparePembelianTablesLoaded && !force) {
                if (selectTable) {
                    jQuery('#compare_tabel_pembelian').val(selectTable);
                }
                toggleComparePembelianButton();
                return;
            }
            jQuery('#compare_tabel_pembelian').prop('disabled', true);
            jQuery.ajax({
                url: urlCompareJurnalPembelianTabelList,
                type: 'POST',
                dataType: 'json'
            }).done(function(res) {
                if (!res || !res.ok) {
                    setComparePembelianStatus('danger', (res && res.message) ? res.message : 'Gagal memuat daftar tabel.');
                    return;
                }
                fillComparePembelianTableSelect(res.tables || [], selectTable);
                comparePembelianTablesLoaded = true;
            }).fail(function() {
                setComparePembelianStatus('danger', 'Tidak dapat memuat daftar tabel dari server.');
            }).always(function() {
                jQuery('#compare_tabel_pembelian').prop('disabled', false);
                toggleComparePembelianButton();
            });
        }

        function buildComparePembelianRows(items) {
            return (items || []).map(function(it, i) {
                return [
                    i + 1,
                    it.tanggal || '',
                    it.spop || '',
                    it.supplier || '',
                    it.jumlah || '',
                    it.keterangan || ''
                ];
            });
        }

        function renderComparePembelianTable(tableId, items) {
            var $table = jQuery(tableId);
            if (!$table.length) {
                return;
            }
            if (jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().clear().destroy();
            }
            $table.find('tbody').empty();
            var rows = buildComparePembelianRows(items);
            var dt = $table.DataTable({
                data: rows,
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                scrollX: true,
                autoWidth: false,
                pageLength: 25,
                columnDefs: [
                    { targets: 5, className: 'text-wrap', width: '280px' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
                }
            });
            comparePembelianDtInstances[tableId] = dt;
        }

        function renderComparePembelianAllTables(res) {
            renderComparePembelianTable('#table-compare-pembelian-manual', res.hanya_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-cocok', res.cocok || []);
            renderComparePembelianTable('#table-compare-pembelian-online', res.hanya_online || []);
            renderComparePembelianTable('#table-compare-pembelian-manual-duplikat', res.hanya_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-tidak-lengkap-manual', res.tidak_lengkap_manual || []);
            renderComparePembelianTable('#table-compare-pembelian-tidak-lengkap-online', res.tidak_lengkap_online || []);
        }

        function runCompareJurnalPembelian() {
            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan dan tahun.' });
                } else {
                    alert('Pilih bulan dan tahun.');
                }
                return;
            }
            if (!tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tabel database yang akan dibandingkan.' });
                } else {
                    alert('Pilih tabel database yang akan dibandingkan.');
                }
                return;
            }

            setComparePembelianStatus('info', '<i class="fas fa-spinner fa-spin"></i> Membandingkan tabel <strong>' + tabel + '</strong> dengan tbl_pembelian...');

            jQuery.ajax({
                url: urlCompareJurnalPembelianRun,
                type: 'POST',
                dataType: 'json',
                data: {
                    bulan: bulanKey,
                    bulan_num: jQuery('#compare_bulan_pembelian').val(),
                    tahun: jQuery('#compare_tahun_pembelian').val(),
                    tabel: tabel
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    hideComparePembelianExcelAllButton();
                    setComparePembelianStatus('danger', (res && res.message) ? res.message : 'Compare gagal.');
                    if (window.Swal) {
                        Swal.fire({ icon: 'error', title: 'Compare gagal', text: (res && res.message) ? res.message : 'Gagal compare.' });
                    }
                    return;
                }
                comparePembelianLastResult = res;
                renderComparePembelianAllTables(res);
                updateComparePembelianInfoRingkas(res);
                toggleComparePembelianExcelAllButton(true);
                var s = res.stats || {};
                setComparePembelianStatus('success', 'Compare selesai — Manual tidak di online: <strong>' + (s.hanya_manual || 0) + '</strong>, '
                    + 'Online tidak di manual: <strong>' + (s.hanya_online || 0) + '</strong>, '
                    + 'Cocok: <strong>' + (s.cocok || 0) + '</strong>, '
                    + 'Tidak lengkap manual: <strong>' + (s.tidak_lengkap_manual || 0) + '</strong>, '
                    + 'Tidak lengkap online: <strong>' + (s.tidak_lengkap_online || 0) + '</strong>. Tabel: <strong>' + (res.table || tabel) + '</strong>.');
            }).fail(function() {
                hideComparePembelianExcelAllButton();
                setComparePembelianStatus('danger', 'Tidak dapat menghubungi server.');
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghubungi server.' });
                }
            });
        }

        function updateComparePembelianCsvUploadInfo(data) {
            var $box = jQuery('#compare-pembelian-csv-upload-info');
            if (!data || !data.table) {
                comparePembelianCsvLastUpload = null;
                $box.addClass('d-none');
                jQuery('#compare-pembelian-csv-filename').text('—');
                jQuery('#compare-pembelian-csv-tablename').text('—');
                jQuery('#compare-pembelian-csv-rowcount').text('');
                return;
            }
            comparePembelianCsvLastUpload = {
                file: data.file || '',
                table: data.table || '',
                rows: data.rows || 0
            };
            jQuery('#compare-pembelian-csv-filename').text(comparePembelianCsvLastUpload.file || '—');
            jQuery('#compare-pembelian-csv-tablename').text(comparePembelianCsvLastUpload.table || '—');
            jQuery('#compare-pembelian-csv-rowcount').text(
                comparePembelianCsvLastUpload.rows ? (' (' + comparePembelianCsvLastUpload.rows + ' baris)') : ''
            );
            $box.removeClass('d-none');
        }

        function renderComparePembelianCsvPreviewTable(res) {
            res = res || {};
            var cols = res.columns || [];
            var rows = res.rows || [];
            var $table = jQuery('#table-compare-pembelian-csv-preview');

            if (jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
            }
            $table.find('thead tr').empty();
            $table.find('tbody').empty();

            cols.forEach(function(col) {
                $table.find('thead tr').append(jQuery('<th>').text(col));
            });

            var dtRows = rows.map(function(row) {
                return cols.map(function(col) {
                    return (row && row[col] != null) ? String(row[col]) : '';
                });
            });

            comparePembelianCsvPreviewDt = $table.DataTable({
                data: dtRows,
                scrollX: true,
                scrollY: 400,
                scrollCollapse: true,
                paging: true,
                pageLength: 25,
                order: [],
                autoWidth: false,
                deferRender: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/id.json'
                }
            });

            setTimeout(function() {
                if (comparePembelianCsvPreviewDt && comparePembelianCsvPreviewDt.columns) {
                    try {
                        comparePembelianCsvPreviewDt.columns.adjust().draw(false);
                    } catch (eAdj) {}
                }
            }, 200);
        }

        function openComparePembelianCsvPreviewModal(table, fileLabel) {
            table = (table || '').trim();
            if (!table) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Belum ada tabel hasil upload CSV untuk ditampilkan.' });
                } else {
                    alert('Belum ada tabel hasil upload CSV untuk ditampilkan.');
                }
                return;
            }

            jQuery('#compare-pembelian-csv-preview-meta').text('Memuat data tabel `' + table + '`...');
            jQuery('#compare-pembelian-csv-preview-loading').removeClass('d-none');
            jQuery('.compare-pembelian-csv-preview-dt-wrap').addClass('d-none');
            jQuery('#modal-compare-pembelian-csv-preview').modal('show');

            jQuery.ajax({
                url: urlCompareJurnalPembelianTabelPreview,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: {
                    tabel: table,
                    limit: 1000
                }
            }).done(function(res) {
                jQuery('#compare-pembelian-csv-preview-loading').addClass('d-none');
                jQuery('.compare-pembelian-csv-preview-dt-wrap').removeClass('d-none');
                if (!res || !res.ok) {
                    jQuery('#compare-pembelian-csv-preview-meta').text((res && res.message) ? res.message : 'Gagal memuat data tabel.');
                    renderComparePembelianCsvPreviewTable({ columns: [], rows: [] });
                    return;
                }
                var meta = 'File: ' + (fileLabel || '—')
                    + ' | Tabel: `' + (res.table || table) + '`'
                    + ' | Total: ' + (res.total || 0) + ' baris';
                if (res.truncated) {
                    meta += ' (ditampilkan ' + (res.shown || 0) + ' baris pertama)';
                }
                jQuery('#compare-pembelian-csv-preview-meta').text(meta);
                jQuery('#modalComparePembelianCsvPreviewLabel').text('Data Tabel — ' + (res.table || table));
                renderComparePembelianCsvPreviewTable(res);
            }).fail(function(xhr) {
                jQuery('#compare-pembelian-csv-preview-loading').addClass('d-none');
                jQuery('.compare-pembelian-csv-preview-dt-wrap').removeClass('d-none');
                var parsed = parseComparePembelianAjaxResponse(xhr);
                var msg = (parsed && parsed.message)
                    ? parsed.message
                    : 'Gagal memuat preview tabel. Periksa koneksi server.';
                jQuery('#compare-pembelian-csv-preview-meta').text(msg);
                renderComparePembelianCsvPreviewTable({ columns: [], rows: [] });
            });
        }

        function exportComparePembelianExcelAll() {
            if (!comparePembelianExcelAllReady) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Jalankan Compare terlebih dahulu sebelum cetak Excel.' });
                } else {
                    alert('Jalankan Compare terlebih dahulu sebelum cetak Excel.');
                }
                return;
            }

            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey || !tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan, tahun, dan tabel terlebih dahulu.' });
                }
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlCompareJurnalPembelianExcelAll;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan: bulanKey,
                bulan_num: jQuery('#compare_bulan_pembelian').val(),
                tahun: jQuery('#compare_tahun_pembelian').val(),
                tabel: tabel
            };

            Object.keys(fields).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function exportComparePembelianExcel(jenis) {
            if (!comparePembelianLastResult) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Jalankan Compare terlebih dahulu sebelum cetak Excel.' });
                } else {
                    alert('Jalankan Compare terlebih dahulu sebelum cetak Excel.');
                }
                return;
            }

            var bulanKey = getBulanKeyComparePembelian();
            var tabel = jQuery('#compare_tabel_pembelian').val() || '';
            if (!bulanKey || !tabel) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih bulan, tahun, dan tabel terlebih dahulu.' });
                }
                return;
            }

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = urlCompareJurnalPembelianExcel;
            form.target = '_blank';
            form.style.display = 'none';

            var fields = {
                bulan: bulanKey,
                bulan_num: jQuery('#compare_bulan_pembelian').val(),
                tahun: jQuery('#compare_tahun_pembelian').val(),
                jenis: jenis,
                tabel: tabel
            };

            Object.keys(fields).forEach(function(key) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        jQuery(document).on('click', '#btn-compare-pembelian-csv-cek-data', function() {
            if (!comparePembelianCsvLastUpload || !comparePembelianCsvLastUpload.table) {
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Upload CSV terlebih dahulu.' });
                } else {
                    alert('Upload CSV terlebih dahulu.');
                }
                return;
            }
            openComparePembelianCsvPreviewModal(
                comparePembelianCsvLastUpload.table,
                comparePembelianCsvLastUpload.file
            );
        });

        jQuery('#modal-compare-pembelian-csv-preview').on('hidden.bs.modal', function() {
            var $table = jQuery('#table-compare-pembelian-csv-preview');
            if (jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
                $table.find('thead tr').empty();
                $table.find('tbody').empty();
            }
            comparePembelianCsvPreviewDt = null;
        });

        jQuery(document).on('change', '#compare_pembelian_csv_file', function() {
            var file = (this.files && this.files[0]) ? this.files[0] : null;
            var label = file ? file.name : 'Cari / pilih file CSV...';
            jQuery(this).next('.custom-file-label').text(label);
            if (!file) {
                resetComparePembelianCsvInput();
                return;
            }
            startImportComparePembelianCsv(file);
        });

        jQuery('#compare_bulan_pembelian, #compare_tahun_pembelian, #compare_tabel_pembelian').on('change', function() {
            hideComparePembelianExcelAllButton();
            toggleComparePembelianButton();
            updateComparePembelianInfoRingkas({
                bulan_label: getBulanKeyComparePembelian(),
                table: jQuery('#compare_tabel_pembelian').val()
            });
        });

        jQuery(document).on('click', '#btn-compare-jurnal-pembelian', function() {
            runCompareJurnalPembelian();
        });

        jQuery(document).on('click', '#btn-compare-pembelian-excel-all', function() {
            exportComparePembelianExcelAll();
        });

        jQuery(document).on('click', '.btn-compare-pembelian-excel', function() {
            exportComparePembelianExcel(jQuery(this).attr('data-jenis') || '');
        });

        jQuery(document).on('shown.bs.tab', function(e) {
            var target = jQuery(e.target).attr('href') || '';
            if (target === '#panel-compare-manual-online') {
                loadComparePembelianTableList(false);
                jQuery.each(comparePembelianDtInstances, function(id, dt) {
                    if (dt && dt.columns) {
                        dt.columns.adjust();
                    }
                });
            }
        });

        if (jQuery('#panel-compare-manual-online').hasClass('active') || jQuery('#panel-compare-manual-online').hasClass('show')) {
            loadComparePembelianTableList(false);
        }
        toggleComparePembelianButton();
    });
</script>
