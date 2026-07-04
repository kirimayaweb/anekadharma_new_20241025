<script>
(function() {
    function bootLabarugiKa() {
        var $ = window.jQuery;
        if (!$ || !$.fn || !$.fn.DataTable) {
            setTimeout(bootLabarugiKa, 50);
            return;
        }

        var labarugiKaUrls = {
            list: <?php echo json_encode(site_url('Tbl_laba_rugi/ajax_labarugi_setting_kode_akun')); ?>,
            pilih: <?php echo json_encode(site_url('Tbl_laba_rugi/ajax_labarugi_setting_kode_akun_pilih')); ?>,
            hapus: <?php echo json_encode(site_url('Tbl_laba_rugi/ajax_labarugi_setting_kode_akun_hapus')); ?>,
            transaksi: <?php echo json_encode(site_url('Tbl_laba_rugi/ajax_labarugi_transaksi_unit')); ?>,
            nominal: <?php echo json_encode(site_url('Tbl_laba_rugi/ajax_labarugi_nominal_unit')); ?>
        };

        var currentKa = { uuid: '', jenisTab: '', namaKeterangan: '', tahun: 0, bulan: 0 };
        var dtAvail = null;
        var dtSel = null;
        var dtTrx = null;
        var kaDtReady = false;
        var kaLoadSeq = 0;
        var kaDtLang = {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ baris',
            info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
            paginate: { previous: 'Sebelum', next: 'Berikut' },
            processing: 'Memproses...',
            zeroRecords: 'Data tidak ditemukan'
        };

        function escHtml(v) {
            return String(v || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function setKaLoading(on) {
            var $overlay = $('#labarugiKaLoading');
            if (on) {
                $overlay.addClass('is-active').attr('aria-hidden', 'false');
            } else {
                $overlay.removeClass('is-active').attr('aria-hidden', 'true');
            }
        }

        function pilihBtnHtml(kode) {
            return '<button type="button" class="btn btn-success btn-xs btn-labarugi-ka-pilih" data-kode="' + escHtml(kode) + '">Pilih</button>';
        }

        function hapusBtnHtml(kode) {
            return '<button type="button" class="btn btn-danger btn-xs btn-labarugi-ka-hapus" data-kode="' + escHtml(kode) + '">Hapus</button>';
        }

        function kaDtOptions(zeroRecords) {
            return {
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
                order: [[0, 'asc']],
                deferRender: true,
                processing: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                dom: 'frtip',
                retrieve: true,
                columnDefs: [{ targets: 2, orderable: false, searchable: false, className: 'text-center' }],
                language: $.extend({}, kaDtLang, { zeroRecords: zeroRecords || 'Data tidak ditemukan' })
            };
        }

        function ensureKaTables() {
            if (kaDtReady) {
                return;
            }
            dtSel = $('#tblLabarugiKodeAkunSelected').DataTable(kaDtOptions('Belum ada kode akun terpilih.'));
            dtAvail = $('#tblLabarugiKodeAkunAvailable').DataTable(kaDtOptions('Semua kode akun sudah dipilih.'));
            kaDtReady = true;
        }

        function fillKaTable(dt, rows, mode) {
            dt.clear();
            (rows || []).forEach(function(row) {
                var btn = mode === 'pilih' ? pilihBtnHtml(row.kode_akun) : hapusBtnHtml(row.kode_akun);
                dt.row.add([row.kode_akun || '', row.nama_akun || '', btn]);
            });
            dt.draw(false);
        }

        function renderKaTables(payload) {
            ensureKaTables();
            fillKaTable(dtSel, payload.terpilih || [], 'hapus');
            fillKaTable(dtAvail, payload.available || [], 'pilih');
        }

        function removeRowByKode(dt, kode) {
            dt.rows(function(idx, data) {
                return String(data[0]) === String(kode);
            }).remove();
            dt.draw(false);
        }

        function moveRowToSelected(row) {
            if (!row || !row.kode_akun || !kaDtReady) {
                return;
            }
            removeRowByKode(dtAvail, row.kode_akun);
            dtSel.row.add([row.kode_akun, row.nama_akun || '', hapusBtnHtml(row.kode_akun)]).draw(false);
        }

        function moveRowToAvailable(row) {
            if (!row || !row.kode_akun || !kaDtReady) {
                return;
            }
            removeRowByKode(dtSel, row.kode_akun);
            dtAvail.row.add([row.kode_akun, row.nama_akun || '', pilihBtnHtml(row.kode_akun)]).draw(false);
        }

        function getSelectedRowData(kode) {
            if (!kaDtReady) {
                return null;
            }
            var found = null;
            dtSel.rows().every(function() {
                var data = this.data();
                if (String(data[0]) === String(kode)) {
                    found = { kode_akun: data[0], nama_akun: data[1] };
                    return false;
                }
            });
            return found;
        }

        function refreshGridNominals(ketKey) {
            if (typeof window.labarugiRefreshGridMatch !== 'function') {
                return;
            }
            window.setTimeout(function() {
                $('.labarugi-grid-ns-nominal, .labarugi-ns-nominal-utama').each(function() {
                    var $el = $(this);
                    if (ketKey && ($el.attr('data-ket-key') || '') !== ketKey) {
                        return;
                    }
                    var viewMode = $el.attr('data-view-mode') || 'unit';
                    $.getJSON(labarugiKaUrls.nominal, {
                        uuid_nama_keterangan: $el.attr('data-ket-key'),
                        jenis_tab: $el.attr('data-jenis-tab') || (viewMode === 'utama' ? 'utama' : 'rinci'),
                        view_mode: viewMode,
                        unit: $el.attr('data-unit') || '',
                        unit_label: $el.attr('data-unit-label') || '',
                        tahun: $el.attr('data-tahun'),
                        bulan: $el.attr('data-bulan')
                    }).done(function(res) {
                        if (res && res.ok) {
                            $el.text(res.nominal_formatted || '0,00');
                            $el.attr('data-nominal', res.nominal || 0);
                            var $group = $el.closest('.labarugi-grid-input-group');
                            if ($group.length) {
                                window.labarugiRefreshGridMatch($group[0]);
                            }
                        }
                    });
                });
            }, 0);
        }

        function loadKodeAkun(forceReload) {
            if (!currentKa.uuid) {
                return $.Deferred().reject().promise();
            }

            var seq = ++kaLoadSeq;
            ensureKaTables();
            setKaLoading(true);

            return $.getJSON(labarugiKaUrls.list + '/' + encodeURIComponent(currentKa.uuid), {
                jenis_tab: currentKa.jenisTab,
                nama_keterangan: currentKa.namaKeterangan
            }).done(function(res) {
                if (seq !== kaLoadSeq) {
                    return;
                }
                if (!res || !res.ok) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal memuat data kode akun.' });
                    return;
                }
                var label = res.nama_keterangan || currentKa.namaKeterangan || currentKa.uuid;
                $('#labarugiKaModalKetLabel, #labarugiKaSelectedKetLabel').text(label);
                renderKaTables(res);
            }).fail(function(xhr) {
                if (seq !== kaLoadSeq) {
                    return;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data kode akun. (' + xhr.status + ')' });
            }).always(function() {
                if (seq === kaLoadSeq) {
                    setKaLoading(false);
                }
            });
        }

        function openKaModal(uuid, jenisTab, namaKeterangan, tahun, bulan) {
            currentKa.uuid = uuid;
            currentKa.jenisTab = jenisTab;
            currentKa.namaKeterangan = namaKeterangan;
            currentKa.tahun = tahun;
            currentKa.bulan = bulan;

            var $modal = $('#modalLabarugiKodeAkun');
            if ($modal.length && !$modal.parent().is('body')) {
                $modal.appendTo(document.body);
            }

            $('#labarugiKaModalKetLabel, #labarugiKaSelectedKetLabel').text(namaKeterangan);
            ensureKaTables();
            $modal.modal('show');
            loadKodeAkun(true);
        }

        $(document).on('click', '.labarugi-btn-setting-kode-akun', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(this);
            var $wrap = $btn.closest('.labarugi-unit-grid-wrap');
            if (!$wrap.length) {
                $wrap = $btn.closest('.labarugi-utama-wrap');
            }
            openKaModal(
                ($btn.attr('data-ket-key') || '').trim(),
                ($btn.attr('data-jenis-tab') || 'rinci').trim(),
                ($btn.attr('data-ket-label') || '').trim(),
                parseInt($wrap.attr('data-tahun') || '0', 10),
                parseInt($wrap.attr('data-bulan') || '0', 10)
            );
        });

        $(document).on('click', '.btn-labarugi-ka-pilih', function(e) {
            e.preventDefault();
            var kode = $(this).data('kode');
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(labarugiKaUrls.pilih, {
                uuid_nama_keterangan: currentKa.uuid,
                kode_akun: kode,
                status_labarugi: currentKa.jenisTab,
                nama_keterangan: currentKa.namaKeterangan
            }).done(function(res) {
                if (!res || !res.ok) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal memilih kode akun.' });
                    return;
                }
                if (res.row) {
                    moveRowToSelected(res.row);
                } else {
                    loadKodeAkun(true);
                }
                refreshGridNominals(currentKa.uuid);
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        $(document).on('click', '.btn-labarugi-ka-hapus', function(e) {
            e.preventDefault();
            var kode = $(this).data('kode');
            var rowData = getSelectedRowData(kode);
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(labarugiKaUrls.hapus, {
                uuid_nama_keterangan: currentKa.uuid,
                kode_akun: kode,
                status_labarugi: currentKa.jenisTab
            }).done(function(res) {
                if (!res || !res.ok) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal menghapus kode akun.' });
                    return;
                }
                if (rowData) {
                    moveRowToAvailable(rowData);
                } else {
                    loadKodeAkun(true);
                }
                refreshGridNominals(currentKa.uuid);
            }).always(function() {
                $btn.prop('disabled', false);
            });
        });

        function loadTransaksiModal($el) {
            var viewMode = ($el.attr('data-view-mode') || 'unit').trim();
            var params = {
                uuid_nama_keterangan: $el.attr('data-ket-key'),
                jenis_tab: $el.attr('data-jenis-tab'),
                view_mode: viewMode,
                unit: $el.attr('data-unit') || '',
                unit_label: $el.attr('data-unit-label') || '',
                nama_keterangan: $el.attr('data-ket-label'),
                tahun: $el.attr('data-tahun'),
                bulan: $el.attr('data-bulan')
            };
            $('#labarugiTrxKetLabel').text(params.nama_keterangan || '-');
            if (viewMode === 'utama') {
                $('#labarugiTrxUnitWrap').hide();
                $('#labarugiTrxUnitLabel').text('');
            } else {
                $('#labarugiTrxUnitWrap').show();
                $('#labarugiTrxUnitLabel').text(params.unit || '-');
            }
            $('#labarugiTrxInfo').hide().text('');
            $('#labarugiTrxLoading').show();
            $('#labarugiTrxTableWrap').hide();

            $.getJSON(labarugiKaUrls.transaksi, params).done(function(res) {
                var html = '';
                var rows = (res && res.data) ? res.data : [];
                rows.forEach(function(row) {
                    html += '<tr>' +
                        '<td>' + escHtml(row.tanggal) + '</td>' +
                        '<td>' + escHtml(row.pl) + '</td>' +
                        '<td>' + escHtml(row.kode) + '</td>' +
                        '<td>' + escHtml(row.kode_akun) + '</td>' +
                        '<td>' + escHtml(row.nama_akun) + '</td>' +
                        '<td class="text-right">' + escHtml(row.debet_formatted) + '</td>' +
                        '<td class="text-right">' + escHtml(row.kredit_formatted) + '</td>' +
                        '<td>' + escHtml(row.source_key) + '</td></tr>';
                });

                if (dtTrx && $.fn.DataTable.isDataTable('#tblLabarugiTransaksiUnit')) {
                    dtTrx.destroy();
                    dtTrx = null;
                }

                if (rows.length === 0) {
                    var infoMsg = (res && res.empty_message) ? res.empty_message : 'Tidak ada transaksi untuk kode akun terpilih.';
                    $('#labarugiTrxInfo').text(infoMsg).show();
                    $('#tblLabarugiTransaksiUnit tbody').html('<tr><td colspan="8" class="text-center text-muted">Tidak ada data transaksi.</td></tr>');
                } else {
                    $('#tblLabarugiTransaksiUnit tbody').html(html);
                }

                dtTrx = $('#tblLabarugiTransaksiUnit').DataTable({
                    pageLength: 15,
                    deferRender: true,
                    autoWidth: false,
                    destroy: true,
                    language: { zeroRecords: 'Tidak ada transaksi untuk kode akun terpilih.' }
                });
                $('#labarugiTrxTotal').text((res && res.total_formatted) ? res.total_formatted : '0,00');
                $('#labarugiTrxLoading').hide();
                $('#labarugiTrxTableWrap').show();
            }).fail(function() {
                $('#labarugiTrxLoading').hide();
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat rincian transaksi.' });
            });
        }

        $(document).on('click', '.labarugi-grid-ns-nominal, .labarugi-ns-nominal-utama', function(e) {
            e.preventDefault();
            var $el = $(this);
            var $modal = $('#modalLabarugiTransaksiUnit');
            if ($modal.length && !$modal.parent().is('body')) {
                $modal.appendTo(document.body);
            }
            $modal.modal('show');
            loadTransaksiModal($el);
        });
    }

    bootLabarugiKa();
})();
</script>
