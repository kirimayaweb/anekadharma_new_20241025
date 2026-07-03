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

        function escHtml(v) {
            return String(v || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function setKaLoading(on) {
            if (on) {
                $('#labarugiKaLoading').show();
                $('#labarugiKaTablesWrap').hide();
            } else {
                $('#labarugiKaLoading').hide();
                $('#labarugiKaTablesWrap').show();
            }
        }

        function initKaDataTablesOnce() {
            if (kaDtReady) { return; }
            dtAvail = $('#tblLabarugiKodeAkunAvailable').DataTable({
                pageLength: 15,
                deferRender: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ baris',
                    info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
                    paginate: { previous: 'Sebelum', next: 'Berikut' },
                    zeroRecords: 'Semua kode akun sudah dipilih.'
                }
            });
            dtSel = $('#tblLabarugiKodeAkunSelected').DataTable({
                pageLength: 15,
                deferRender: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                columnDefs: [{ targets: 1, className: 'text-right' }],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ baris',
                    info: 'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
                    paginate: { previous: 'Sebelum', next: 'Berikut' },
                    zeroRecords: 'Belum ada kode akun terpilih.'
                }
            });
            kaDtReady = true;
        }

        function renderKaTables(payload) {
            var availHtml = '';
            var selHtml = '';
            (payload.available || []).forEach(function(row) {
                availHtml += '<tr><td>' + escHtml(row.nama_akun) + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-success btn-xs btn-labarugi-ka-pilih" data-kode="' + escHtml(row.kode_akun) + '">Pilih</button></td></tr>';
            });
            (payload.terpilih || []).forEach(function(row) {
                selHtml += '<tr><td>' + escHtml(row.kode_akun) + '</td>' +
                    '<td class="text-right">' + escHtml(row.nominal_ns_formatted || '0,00') + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-labarugi-ka-hapus" data-kode="' + escHtml(row.kode_akun) + '">Hapus</button></td></tr>';
            });

            if (kaDtReady) {
                dtAvail.destroy();
                dtSel.destroy();
                kaDtReady = false;
                dtAvail = null;
                dtSel = null;
            }

            $('#tblLabarugiKodeAkunAvailable tbody').html(availHtml);
            $('#tblLabarugiKodeAkunSelected tbody').html(selHtml);
            initKaDataTablesOnce();
        }

        function refreshGridNominals() {
            if (typeof window.labarugiRefreshGridMatch === 'function') {
                $('.labarugi-grid-ns-nominal').each(function() {
                    var $el = $(this);
                    $.getJSON(labarugiKaUrls.nominal, {
                        uuid_nama_keterangan: $el.attr('data-ket-key'),
                        jenis_tab: $el.attr('data-jenis-tab'),
                        unit: $el.attr('data-unit'),
                        unit_label: $el.attr('data-unit-label'),
                        tahun: $el.attr('data-tahun'),
                        bulan: $el.attr('data-bulan')
                    }).done(function(res) {
                        if (res && res.ok) {
                            $el.text(res.nominal_formatted || '0,00');
                            $el.attr('data-nominal', res.nominal || 0);
                            var $group = $el.closest('.labarugi-grid-input-group');
                            if ($group.length) { window.labarugiRefreshGridMatch($group[0]); }
                        }
                    });
                });
            }
        }

        function loadKodeAkun() {
            if (!currentKa.uuid) { return $.Deferred().reject().promise(); }
            setKaLoading(true);
            return $.getJSON(labarugiKaUrls.list + '/' + encodeURIComponent(currentKa.uuid), {
                jenis_tab: currentKa.jenisTab,
                nama_keterangan: currentKa.namaKeterangan,
                tahun: currentKa.tahun,
                bulan: currentKa.bulan
            }).done(function(res) {
                if (!res || !res.ok) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal memuat data kode akun.' });
                    setKaLoading(false);
                    return;
                }
                var label = res.nama_keterangan || currentKa.namaKeterangan || currentKa.uuid;
                $('#labarugiKaModalKetLabel, #labarugiKaSelectedKetLabel').text(label);
                renderKaTables(res);
                setKaLoading(false);
            }).fail(function(xhr) {
                setKaLoading(false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memuat data kode akun. (' + xhr.status + ')' });
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
            $modal.modal('show');
            loadKodeAkun();
        }

        $(document).on('click', '.labarugi-btn-setting-kode-akun', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(this);
            var $wrap = $btn.closest('.labarugi-unit-grid-wrap');
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
                loadKodeAkun();
                refreshGridNominals();
            });
        });

        $(document).on('click', '.btn-labarugi-ka-hapus', function(e) {
            e.preventDefault();
            var kode = $(this).data('kode');
            $.post(labarugiKaUrls.hapus, {
                uuid_nama_keterangan: currentKa.uuid,
                kode_akun: kode,
                status_labarugi: currentKa.jenisTab
            }).done(function(res) {
                if (!res || !res.ok) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: (res && res.message) ? res.message : 'Gagal menghapus kode akun.' });
                    return;
                }
                loadKodeAkun();
                refreshGridNominals();
            });
        });

        function loadTransaksiModal($el) {
            var params = {
                uuid_nama_keterangan: $el.attr('data-ket-key'),
                jenis_tab: $el.attr('data-jenis-tab'),
                unit: $el.attr('data-unit'),
                unit_label: $el.attr('data-unit-label'),
                nama_keterangan: $el.attr('data-ket-label'),
                tahun: $el.attr('data-tahun'),
                bulan: $el.attr('data-bulan')
            };
            $('#labarugiTrxKetLabel').text(params.nama_keterangan || '-');
            $('#labarugiTrxUnitLabel').text(params.unit || '-');
            $('#labarugiTrxLoading').show();
            $('#labarugiTrxTableWrap').hide();

            $.getJSON(labarugiKaUrls.transaksi, params).done(function(res) {
                var html = '';
                (res.data || []).forEach(function(row) {
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
                $('#tblLabarugiTransaksiUnit tbody').html(html || '<tr><td colspan="8" class="text-center text-muted">Tidak ada transaksi.</td></tr>');
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

        $(document).on('click', '.labarugi-grid-ns-nominal', function(e) {
            e.preventDefault();
            var $el = $(this);
            var $modal = $('#modalLabarugiTransaksiUnit');
            if ($modal.length && !$modal.parent().is('body')) {
                $modal.appendTo(document.body);
            }
            $modal.modal('show');
            loadTransaksiModal($el);
        });

        $('#modalLabarugiKodeAkun').on('hidden.bs.modal', function() {
            if (kaDtReady && dtAvail && dtSel) {
                dtAvail.destroy();
                dtSel.destroy();
                kaDtReady = false;
                dtAvail = null;
                dtSel = null;
            }
        });
    }

    bootLabarugiKa();
})();
</script>
