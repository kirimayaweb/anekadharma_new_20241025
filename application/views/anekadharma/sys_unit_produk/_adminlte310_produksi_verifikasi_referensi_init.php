<?php
$url_produksi_referensi_list = isset($url_produksi_referensi_list)
    ? $url_produksi_referensi_list
    : site_url('Sys_unit_produk/ajax_referensi_persediaan_list');
$url_produksi_referensi_apply = isset($url_produksi_referensi_apply)
    ? $url_produksi_referensi_apply
    : site_url('Sys_unit_produk/ajax_referensi_persediaan_apply');
$bulan_produksi_referensi = isset($bulan_produksi_selected) ? (string) $bulan_produksi_selected : date('Y-m');
?>
<script>
(function() {
    function escapeHtmlProd(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function initProduksiVerifikasiReferensi() {
        if (!window.jQuery) {
            return;
        }
        var $ = jQuery;
        var urlList = <?php echo json_encode($url_produksi_referensi_list); ?>;
        var urlApply = <?php echo json_encode($url_produksi_referensi_apply); ?>;
        var bulanDefault = <?php echo json_encode($bulan_produksi_referensi); ?>;

        $('#modal-prod-referensi-persediaan, #modal-prod-isi-jumlah-refered').appendTo('body');

        function raiseSwalZIndex() {
            setTimeout(function() {
                $('.swal2-container').css('z-index', 20000);
            }, 30);
        }

        function normSatuanProd(s) {
            return String(s == null ? '' : s).toLowerCase().replace(/\s+/g, ' ').trim();
        }
        function satuanCocokProd(a, b) {
            a = normSatuanProd(a);
            b = normSatuanProd(b);
            if (a === '' || b === '') return true;
            if (a === b) return true;
            var n = Math.min(a.length, b.length);
            if (n >= 3 && a.slice(0, n) === b.slice(0, n)) return true;
            if (a.indexOf(b) === 0 || b.indexOf(a) === 0) return true;
            return false;
        }
        function parseAngkaProdRef(v) {
            var s = String(v == null ? '' : v).trim();
            if (!s || s === '-') return 0;
            if (s.indexOf(',') >= 0) {
                s = s.replace(/\./g, '').replace(',', '.');
            } else if (/^\d{1,3}(\.\d{3})+$/.test(s)) {
                s = s.replace(/\./g, '');
            }
            s = s.replace(/[^0-9.\-]/g, '');
            var n = parseFloat(s);
            return isNaN(n) ? 0 : n;
        }
        function stokPersediaanProdRef(row) {
            if (!row) return 0;
            if (row.total_10_stok != null && row.total_10_stok !== '') {
                var nStok = parseFloat(row.total_10_stok);
                if (!isNaN(nStok)) return nStok;
            }
            return parseAngkaProdRef(row.total_10);
        }
        function notifyProdReferensi(msg, icon) {
            icon = icon || 'warning';
            var cls = (icon === 'error') ? 'danger' : 'warning';
            $('#prod-referensi-alert').html('<div class="alert alert-' + cls + ' py-2 mb-0">' + escapeHtmlProd(msg) + '</div>');
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: icon, title: (icon === 'error' ? 'Gagal' : 'Perhatian'), text: msg });
                raiseSwalZIndex();
            } else {
                window.alert(msg);
            }
        }
        function resolveBulanProduksiReferensi() {
            var bulanInput = $('#bulan_produksi').val() || '';
            if (/^\d{4}-\d{2}$/.test(bulanInput)) {
                return bulanInput;
            }
            if (bulanDefault && /^\d{4}-\d{2}$/.test(bulanDefault)) {
                return bulanDefault;
            }
            return '';
        }
        function reloadVerifikasiSetelahRefered() {
            var bulan = resolveBulanProduksiReferensi();
            if (typeof loadVerifikasiByBulan === 'function' && bulan) {
                loadVerifikasiByBulan(bulan);
            } else {
                window.location.reload();
            }
        }
        function showProdIsiJumlahModal() {
            var $m = $('#modal-prod-isi-jumlah-refered');
            if ($m.length && $m.parent()[0] !== document.body) {
                $m.appendTo('body');
            }
            $m.css('z-index', 1065).modal({ backdrop: 'static', keyboard: true, show: true });
        }

        var refState = { idBahan: 0, bulanKey: '', meta: null, dt: null, persediaanMap: {}, isUpdate: false };
        var prodIsiJumlahSubmitting = false;

        function openProdReferensiModal(idBahan, meta, isUpdate) {
            var bulanKey = resolveBulanProduksiReferensi();
            if (!bulanKey) {
                notifyProdReferensi('Bulan produksi tidak dikenali. Pilih bulan dulu.', 'error');
                return;
            }
            refState.idBahan = idBahan;
            refState.bulanKey = bulanKey;
            refState.meta = meta || {};
            refState.isUpdate = !!isUpdate;
            $('#prod-referensi-alert').empty();
            var modeLabel = refState.isUpdate ? 'Update referensi manual' : 'Referensi baru';
            var refUuidInfo = refState.meta.refUuid
                ? (' &nbsp;|&nbsp; Ref. lama: <code>' + escapeHtmlProd(refState.meta.refUuid) + '</code>')
                : '';
            $('#prod-referensi-meta').html(
                '<div class="text-dark"><strong>' + modeLabel + '</strong> — '
                + 'Bahan produksi ID <strong>' + idBahan + '</strong> — '
                + '<strong>' + escapeHtmlProd(refState.meta.nama || '') + '</strong>'
                + ' / ' + escapeHtmlProd(refState.meta.satuan || '')
                + ' qty <strong>' + escapeHtmlProd(String(refState.meta.jumlah || '')) + '</strong>'
                + refUuidInfo
                + ' &nbsp;|&nbsp; Persediaan bulan <strong>' + escapeHtmlProd(bulanKey) + '</strong>'
                + '</div>'
                + '<div class="small mt-1 text-muted">'
                + (refState.isUpdate
                    ? 'Pilih record persediaan baru. Stok lama dikembalikan, stok baru dikurangi (bahan_produksi).'
                    : 'Klik <strong>Refered</strong> pada baris persediaan yang cocok.')
                + '</div>'
            );
            $('#prod-referensi-loading').removeClass('d-none');
            if (refState.dt && $.fn.DataTable && $.fn.DataTable.isDataTable('#tbl-prod-referensi-persediaan')) {
                try { $('#tbl-prod-referensi-persediaan').DataTable().destroy(); } catch (eD) {}
                refState.dt = null;
            }
            $('#tbl-prod-referensi-persediaan tbody').empty();
            $('#modal-prod-referensi-persediaan').modal('show');

            var qtyRef = parseAngkaProdRef(refState.meta.jumlah);
            var satRef = normSatuanProd(refState.meta.satuan);
            refState.persediaanMap = {};

            $.ajax({
                url: urlList,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: { bulan: bulanKey }
            }).done(function(res) {
                $('#prod-referensi-loading').addClass('d-none');
                if (!res || !res.ok) {
                    $('#prod-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlProd((res && res.message) ? res.message : 'Gagal memuat persediaan.') + '</div>');
                    return;
                }
                if (!$.fn.DataTable) {
                    $('#prod-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">DataTables tidak tersedia.</div>');
                    return;
                }
                var rows = (res.rows || []).map(function(r) {
                    var idp = parseInt(r.id, 10) || 0;
                    if (idp > 0) refState.persediaanMap[idp] = r;
                    var total10 = stokPersediaanProdRef(r);
                    var satuanOk = satuanCocokProd(satRef, r.satuan);
                    var stokOk = (total10 >= qtyRef && qtyRef > 0);
                    var colorYellow = '#d4a017';
                    var colorGreen = '#006400';
                    var btnClass = (satuanOk && stokOk) ? 'btn-success' : 'btn-warning';
                    return [
                        '<button type="button" class="btn btn-xs ' + btnClass + ' btn-prod-refered" data-id-persediaan="' + idp + '" style="color:#fff;">Refered</button>',
                        idp,
                        '<span style="font-weight:600;">' + escapeHtmlProd(r.namabarang || '') + '</span>',
                        '<span style="color:' + (satuanOk ? colorGreen : colorYellow) + ';font-weight:700;">' + escapeHtmlProd(r.satuan || '') + '</span>',
                        escapeHtmlProd(r.hpp || ''),
                        escapeHtmlProd(r.sa || ''),
                        escapeHtmlProd(r.beli || ''),
                        escapeHtmlProd(r.bahan_produksi || ''),
                        '<span style="color:' + (stokOk ? colorGreen : colorYellow) + ';font-weight:700;">' + escapeHtmlProd(r.total_10 || '') + '</span>'
                    ];
                });
                refState.dt = $('#tbl-prod-referensi-persediaan').DataTable({
                    data: rows,
                    pageLength: 10,
                    scrollX: true,
                    order: [[2, 'asc']],
                    columnDefs: [{ targets: [0], orderable: false }],
                    language: {
                        search: 'Cari:',
                        emptyTable: 'Tidak ada data persediaan di bulan ini'
                    }
                });
            }).fail(function() {
                $('#prod-referensi-loading').addClass('d-none');
                $('#prod-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
            });
        }

        $(document).on('click', '.btn-prod-referensi-persediaan', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var idBahan = parseInt($(this).attr('data-id-bahan'), 10) || 0;
            if (idBahan < 1) {
                notifyProdReferensi('ID bahan produksi tidak valid.', 'error');
                return;
            }
            openProdReferensiModal(idBahan, {
                nama: $(this).attr('data-nama-bahan') || '',
                satuan: $(this).attr('data-satuan') || '',
                jumlah: $(this).attr('data-jumlah') || '',
                unit: $(this).attr('data-nama-unit') || '',
                produk: $(this).attr('data-nama-produk') || ''
            }, false);
        });

        $(document).on('click', '.btn-prod-update-referensi-persediaan', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var idBahan = parseInt($(this).attr('data-id-bahan'), 10) || 0;
            if (idBahan < 1) {
                notifyProdReferensi('ID bahan produksi tidak valid.', 'error');
                return;
            }
            openProdReferensiModal(idBahan, {
                nama: $(this).attr('data-nama-bahan') || '',
                satuan: $(this).attr('data-satuan') || '',
                jumlah: $(this).attr('data-jumlah') || '',
                unit: $(this).attr('data-nama-unit') || '',
                produk: $(this).attr('data-nama-produk') || '',
                refUuid: $(this).attr('data-ref-uuid') || ''
            }, true);
        });

        $(document).on('click', '.btn-prod-refered', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var idPers = parseInt($(this).attr('data-id-persediaan'), 10) || 0;
            var idBahan = refState.idBahan || 0;
            var bulanKey = refState.bulanKey || resolveBulanProduksiReferensi();
            if (idPers < 1 || idBahan < 1 || !bulanKey) {
                notifyProdReferensi('Data referensi tidak lengkap. Buka ulang tombol Referensi.', 'error');
                return;
            }
            var rowPers = refState.persediaanMap[idPers] || {};
            var qtyRef = parseAngkaProdRef(refState.meta && refState.meta.jumlah);
            var satRef = (refState.meta && refState.meta.satuan) ? refState.meta.satuan : '';
            var satPers = rowPers.satuan || '';
            var total10 = stokPersediaanProdRef(rowPers);
            var satuanOk = satuanCocokProd(satRef, satPers);
            var namaPers = rowPers.namabarang || '';
            var defaultJumlah = qtyRef > 0 ? qtyRef : 1;
            var maxJumlah = qtyRef > 0 ? qtyRef : (total10 > 0 ? total10 : defaultJumlah);
            var warnings = [];
            if (!satuanOk) warnings.push('Satuan bahan ("' + satRef + '") berbeda dari persediaan ("' + satPers + '").');
            if (total10 <= 0) warnings.push('total_10 persediaan kosong / 0.');
            else if (qtyRef > 0 && total10 < qtyRef) warnings.push('total_10 (' + total10 + ') lebih kecil dari jumlah bahan (' + qtyRef + ').');

            $('#prod-isi-jumlah-alert').html(warnings.length ? '<div class="alert alert-warning py-2 mb-0">' + escapeHtmlProd(warnings.join(' ')) + '</div>' : '');
            $('#prod-isi-jumlah-d-id').text(String(idPers));
            $('#prod-isi-jumlah-d-nama').html('<strong>' + escapeHtmlProd(namaPers) + '</strong>');
            $('#prod-isi-jumlah-d-kode').text((rowPers.kode_barang || '-') + ' / ' + (rowPers.spop || '-'));
            $('#prod-isi-jumlah-d-satuan').text(satPers || '-');
            $('#prod-isi-jumlah-d-hpp').text(rowPers.hpp || '-');
            $('#prod-isi-jumlah-d-mutasi').text('SA ' + (rowPers.sa || '0') + ' | Beli ' + (rowPers.beli || '0') + ' | Bahan Produksi ' + (rowPers.bahan_produksi || '0'));
            $('#prod-isi-jumlah-d-total10').html('<strong>' + escapeHtmlProd(String(rowPers.total_10 != null ? rowPers.total_10 : total10)) + '</strong>');
            $('#prod-isi-jumlah-label').text('Jumlah (qty bahan = ' + qtyRef + ', total_10 = ' + total10 + ')');
            $('#prod-isi-jumlah-input').attr('max', maxJumlah).attr('min', 1).val(defaultJumlah);
            $('#prod-isi-jumlah-meta-qty').html(
                '<div><strong>Bahan produksi</strong> ID ' + idBahan
                + ' — <strong>' + escapeHtmlProd((refState.meta && refState.meta.nama) ? refState.meta.nama : '') + '</strong>'
                + ' / ' + escapeHtmlProd(satRef)
                + ' — qty <strong>' + qtyRef + '</strong></div>'
                + '<div class="mt-1 text-muted">Default jumlah = qty bahan produksi. Ubah jika perlu, lalu SIMPAN.</div>'
            );
            $('#prod-isi-jumlah-id-persediaan').val(String(idPers));
            $('#prod-isi-jumlah-id-bahan').val(String(idBahan));
            $('#prod-isi-jumlah-bulan').val(bulanKey);
            $('#prod-isi-jumlah-force').val('0');
            $('#prod-isi-jumlah-is-update').val(refState.isUpdate ? '1' : '0');
            prodIsiJumlahSubmitting = false;
            $('#btn-prod-isi-jumlah-simpan').prop('disabled', false).text(refState.isUpdate ? 'SIMPAN UPDATE' : 'SIMPAN');
            showProdIsiJumlahModal();
        });

        function submitProdIsiJumlahRefered(forceFlag) {
            if (prodIsiJumlahSubmitting) {
                return;
            }
            var idPers = parseInt($('#prod-isi-jumlah-id-persediaan').val(), 10) || 0;
            var idBahan = parseInt($('#prod-isi-jumlah-id-bahan').val(), 10) || 0;
            var bulanKey = ($('#prod-isi-jumlah-bulan').val() || refState.bulanKey || resolveBulanProduksiReferensi()).trim();
            var jumlah = parseFloat($('#prod-isi-jumlah-input').val()) || 0;
            var maxJumlah = parseFloat($('#prod-isi-jumlah-input').attr('max')) || 0;
            if (idPers < 1 || idBahan < 1 || !bulanKey) {
                $('#prod-isi-jumlah-alert').html(
                    '<div class="alert alert-danger py-2 mb-0">Data tidak lengkap (persediaan=' + idPers
                    + ', bahan=' + idBahan + ', bulan=' + escapeHtmlProd(bulanKey) + '). Tutup modal lalu buka ulang.</div>'
                );
                return;
            }
            if (jumlah < 1 || (maxJumlah > 0 && jumlah > maxJumlah)) {
                $('#prod-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">Jumlah harus antara 1 dan ' + maxJumlah + '.</div>');
                return;
            }
            var isUpdate = ($('#prod-isi-jumlah-is-update').val() === '1');
            prodIsiJumlahSubmitting = true;
            var $btnSimpan = $('#btn-prod-isi-jumlah-simpan');
            var btnLabelAwal = isUpdate ? 'SIMPAN UPDATE' : 'SIMPAN';
            $btnSimpan.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            $('#prod-isi-jumlah-alert').html('<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin"></i> Menyimpan referensi persediaan...</div>');

            $.ajax({
                url: urlApply,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: {
                    bulan: bulanKey,
                    id_bahan: idBahan,
                    id_persediaan: idPers,
                    jumlah: jumlah,
                    force: forceFlag ? '1' : '0',
                    is_update: isUpdate ? '1' : '0'
                }
            }).done(function(res) {
                if (res && res.need_confirm_uuid) {
                    prodIsiJumlahSubmitting = false;
                    $btnSimpan.prop('disabled', false).text(btnLabelAwal);
                    var msg = (res && res.message) ? res.message : 'Ada uuid sinkron di pembelian & persediaan.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'UUID sinkron ditemukan',
                            html: escapeHtmlProd(msg),
                            showCancelButton: true,
                            confirmButtonText: 'Lanjut paksa SIMPAN',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            raiseSwalZIndex();
                            if (result.isConfirmed) {
                                $('#prod-isi-jumlah-force').val('1');
                                submitProdIsiJumlahRefered(true);
                            }
                        });
                        raiseSwalZIndex();
                    } else if (window.confirm(msg + '\n\nOK = lanjut paksa')) {
                        $('#prod-isi-jumlah-force').val('1');
                        submitProdIsiJumlahRefered(true);
                    }
                    $('#prod-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">' + escapeHtmlProd(msg) + '</div>');
                    return;
                }
                if (!res || !res.ok) {
                    $('#prod-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlProd((res && res.message) ? res.message : 'Gagal simpan.') + '</div>');
                    prodIsiJumlahSubmitting = false;
                    $btnSimpan.prop('disabled', false).text(btnLabelAwal);
                    return;
                }
                $('#modal-prod-isi-jumlah-refered').modal('hide');
                $('#modal-prod-referensi-persediaan').modal('hide');
                prodIsiJumlahSubmitting = false;
                $btnSimpan.prop('disabled', false).text(btnLabelAwal);
                var doneMsg = res.message || 'Referensi berhasil.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: isUpdate ? 'Update referensi berhasil' : 'Refered berhasil',
                        text: doneMsg
                    }).then(function() {
                        reloadVerifikasiSetelahRefered();
                    });
                    raiseSwalZIndex();
                } else {
                    alert(doneMsg);
                    reloadVerifikasiSetelahRefered();
                }
            }).fail(function(xhr) {
                var errMsg = 'Gagal menghubungi server.';
                if (xhr && xhr.responseText) {
                    try {
                        var parsed = JSON.parse(xhr.responseText);
                        if (parsed && parsed.message) errMsg = parsed.message;
                    } catch (eParse) {}
                }
                $('#prod-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlProd(errMsg) + '</div>');
                prodIsiJumlahSubmitting = false;
                $btnSimpan.prop('disabled', false).text(btnLabelAwal);
            });
        }

        $(document).on('submit', '#form-prod-isi-jumlah-refered', function(e) {
            e.preventDefault();
            submitProdIsiJumlahRefered($('#prod-isi-jumlah-force').val() === '1');
        });

        $(document).on('click', '#btn-prod-isi-jumlah-simpan', function(e) {
            e.preventDefault();
            e.stopPropagation();
            submitProdIsiJumlahRefered($('#prod-isi-jumlah-force').val() === '1');
        });

        $(document).on('show.bs.modal', '.modal', function() {
            var visibleCount = $('.modal:visible').length;
            if (visibleCount > 0) {
                var zIndex = 1050 + (10 * visibleCount);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.prod-modal-stack').last()
                        .css('z-index', zIndex - 1)
                        .addClass('prod-modal-stack prod-refered-jumlah-backdrop');
                }, 0);
            }
        });

        $(document).on('hidden.bs.modal', '.modal', function() {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });

        $('#modal-prod-referensi-persediaan').on('shown.bs.modal', function() {
            if (refState.dt) {
                try { refState.dt.columns.adjust(); } catch (eAdj) {}
            }
        });

        $('#modal-prod-isi-jumlah-refered').on('shown.bs.modal', function() {
            $('.modal-backdrop').last().addClass('prod-refered-jumlah-backdrop').css('z-index', 1060);
            $(this).css('z-index', 1065);
            $('#prod-isi-jumlah-input').trigger('focus').select();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProduksiVerifikasiReferensi);
    } else {
        initProduksiVerifikasiReferensi();
    }
})();
</script>
