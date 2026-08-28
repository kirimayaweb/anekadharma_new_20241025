<?php
if (!isset($pj_ref_cfg) || !is_array($pj_ref_cfg)) {
	$pj_ref_cfg = array();
}
$pj_ref_defaults = array(
	'table_sel' => '#tglSPOPFreezeBelumPersediaan',
	'subtabs_content' => '#penjualan-persediaan-subtabs-content',
	'subtabs_ul' => '#penjualan-persediaan-subtabs',
	'main_tab_href' => '#tab-penjualan-belum-persediaan',
	'main_tabs' => '#penjualan-proses-bayar-tabs',
	'badge_link' => '#tab-penjualan-belum-persediaan-link',
	'session_subtab_key' => 'pj_persediaan_subtab',
	'subtab_manual_id' => 'subtab-persediaan-manual',
);
$pj_ref_cfg = array_merge($pj_ref_defaults, $pj_ref_cfg);
?>
<script>
(function() {
	var cfg = <?php echo json_encode($pj_ref_cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        function escapeHtmlPj(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function initBelumPersediaanReferensi() {
            if (!window.jQuery || !jQuery.fn.DataTable) {
                return;
            }

            var urlList = <?php echo json_encode(isset($url_penjualan_referensi_list) ? $url_penjualan_referensi_list : site_url('Tbl_penjualan/ajax_referensi_persediaan_list')); ?>;
            var urlApply = <?php echo json_encode(isset($url_penjualan_referensi_apply) ? $url_penjualan_referensi_apply : site_url('Tbl_penjualan/ajax_referensi_persediaan_apply')); ?>;
            var bulanDefault = <?php echo json_encode(isset($penjualan_bulan_referensi) ? $penjualan_bulan_referensi : ''); ?>;

            var tableSel = cfg.table_sel;
            jQuery('#modal-pj-referensi-persediaan, #modal-pj-isi-jumlah-refered').appendTo('body');

            function normSatuanPj(s) {
                return String(s == null ? '' : s).toLowerCase().replace(/\s+/g, ' ').trim();
            }
            function satuanCocokPj(a, b) {
                a = normSatuanPj(a);
                b = normSatuanPj(b);
                if (a === '' || b === '') {
                    return true;
                }
                if (a === b) {
                    return true;
                }
                var n = Math.min(a.length, b.length);
                if (n >= 3 && a.slice(0, n) === b.slice(0, n)) {
                    return true;
                }
                if (a.indexOf(b) === 0 || b.indexOf(a) === 0) {
                    return true;
                }
                return false;
            }
            function parseAngkaPjRef(v) {
                var s = String(v == null ? '' : v).trim();
                if (!s || s === '-') {
                    return 0;
                }
                if (s.indexOf(',') >= 0) {
                    s = s.replace(/\./g, '').replace(',', '.');
                } else if (/^\d{1,3}(\.\d{3})+$/.test(s)) {
                    s = s.replace(/\./g, '');
                }
                s = s.replace(/[^0-9.\-]/g, '');
                var n = parseFloat(s);
                return isNaN(n) ? 0 : n;
            }
            function stokPersediaanPjRef(row) {
                if (!row) {
                    return 0;
                }
                if (row.total_10_stok != null && row.total_10_stok !== '') {
                    var nStok = parseFloat(row.total_10_stok);
                    if (!isNaN(nStok)) {
                        return nStok;
                    }
                }
                return parseAngkaPjRef(row.total_10);
            }
            function notifyPjReferensi(msg, icon) {
                icon = icon || 'warning';
                var cls = (icon === 'error') ? 'danger' : 'warning';
                jQuery('#pj-referensi-alert').html('<div class="alert alert-' + cls + ' py-2 mb-0">' + escapeHtmlPj(msg) + '</div>');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: icon, title: (icon === 'error' ? 'Gagal' : 'Perhatian'), text: msg });
                    setTimeout(function() {
                        jQuery('.swal2-container').css('z-index', 20000);
                    }, 30);
                } else {
                    window.alert(msg);
                }
            }
            function showPjIsiJumlahModal() {
                var $m = jQuery('#modal-pj-isi-jumlah-refered');
                if ($m.length && $m.parent()[0] !== document.body) {
                    $m.appendTo('body');
                }
                $m.css('z-index', 1065).modal('show');
            }

            function parseAngkaPersediaanDt(val) {
                if (val == null || val === '') {
                    return 0;
                }
                var s = String(val).replace(/<[^>]+>/g, '').trim();
                if (s === '') {
                    return 0;
                }
                if (s.indexOf(',') >= 0) {
                    s = s.replace(/\./g, '').replace(/,/g, '.');
                } else {
                    s = s.replace(/[^\d.-]/g, '');
                }
                var n = parseFloat(s);
                return isNaN(n) ? 0 : n;
            }

            function formatAngkaPersediaanDt(n, maxDec) {
                var dec = (typeof maxDec === 'number') ? maxDec : 2;
                var rounded = Math.round(n * Math.pow(10, dec)) / Math.pow(10, dec);
                var parts = String(rounded).split('.');
                var intPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                if (dec === 0 || !parts[1]) {
                    return intPart;
                }
                var frac = (parts[1] || '').slice(0, dec);
                while (frac.length < dec) {
                    frac += '0';
                }
                return intPart + ',' + frac;
            }

            function buildPersediaanFooterCallback(hasReferensi) {
                return function() {
                    var api = this.api();
                    var totalJumlah = 0;
                    var totalHarga = 0;
                    api.rows({ search: 'applied' }).every(function() {
                        var node = this.node();
                        if (!node) {
                            return;
                        }
                        var $row = jQuery(node);
                        var jNum = $row.find('td.pj-persediaan-col-jumlah').attr('data-num');
                        var hNum = $row.find('td.pj-persediaan-col-harga-total').attr('data-num');
                        totalJumlah += parseAngkaPersediaanDt(jNum != null ? jNum : $row.find('td.pj-persediaan-col-jumlah').text());
                        totalHarga += parseAngkaPersediaanDt(hNum != null ? hNum : $row.find('td.pj-persediaan-col-harga-total').text());
                    });
                    var jDec = (Math.abs(totalJumlah - Math.round(totalJumlah)) < 0.0001) ? 0 : 2;
                    jQuery(api.table().footer()).find('.pj-persediaan-foot-jumlah').html(formatAngkaPersediaanDt(totalJumlah, jDec));
                    jQuery(api.table().footer()).find('.pj-persediaan-foot-harga-total').html(formatAngkaPersediaanDt(totalHarga, 2));
                };
            }

            function initPersediaanDtTable(sel) {
                if (!sel || !jQuery(sel).length || jQuery.fn.DataTable.isDataTable(sel)) {
                    if (sel && jQuery.fn.DataTable.isDataTable(sel)) {
                        try { jQuery(sel).DataTable().columns.adjust(); } catch (eAdj) {}
                    }
                    return;
                }
                var hasReferensi = jQuery(sel + ' thead th').filter(function() {
                    return jQuery(this).text().trim() === 'Referensi';
                }).length > 0;
                var colJumlah = hasReferensi ? 5 : 4;
                var colHargaTotal = hasReferensi ? 7 : 6;
                var dtOpts = {
                    scrollX: true,
                    pageLength: 10,
                    order: [[hasReferensi ? 2 : 1, 'asc']],
                    language: {
                        emptyTable: 'Tidak ada data'
                    },
                    footerCallback: buildPersediaanFooterCallback(hasReferensi),
                    columnDefs: [
                        { targets: [colJumlah, colHargaTotal], className: 'text-right' }
                    ]
                };
                if (hasReferensi) {
                    dtOpts.columnDefs.push({ targets: [1], orderable: false });
                    dtOpts.language.emptyTable = 'Semua penjualan sudah terverifikasi ke persediaan';
                }
                jQuery(sel).DataTable(dtOpts);
            }

            function initVisiblePersediaanSubtabDt() {
                var $activePane = jQuery(cfg.subtabs_content + ' .tab-pane.active');
                if (!$activePane.length) {
                    initPersediaanDtTable(tableSel);
                    return;
                }
                var $tbl = $activePane.find('table.penjualan-persediaan-dt-table').first();
                if ($tbl.length && $tbl.attr('id')) {
                    initPersediaanDtTable('#' + $tbl.attr('id'));
                }
            }

            initVisiblePersediaanSubtabDt();

            try {
                var savedSub = sessionStorage.getItem(cfg.session_subtab_key);
                if (savedSub) {
                    sessionStorage.removeItem(cfg.session_subtab_key);
                    jQuery(cfg.main_tabs + ' a[href="' + cfg.main_tab_href + '"]').tab('show');
                    setTimeout(function() {
                        jQuery(cfg.subtabs_ul + ' a[href="#' + savedSub + '"]').tab('show');
                    }, 250);
                }
            } catch (eSub) {}

            jQuery(cfg.subtabs_ul + ' a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var href = jQuery(e.target).attr('href') || '';
                if (href.charAt(0) === '#') {
                    var $tbl = jQuery(href).find('table.penjualan-persediaan-dt-table').first();
                    if ($tbl.length && $tbl.attr('id')) {
                        setTimeout(function() {
                            initPersediaanDtTable('#' + $tbl.attr('id'));
                        }, 50);
                    }
                }
            });

            jQuery(cfg.main_tabs + ' a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var href = jQuery(e.target).attr('href') || '';
                if (href === cfg.main_tab_href) {
                    setTimeout(initVisiblePersediaanSubtabDt, 80);
                }
            });

            var refState = { idPenjualan: 0, bulanKey: '', meta: null, dt: null, persediaanMap: {} };

            function resolveBulanReferensi() {
                if (bulanDefault && /^\d{4}-\d{2}$/.test(bulanDefault)) {
                    return bulanDefault;
                }
                var tgl = typeof getTanggalFilterPenjualan === 'function' ? getTanggalFilterPenjualan() : { awal: '' };
                var raw = (tgl && tgl.awal) ? String(tgl.awal) : '';
                var m = raw.match(/(\d{4})[-\/](\d{1,2})/);
                if (m) {
                    return m[1] + '-' + ('0' + m[2]).slice(-2);
                }
                // d-m-Y
                var m2 = raw.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/);
                if (m2) {
                    return m2[3] + '-' + ('0' + m2[2]).slice(-2);
                }
                return '';
            }

            function updateBelumPersediaanBadge(count) {
                var $badge = jQuery(cfg.badge_link + ' .badge-count');
                if ($badge.length) {
                    $badge.text(String(count));
                }
            }

            function removeBelumPersediaanRow(idPenjualan) {
                if (!jQuery.fn.DataTable.isDataTable(tableSel)) {
                    jQuery(tableSel + ' tr[data-penjualan-id="' + idPenjualan + '"]').remove();
                    updateBelumPersediaanBadge(jQuery(tableSel + ' tbody tr[data-penjualan-id]').length);
                    return;
                }
                var dt = jQuery(tableSel).DataTable();
                var removed = false;
                dt.rows().every(function() {
                    var node = this.node();
                    if (!node) {
                        return;
                    }
                    var id = parseInt(jQuery(node).attr('data-penjualan-id'), 10) || 0;
                    if (id === idPenjualan) {
                        dt.row(node).remove();
                        removed = true;
                    }
                });
                if (removed) {
                    dt.draw(false);
                }
                updateBelumPersediaanBadge(dt.rows().count());
            }

            function openPjReferensiModal(idPenjualan, meta) {
                var bulanKey = resolveBulanReferensi();
                if (!bulanKey) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Bulan tidak dikenali', text: 'Set tanggal filter penjualan dulu.' });
                    } else {
                        alert('Bulan tidak dikenali. Set tanggal filter penjualan dulu.');
                    }
                    return;
                }
                refState.idPenjualan = idPenjualan;
                refState.bulanKey = bulanKey;
                refState.meta = meta || {};
                jQuery('#pj-referensi-alert').empty();
                jQuery('#pj-referensi-meta').html(
                    '<div class="text-dark">'
                    + 'Penjualan ID <strong>' + idPenjualan + '</strong> â€” '
                    + '<strong style="font-size:1.35rem;">' + escapeHtmlPj(refState.meta.nama || '') + '</strong>'
                    + ' / ' + escapeHtmlPj(refState.meta.satuan || '')
                    + ' qty <strong>' + escapeHtmlPj(String(refState.meta.jumlah || '')) + '</strong>'
                    + ' &nbsp;|&nbsp; Persediaan bulan <strong>' + escapeHtmlPj(bulanKey) + '</strong>'
                    + '</div>'
                    + '<div class="small mt-1 text-muted">'
                    + 'Klik <strong>Refered</strong> untuk membuka modal isi jumlah. '
                    + 'Warna kolom <strong>Satuan</strong> &amp; <strong>Total 10</strong>: '
                    + '<span style="color:#006400;font-weight:600;">hijau tua</span> = cocok/cukup, '
                    + '<span style="color:#d4a017;font-weight:600;">kuning</span> = perlu dicek (modal tetap dibuka).'
                    + '</div>'
                );
                jQuery('#pj-referensi-loading').removeClass('d-none');
                if (refState.dt && jQuery.fn.DataTable.isDataTable('#tbl-pj-referensi-persediaan')) {
                    try { jQuery('#tbl-pj-referensi-persediaan').DataTable().destroy(); } catch (eD) {}
                    refState.dt = null;
                }
                jQuery('#tbl-pj-referensi-persediaan tbody').empty();
                jQuery('#modal-pj-referensi-persediaan').modal('show');

                var qtyRef = parseAngkaPjRef(refState.meta.jumlah);
                var satRef = normSatuanPj(refState.meta.satuan);
                refState.persediaanMap = {};

                jQuery.ajax({
                    url: urlList,
                    type: 'POST',
                    dataType: 'json',
                    data: { bulan: bulanKey, id_penjualan: idPenjualan }
                }).done(function(res) {
                    jQuery('#pj-referensi-loading').addClass('d-none');
                    if (!res || !res.ok) {
                        jQuery('#pj-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPj((res && res.message) ? res.message : 'Gagal memuat persediaan.') + '</div>');
                        return;
                    }
                    var rows = (res.rows || []).map(function(r) {
                        var idp = parseInt(r.id, 10) || 0;
                        if (idp > 0) {
                            refState.persediaanMap[idp] = r;
                        }
                        var total10 = stokPersediaanPjRef(r);
                        var satuanOk = satuanCocokPj(satRef, r.satuan);
                        var stokOk = (total10 >= qtyRef && qtyRef > 0);
                        var boleh = satuanOk && stokOk;
                        var colorYellow = '#d4a017';
                        var colorGreen = '#006400';
                        var colorSatuan = satuanOk ? colorGreen : colorYellow;
                        var colorTotal = stokOk ? colorGreen : colorYellow;
                        var btnClass = boleh ? 'btn-success' : 'btn-warning';
                        var btnTitle = boleh
                            ? 'Satuan cocok dan total_10 cukup â€” klik untuk isi jumlah'
                            : 'Klik untuk lihat detail dan isi jumlah';
                        return [
                            '<button type="button" class="btn btn-xs ' + btnClass + ' btn-pj-refered"'
                                + ' data-id-persediaan="' + idp + '"'
                                + ' title="' + escapeHtmlPj(btnTitle) + '"'
                                + ' style="color:#fff;">Refered</button>',
                            idp,
                            '<span style="color:#212529;font-weight:600;">' + escapeHtmlPj(r.namabarang || '') + '</span>',
                            '<span style="color:' + colorSatuan + ';font-weight:700;">' + escapeHtmlPj(r.satuan || '') + '</span>',
                            escapeHtmlPj(r.hpp || ''),
                            escapeHtmlPj(r.sa || ''),
                            escapeHtmlPj(r.beli || ''),
                            escapeHtmlPj(r.penjualan || ''),
                            '<span style="color:' + colorTotal + ';font-weight:700;">' + escapeHtmlPj(r.total_10 || '') + '</span>'
                        ];
                    });
                    refState.dt = jQuery('#tbl-pj-referensi-persediaan').DataTable({
                        data: rows,
                        pageLength: 10,
                        scrollX: true,
                        order: [[2, 'asc']],
                        columnDefs: [{ targets: [0], orderable: false }],
                        language: {
                            search: 'Cari:',
                            lengthMenu: 'Tampil _MENU_',
                            info: 'Menampilkan _START_â€“_END_ dari _TOTAL_',
                            paginate: { previous: 'Prev', next: 'Next' },
                            emptyTable: 'Tidak ada data persediaan di bulan ini'
                        }
                    });
                }).fail(function() {
                    jQuery('#pj-referensi-loading').addClass('d-none');
                    jQuery('#pj-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
                });
            }

            jQuery(document).on('click', '.btn-pj-referensi-persediaan', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = jQuery(this);
                var idPj = parseInt($btn.attr('data-id-penjualan'), 10) || 0;
                if (idPj < 1) {
                    notifyPjReferensi('ID penjualan tidak valid.', 'error');
                    return;
                }
                openPjReferensiModal(idPj, {
                    nama: $btn.attr('data-nama-barang') || '',
                    satuan: $btn.attr('data-satuan') || '',
                    jumlah: $btn.attr('data-jumlah') || '',
                    konsumen: $btn.attr('data-konsumen') || '',
                    unit: $btn.attr('data-unit') || ''
                });
            });

            jQuery(document).on('click', '.btn-pj-refered', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $btnClick = jQuery(this);
                var idPers = parseInt($btnClick.attr('data-id-persediaan'), 10) || 0;
                var idPj = refState.idPenjualan || 0;
                var bulanKey = refState.bulanKey || resolveBulanReferensi();
                if (idPers < 1 || idPj < 1 || !bulanKey) {
                    notifyPjReferensi('Data referensi tidak lengkap (ID persediaan / penjualan / bulan). Buka ulang tombol Referensi.', 'error');
                    return;
                }

                var rowPers = refState.persediaanMap[idPers] || {};
                var qtyRef = parseAngkaPjRef(refState.meta && refState.meta.jumlah);
                var satRef = (refState.meta && refState.meta.satuan) ? refState.meta.satuan : '';
                var satPers = rowPers.satuan || $btnClick.attr('data-satuan') || '';
                var total10 = stokPersediaanPjRef(rowPers);
                if (!total10 && $btnClick.attr('data-total10')) {
                    total10 = parseAngkaPjRef($btnClick.attr('data-total10'));
                }
                var satuanOk = satuanCocokPj(satRef, satPers);
                var namaPers = rowPers.namabarang || $btnClick.attr('data-nama-barang') || '';
                var defaultJumlah = qtyRef > 0 ? qtyRef : 1;
                if (defaultJumlah < 1) {
                    defaultJumlah = 1;
                }
                var maxJumlah = qtyRef > 0 ? qtyRef : (total10 > 0 ? total10 : defaultJumlah);

                var warnings = [];
                if (!satuanOk) {
                    warnings.push('Satuan penjualan ("' + satRef + '") berbeda dari persediaan ("' + satPers + '").');
                }
                if (total10 <= 0) {
                    warnings.push('total_10 persediaan kosong / 0 â€” simpan akan ditolak sampai stok cukup.');
                } else if (qtyRef > 0 && total10 < qtyRef) {
                    warnings.push('total_10 (' + total10 + ') lebih kecil dari qty jual (' + qtyRef + '). Kurangi jumlah sebelum simpan.');
                }

                jQuery('#pj-isi-jumlah-alert').html(
                    warnings.length
                        ? '<div class="alert alert-warning py-2 mb-0">' + escapeHtmlPj(warnings.join(' ')) + '</div>'
                        : ''
                );
                jQuery('#pj-isi-jumlah-nama').val(namaPers);
                jQuery('#pj-isi-jumlah-satuan').val(satPers);
                jQuery('#pj-isi-jumlah-d-id').text(String(idPers));
                jQuery('#pj-isi-jumlah-d-nama').html('<strong>' + escapeHtmlPj(namaPers) + '</strong>');
                jQuery('#pj-isi-jumlah-d-kode').text((rowPers.kode_barang || '-') + ' / ' + (rowPers.spop || '-'));
                jQuery('#pj-isi-jumlah-d-satuan').text(satPers || '-');
                jQuery('#pj-isi-jumlah-d-hpp').text(rowPers.hpp || '-');
                jQuery('#pj-isi-jumlah-d-mutasi').text(
                    'SA ' + (rowPers.sa || '0')
                    + ' | Beli ' + (rowPers.beli || '0')
                    + ' | Penjualan ' + (rowPers.penjualan || '0')
                    + (rowPers.pecah_satuan ? (' | Pecah ' + rowPers.pecah_satuan) : '')
                    + (rowPers.bahan_produksi ? (' | Produksi ' + rowPers.bahan_produksi) : '')
                );
                jQuery('#pj-isi-jumlah-d-total10').html('<strong>' + escapeHtmlPj(String(rowPers.total_10 != null ? rowPers.total_10 : total10)) + '</strong>');
                jQuery('#pj-isi-jumlah-d-uuid').html('<small>' + escapeHtmlPj(rowPers.uuid_persediaan || '-') + '</small>');
                jQuery('#pj-isi-jumlah-label').text('Jumlah (qty jual = ' + qtyRef + ' , total_10 = ' + total10 + ')');
                jQuery('#pj-isi-jumlah-input').attr('max', maxJumlah).attr('min', 1).val(defaultJumlah);
                jQuery('#pj-isi-jumlah-meta-qty').html(
                    '<div><strong>Penjualan</strong> ID ' + idPj
                    + ' â€” <strong>' + escapeHtmlPj((refState.meta && refState.meta.nama) ? refState.meta.nama : '') + '</strong>'
                    + ' / ' + escapeHtmlPj(satRef)
                    + ' â€” qty jual <strong style="font-size:1.15rem;">' + qtyRef + '</strong></div>'
                    + '<div class="mt-1">Konsumen: <strong>' + escapeHtmlPj((refState.meta && refState.meta.konsumen) ? refState.meta.konsumen : '-') + '</strong>'
                    + ' &nbsp;|&nbsp; Unit: <strong>' + escapeHtmlPj((refState.meta && refState.meta.unit) ? refState.meta.unit : '-') + '</strong>'
                    + ' &nbsp;|&nbsp; Bulan: <strong>' + escapeHtmlPj(bulanKey) + '</strong></div>'
                    + '<div class="mt-1 text-muted">Default jumlah = qty penjualan. Ubah jika perlu, lalu SIMPAN.</div>'
                );
                jQuery('#pj-isi-jumlah-id-persediaan').val(String(idPers));
                jQuery('#pj-isi-jumlah-id-penjualan').val(String(idPj));
                jQuery('#pj-isi-jumlah-bulan').val(bulanKey);
                jQuery('#pj-isi-jumlah-force').val('0');
                jQuery('#btn-pj-isi-jumlah-simpan').prop('disabled', false);
                showPjIsiJumlahModal();
            });

            function submitPjIsiJumlahRefered(forceFlag) {
                var idPers = parseInt(jQuery('#pj-isi-jumlah-id-persediaan').val(), 10) || 0;
                var idPj = parseInt(jQuery('#pj-isi-jumlah-id-penjualan').val(), 10) || 0;
                var bulanKey = jQuery('#pj-isi-jumlah-bulan').val() || '';
                var jumlah = parseFloat(jQuery('#pj-isi-jumlah-input').val()) || 0;
                var maxJumlah = parseFloat(jQuery('#pj-isi-jumlah-input').attr('max')) || 0;
                if (idPers < 1 || idPj < 1 || !bulanKey) {
                    return;
                }
                if (jumlah < 1 || (maxJumlah > 0 && jumlah > maxJumlah)) {
                    jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">Jumlah harus antara 1 dan ' + maxJumlah + '.</div>');
                    return;
                }

                var $btnSimpan = jQuery('#btn-pj-isi-jumlah-simpan').prop('disabled', true);
                jQuery.ajax({
                    url: urlApply,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        bulan: bulanKey,
                        id_penjualan: idPj,
                        id_persediaan: idPers,
                        jumlah: jumlah,
                        force: forceFlag ? '1' : '0'
                    }
                }).done(function(res) {
                    if (res && res.need_confirm_uuid) {
                        $btnSimpan.prop('disabled', false);
                        var msg = (res && res.message) ? res.message : 'Ada uuid sinkron di pembelian &amp; persediaan.';
                        var sid = parseInt(res.suggested_id_persediaan, 10) || 0;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'UUID sinkron ditemukan',
                                html: escapeHtmlPj(msg)
                                    + (sid > 0 ? '<br/><br/>Sarankan persediaan ID: <strong>' + sid + '</strong>' : '')
                                    + '<br/><small>Batal lalu pilih record yang disarankan, atau lanjut paksa ke record ini.</small>',
                                showCancelButton: true,
                                confirmButtonText: 'Lanjut paksa SIMPAN',
                                cancelButtonText: 'Batal / pilih ulang'
                            }).then(function(result) {
                                if (result.isConfirmed) {
                                    jQuery('#pj-isi-jumlah-force').val('1');
                                    submitPjIsiJumlahRefered(true);
                                }
                            });
                        } else {
                            if (window.confirm(msg + '\n\nOK = lanjut paksa, Cancel = batal')) {
                                submitPjIsiJumlahRefered(true);
                            }
                        }
                        jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">' + escapeHtmlPj(msg) + '</div>');
                        return;
                    }
                    if (!res || !res.ok) {
                        jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPj((res && res.message) ? res.message : 'Gagal simpan.') + '</div>');
                        $btnSimpan.prop('disabled', false);
                        return;
                    }
                    jQuery('#modal-pj-isi-jumlah-refered').modal('hide');
                    jQuery('#modal-pj-referensi-persediaan').modal('hide');
                    $btnSimpan.prop('disabled', false);
                    var doneMsg = res.message || ('Persediaan id=' + idPers + ' di-update sejumlah ' + jumlah);
                    if (typeof Swal !== 'undefined') {
                        try { sessionStorage.setItem(cfg.session_subtab_key, cfg.subtab_manual_id); } catch (eSs) {}
                        Swal.fire({
                            icon: 'success',
                            title: 'Refered berhasil',
                            text: doneMsg
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        try { sessionStorage.setItem(cfg.session_subtab_key, cfg.subtab_manual_id); } catch (eSs2) {}
                        alert(doneMsg);
                        window.location.reload();
                    }
                }).fail(function() {
                    jQuery('#pj-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
                    $btnSimpan.prop('disabled', false);
                });
            }

            jQuery('#form-pj-isi-jumlah-refered').on('submit', function(e) {
                e.preventDefault();
                submitPjIsiJumlahRefered(jQuery('#pj-isi-jumlah-force').val() === '1');
            });

            jQuery('#modal-pj-referensi-persediaan').on('shown.bs.modal', function() {
                if (refState.dt) {
                    try { refState.dt.columns.adjust(); } catch (eAdj) {}
                }
            });

            jQuery('#modal-pj-isi-jumlah-refered').on('shown.bs.modal', function() {
                jQuery('.modal-backdrop').last().addClass('pj-refered-jumlah-backdrop').css('z-index', 1060);
                jQuery('#pj-isi-jumlah-input').trigger('focus').select();
            });

            jQuery(document).on('click', '.btn-cetak-excel-persediaan-section', function(e) {
                e.preventDefault();
                var tableSelExport = jQuery(this).data('table') || tableSel;
                var filename = jQuery(this).data('filename') || 'Penjualan_Persediaan';
                var $table = jQuery(tableSelExport);
                if (!$table.length) {
                    return;
                }
                var headers = [];
                $table.find('thead tr:first th').each(function() {
                    headers.push(jQuery(this).text().trim());
                });
                var rows = [];
                if (jQuery.fn.DataTable.isDataTable(tableSelExport)) {
                    jQuery(tableSelExport).DataTable().rows({ search: 'applied' }).every(function() {
                        var d = this.data();
                        var line = [];
                        for (var i = 0; i < headers.length; i++) {
                            var cell = d[i];
                            line.push(cell == null ? '' : String(cell).replace(/<[^>]+>/g, '').trim());
                        }
                        rows.push(line);
                    });
                }
                function escXml(s) {
                    return String(s == null ? '' : s)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');
                }
                var xml = '<' + '?xml version="1.0"?>' + '<' + '?mso-application progid="Excel.Sheet"?>'
                    + '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
                    + ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
                    + '<Worksheet ss:Name="Data"><Table>';
                xml += '<Row>';
                headers.forEach(function(h) {
                    xml += '<Cell><Data ss:Type="String">' + escXml(h) + '</Data></Cell>';
                });
                xml += '</Row>';
                rows.forEach(function(line) {
                    xml += '<Row>';
                    line.forEach(function(c) {
                        xml += '<Cell><Data ss:Type="String">' + escXml(c) + '</Data></Cell>';
                    });
                    xml += '</Row>';
                });
                var $footCells = $table.find('tfoot tr:first th');
                if ($footCells.length) {
                    xml += '<Row>';
                    $footCells.each(function() {
                        xml += '<Cell><Data ss:Type="String">' + escXml(jQuery(this).text().trim()) + '</Data></Cell>';
                    });
                    xml += '</Row>';
                }
                xml += '</Table></Worksheet></Workbook>';
                var blob = new Blob([xml], { type: 'application/vnd.ms-excel' });
                var link = document.createElement('a');
                var objectUrl = URL.createObjectURL(blob);
                link.href = objectUrl;
                link.download = filename + '_' + (new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-')) + '.xls';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(objectUrl);
            });
        }

	if (document.readyState === 'complete') {
		initBelumPersediaanReferensi();
	} else {
		window.addEventListener('load', initBelumPersediaanReferensi);
	}
})();
</script>
