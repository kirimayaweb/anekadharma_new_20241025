<?php
$url_pecah_referensi_list = isset($url_pecah_referensi_list)
    ? $url_pecah_referensi_list
    : site_url('Tbl_pembelian/ajax_pecah_referensi_persediaan_list');
$url_pecah_referensi_apply = isset($url_pecah_referensi_apply)
    ? $url_pecah_referensi_apply
    : site_url('Tbl_pembelian/ajax_pecah_referensi_persediaan_apply');
$bulan_pecah_referensi = isset($bulan_persediaan_selected) ? (string) $bulan_persediaan_selected : date('Y-m');
?>
<script>
(function() {
    function escapeHtmlPecah(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function parseAngkaPecahRef(val) {
        if (val == null || val === '') return 0;
        var s = String(val).replace(/[^\d.-]/g, '');
        var n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function initPecahVerifikasiReferensi() {
        if (!window.jQuery) return;
        var $ = jQuery;
        var urlList = <?php echo json_encode($url_pecah_referensi_list); ?>;
        var urlApply = <?php echo json_encode($url_pecah_referensi_apply); ?>;
        var bulanDefault = <?php echo json_encode($bulan_pecah_referensi); ?>;

        $('#modal-pecah-referensi-persediaan, #modal-pecah-isi-jumlah-refered').appendTo('body');

        function raiseSwalZ() {
            setTimeout(function() { $('.swal2-container').css('z-index', 20000); }, 30);
        }
        function resolveBulan() {
            var v = $('#bulan_persediaan').val() || '';
            if (/^\d{4}-\d{2}$/.test(v)) return v;
            if (/^\d{4}-\d{2}$/.test(bulanDefault)) return bulanDefault;
            return '';
        }
        function reloadVerifikasi() {
            var b = resolveBulan();
            if (typeof loadPecahVerifikasiByBulan === 'function' && b) {
                loadPecahVerifikasiByBulan(b);
            } else {
                window.location.reload();
            }
        }
        function showPecahIsiJumlahModal() {
            var $m = $('#modal-pecah-isi-jumlah-refered');
            if ($m.length && $m.parent()[0] !== document.body) {
                $m.appendTo('body');
            }
            $m.css('z-index', 1065).modal('show');
        }

        var refState = { idPecah: 0, bulanKey: '', meta: null, dt: null, isUpdate: false, persediaanMap: {} };
        var pecahIsiJumlahSubmitting = false;

        function stokPersediaanPecahRef(r) {
            if (r.total_10_stok != null && !isNaN(parseFloat(r.total_10_stok))) {
                return parseFloat(r.total_10_stok) || 0;
            }
            return parseAngkaPecahRef(r.total_10);
        }

        function openModal(idPecah, meta, isUpdate) {
            var bulanKey = resolveBulan();
            if (!bulanKey) {
                alert('Bulan tidak dikenali.');
                return;
            }
            refState.idPecah = idPecah;
            refState.bulanKey = bulanKey;
            refState.meta = meta || {};
            refState.isUpdate = !!isUpdate;
            refState.persediaanMap = {};
            $('#pecah-referensi-alert').empty();
            var refInfo = refState.meta.refUuid ? (' | Ref. lama: <code>' + escapeHtmlPecah(refState.meta.refUuid) + '</code>') : '';
            $('#pecah-referensi-meta').html(
                '<strong>' + (isUpdate ? 'Update referensi manual' : 'Referensi baru') + '</strong>'
                + ' — Pecah satuan ID <strong>' + idPecah + '</strong>'
                + ' — Data pecah: <strong>' + escapeHtmlPecah(refState.meta.nama || '') + '</strong>'
                + ' / ' + escapeHtmlPecah(refState.meta.satuan || '')
                + ' qty <strong>' + escapeHtmlPecah(String(refState.meta.jumlah || '')) + '</strong>'
                + ' → Baru: <strong>' + escapeHtmlPecah(refState.meta.namaBaru || '') + '</strong>'
                + ' qty baru <strong>' + escapeHtmlPecah(String(refState.meta.jumlahBaru || '')) + '</strong>'
                + refInfo + ' | Bulan <strong>' + escapeHtmlPecah(bulanKey) + '</strong>'
            );
            $('#pecah-referensi-loading').removeClass('d-none');
            if (refState.dt && $.fn.DataTable && $.fn.DataTable.isDataTable('#tbl-pecah-referensi-persediaan')) {
                try { $('#tbl-pecah-referensi-persediaan').DataTable().destroy(); } catch (e) {}
                refState.dt = null;
            }
            $('#tbl-pecah-referensi-persediaan tbody').empty();
            $('#modal-pecah-referensi-persediaan').modal('show');

            $.ajax({
                url: urlList,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: { bulan: bulanKey, id_pecah_satuan: idPecah }
            }).done(function(res) {
                $('#pecah-referensi-loading').addClass('d-none');
                if (!res || !res.ok) {
                    $('#pecah-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPecah((res && res.message) ? res.message : 'Gagal memuat.') + '</div>');
                    return;
                }
                if (!$.fn.DataTable) return;
                var qtyPecah = parseAngkaPecahRef(refState.meta.jumlah);
                var colorYellow = '#d4a017';
                var colorGreen = '#006400';
                var rows = (res.rows || []).map(function(r) {
                    var idp = parseInt(r.id, 10) || 0;
                    if (idp > 0) refState.persediaanMap[idp] = r;
                    var stok = stokPersediaanPecahRef(r);
                    var stokOk = (qtyPecah <= 0 || stok >= qtyPecah);
                    var btnClass = stokOk ? 'btn-success' : 'btn-warning';
                    return [
                        '<button type="button" class="btn btn-xs ' + btnClass + ' btn-pecah-refered" data-id-persediaan="' + idp + '" style="color:#fff;">Refered</button>',
                        idp,
                        escapeHtmlPecah(r.namabarang || ''),
                        escapeHtmlPecah(r.satuan || ''),
                        escapeHtmlPecah(r.hpp || ''),
                        escapeHtmlPecah(r.sa || ''),
                        escapeHtmlPecah(r.beli || ''),
                        escapeHtmlPecah(r.pecah_satuan || ''),
                        '<span style="color:' + (stokOk ? colorGreen : colorYellow) + ';font-weight:700;">' + escapeHtmlPecah(String(r.total_10 != null ? r.total_10 : stok)) + '</span>'
                    ];
                });
                refState.dt = $('#tbl-pecah-referensi-persediaan').DataTable({
                    data: rows,
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
                    scrollX: true,
                    order: [[2, 'asc']],
                    columnDefs: [{ targets: [0], orderable: false }],
                    language: {
                        search: 'Cari:',
                        emptyTable: 'Tidak ada data persediaan di bulan ini',
                        lengthMenu: 'Tampilkan _MENU_ baris'
                    }
                });
            }).fail(function() {
                $('#pecah-referensi-loading').addClass('d-none');
                $('#pecah-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
            });
        }

        $(document).on('click', '.btn-pecah-referensi-persediaan', function(e) {
            e.preventDefault();
            openModal(parseInt($(this).attr('data-id-pecah'), 10) || 0, {
                nama: $(this).attr('data-nama-sumber') || '',
                satuan: $(this).attr('data-satuan') || '',
                jumlah: $(this).attr('data-jumlah') || '',
                namaBaru: $(this).attr('data-nama-baru') || '',
                jumlahBaru: $(this).attr('data-jumlah-baru') || ''
            }, false);
        });

        $(document).on('click', '.btn-pecah-update-referensi-persediaan', function(e) {
            e.preventDefault();
            openModal(parseInt($(this).attr('data-id-pecah'), 10) || 0, {
                nama: $(this).attr('data-nama-sumber') || '',
                satuan: $(this).attr('data-satuan') || '',
                jumlah: $(this).attr('data-jumlah') || '',
                namaBaru: $(this).attr('data-nama-baru') || '',
                jumlahBaru: $(this).attr('data-jumlah-baru') || '',
                jumlahRefered: $(this).attr('data-jumlah-refered') || '',
                refUuid: $(this).attr('data-ref-uuid') || ''
            }, true);
        });

        $(document).on('click', '.btn-pecah-refered', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var idPers = parseInt($(this).attr('data-id-persediaan'), 10) || 0;
            var idPecah = refState.idPecah || 0;
            var bulanKey = refState.bulanKey || resolveBulan();
            if (idPers < 1 || idPecah < 1 || !bulanKey) {
                $('#pecah-referensi-alert').html('<div class="alert alert-danger py-2 mb-0">Data referensi tidak lengkap. Buka ulang tombol Referensi.</div>');
                return;
            }
            var rowPers = refState.persediaanMap[idPers] || {};
            var qtyPecah = parseAngkaPecahRef(refState.meta && refState.meta.jumlah);
            var qtyBaru = parseAngkaPecahRef(refState.meta && refState.meta.jumlahBaru);
            var qtyReferedLama = parseAngkaPecahRef(refState.meta && refState.meta.jumlahRefered);
            var total10 = stokPersediaanPecahRef(rowPers);
            var defaultJumlah = qtyReferedLama > 0 ? qtyReferedLama : (qtyPecah > 0 ? qtyPecah : 1);
            var maxJumlah = qtyPecah > 0 ? qtyPecah : (total10 > 0 ? total10 : defaultJumlah);
            if (total10 > 0 && total10 < maxJumlah) {
                maxJumlah = total10;
            }
            var warnings = [];
            if (total10 <= 0) warnings.push('total_10 persediaan kosong / 0.');
            else if (defaultJumlah > 0 && total10 < defaultJumlah) warnings.push('total_10 (' + total10 + ') lebih kecil dari jumlah default (' + defaultJumlah + ').');

            $('#pecah-isi-jumlah-alert').html(warnings.length ? '<div class="alert alert-warning py-2 mb-0">' + escapeHtmlPecah(warnings.join(' ')) + '</div>' : '');
            $('#pecah-isi-jumlah-d-id').text(String(idPers));
            $('#pecah-isi-jumlah-d-nama').html('<strong>' + escapeHtmlPecah(rowPers.namabarang || '') + '</strong>');
            $('#pecah-isi-jumlah-d-kode').text((rowPers.kode_barang || '-') + ' / ' + (rowPers.spop || '-'));
            $('#pecah-isi-jumlah-d-satuan').text(rowPers.satuan || '-');
            $('#pecah-isi-jumlah-d-hpp').text(rowPers.hpp || '-');
            $('#pecah-isi-jumlah-d-mutasi').text('SA ' + (rowPers.sa || '0') + ' | Beli ' + (rowPers.beli || '0') + ' | Pecah Satuan ' + (rowPers.pecah_satuan || '0'));
            $('#pecah-isi-jumlah-d-total10').html('<strong>' + escapeHtmlPecah(String(rowPers.total_10 != null ? rowPers.total_10 : total10)) + '</strong>');
            $('#pecah-isi-jumlah-label').text('Jumlah diambil dari sumber (qty pecah = ' + qtyPecah + ', stok = ' + total10 + ')');
            $('#pecah-isi-jumlah-input').attr('max', maxJumlah).attr('min', 1).val(defaultJumlah);
            $('#pecah-isi-jumlah-meta-qty').html(
                '<div><strong>Pecah satuan</strong> ID ' + idPecah
                + ' — dipecah <strong>' + qtyPecah + '</strong> → barang baru qty <strong>' + qtyBaru + '</strong></div>'
                + '<div class="mt-1 text-muted">Isi berapa jumlah yang diambil dari persediaan sumber ini, lalu klik SIMPAN.</div>'
            );
            $('#pecah-isi-jumlah-id-persediaan').val(String(idPers));
            $('#pecah-isi-jumlah-id-pecah').val(String(idPecah));
            $('#pecah-isi-jumlah-bulan').val(bulanKey);
            $('#pecah-isi-jumlah-is-update').val(refState.isUpdate ? '1' : '0');
            pecahIsiJumlahSubmitting = false;
            $('#btn-pecah-isi-jumlah-simpan').prop('disabled', false).text(refState.isUpdate ? 'SIMPAN UPDATE' : 'SIMPAN');
            showPecahIsiJumlahModal();
        });

        function submitPecahIsiJumlahRefered() {
            if (pecahIsiJumlahSubmitting) return;
            var idPers = parseInt($('#pecah-isi-jumlah-id-persediaan').val(), 10) || 0;
            var idPecah = parseInt($('#pecah-isi-jumlah-id-pecah').val(), 10) || 0;
            var bulanKey = ($('#pecah-isi-jumlah-bulan').val() || refState.bulanKey || resolveBulan()).trim();
            var jumlah = parseFloat($('#pecah-isi-jumlah-input').val()) || 0;
            var maxJumlah = parseFloat($('#pecah-isi-jumlah-input').attr('max')) || 0;
            var isUpdate = ($('#pecah-isi-jumlah-is-update').val() === '1');
            if (idPers < 1 || idPecah < 1 || !bulanKey) {
                $('#pecah-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">Data tidak lengkap. Tutup modal lalu buka ulang.</div>');
                return;
            }
            if (jumlah < 1 || (maxJumlah > 0 && jumlah > maxJumlah)) {
                $('#pecah-isi-jumlah-alert').html('<div class="alert alert-warning py-2 mb-0">Jumlah harus antara 1 dan ' + maxJumlah + '.</div>');
                return;
            }
            pecahIsiJumlahSubmitting = true;
            var $btn = $('#btn-pecah-isi-jumlah-simpan');
            var btnLabel = isUpdate ? 'SIMPAN UPDATE' : 'SIMPAN';
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            $('#pecah-isi-jumlah-alert').html('<div class="alert alert-info py-2 mb-0"><i class="fas fa-spinner fa-spin"></i> Menyimpan referensi...</div>');

            $.ajax({
                url: urlApply,
                type: 'POST',
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                data: {
                    bulan: bulanKey,
                    id_pecah_satuan: idPecah,
                    id_persediaan: idPers,
                    jumlah: jumlah,
                    is_update: isUpdate ? '1' : '0'
                }
            }).done(function(res) {
                if (!res || !res.ok) {
                    $('#pecah-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">' + escapeHtmlPecah((res && res.message) ? res.message : 'Gagal simpan.') + '</div>');
                    pecahIsiJumlahSubmitting = false;
                    $btn.prop('disabled', false).text(btnLabel);
                    return;
                }
                $('#modal-pecah-isi-jumlah-refered').modal('hide');
                $('#modal-pecah-referensi-persediaan').modal('hide');
                pecahIsiJumlahSubmitting = false;
                $btn.prop('disabled', false).text(btnLabel);
                var msg = res.message || 'Berhasil.';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: isUpdate ? 'Update berhasil' : 'Refered berhasil', text: msg })
                        .then(function() { reloadVerifikasi(); });
                    raiseSwalZ();
                } else {
                    alert(msg);
                    reloadVerifikasi();
                }
            }).fail(function() {
                $('#pecah-isi-jumlah-alert').html('<div class="alert alert-danger py-2 mb-0">Gagal menghubungi server.</div>');
                pecahIsiJumlahSubmitting = false;
                $btn.prop('disabled', false).text(btnLabel);
            });
        }

        $(document).on('submit', '#form-pecah-isi-jumlah-refered', function(e) {
            e.preventDefault();
            submitPecahIsiJumlahRefered();
        });
        $(document).on('click', '#btn-pecah-isi-jumlah-simpan', function(e) {
            e.preventDefault();
            e.stopPropagation();
            submitPecahIsiJumlahRefered();
        });

        $(document).on('show.bs.modal', '.modal', function() {
            var visibleCount = $('.modal:visible').length;
            if (visibleCount > 0) {
                var zIndex = 1050 + (10 * visibleCount);
                $(this).css('z-index', zIndex);
                setTimeout(function() {
                    $('.modal-backdrop').not('.pecah-modal-stack').last()
                        .css('z-index', zIndex - 1)
                        .addClass('pecah-modal-stack pecah-refered-jumlah-backdrop');
                }, 0);
            }
        });
        $(document).on('hidden.bs.modal', '.modal', function() {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });

        $('#modal-pecah-referensi-persediaan').on('shown.bs.modal', function() {
            if (refState.dt) try { refState.dt.columns.adjust(); } catch (e) {}
        });
        $('#modal-pecah-isi-jumlah-refered').on('shown.bs.modal', function() {
            $('.modal-backdrop').last().addClass('pecah-refered-jumlah-backdrop').css('z-index', 1060);
            $(this).css('z-index', 1065);
            $('#pecah-isi-jumlah-input').trigger('focus').select();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPecahVerifikasiReferensi);
    } else {
        initPecahVerifikasiReferensi();
    }
})();
</script>
