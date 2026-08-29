<script>
(function() {
    if (typeof window.jQuery === 'undefined') {
        console.error('Edit Penjualan: jQuery belum tersedia di footer.');
        return;
    }

    var $ = window.jQuery;
    var ajaxUrlDetail = <?php echo json_encode(site_url('tbl_pembelian/ajax_detail_penjualan_tagihan')); ?>;
    var ajaxUrlUpdate = <?php echo json_encode(site_url('tbl_pembelian/ajax_update_penjualan_tagihan')); ?>;
    var ajaxUrlCreate = <?php echo json_encode(site_url('tbl_pembelian/ajax_create_penjualan_tagihan')); ?>;
    var ajaxUrlDelete = <?php echo json_encode(site_url('tbl_pembelian/ajax_delete_penjualan_tagihan')); ?>;
    var ajaxUrlListPersediaan = <?php echo json_encode(site_url('tbl_pembelian/ajax_list_persediaan_tagihan')); ?>;
    var ajaxUrlTambahDariPersediaan = <?php echo json_encode(site_url('tbl_pembelian/ajax_tambah_barang_dari_persediaan_tagihan')); ?>;
    var tableEditPenjualan = null;
    var tablePilihPersediaan = null;
    var currentCtx = {
        uuid_penjualan: '',
        uuid_penjualan_proses: '',
        total_nominal_record: 0,
        nmrpesan: '',
        nmrkirim: '',
        rows: [],
        meta: {},
        dirty: false
    };
    var selectedPersediaan = null;

    function fmtNumber(n) {
        n = Number(n || 0);
        try {
            if (Math.abs(n - Math.round(n)) < 0.0000001) {
                return n.toLocaleString('id-ID', { maximumFractionDigits: 0 });
            }
            return n.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } catch (e) {
            return String(n);
        }
    }

    function notify(type, title, text) {
        if (window.Swal) {
            Swal.fire({
                icon: type || 'info',
                title: title || '',
                text: text || '',
                timer: 1600,
                showConfirmButton: false
            });
            return;
        }
        if (text) {
            window.alert(text);
        }
    }

    function findRowById(id) {
        var idInt = parseInt(id, 10);
        if (!idInt) return null;
        for (var i = 0; i < currentCtx.rows.length; i++) {
            if (parseInt(currentCtx.rows[i].id, 10) === idInt) {
                return currentCtx.rows[i];
            }
        }
        return null;
    }

    /** Parse angka ID: 8.000,45 / 8000,45 / 8000.45 */
    function parseAngkaId(val) {
        val = String(val == null ? '' : val).trim().replace(/\s/g, '');
        if (!val) return 0;
        if (val.indexOf(',') >= 0) {
            val = val.replace(/\./g, '').replace(',', '.');
        } else {
            var parts = val.split('.');
            if (parts.length > 2) {
                val = val.replace(/\./g, '');
            } else if (parts.length === 2 && parts[1].length > 2) {
                val = val.replace(/\./g, '');
            }
        }
        val = val.replace(/[^0-9.\-]/g, '');
        var num = parseFloat(val);
        return isNaN(num) ? 0 : num;
    }

    function destroyEditPenjualanTable() {
        if (tableEditPenjualan && $.fn.DataTable && $.fn.DataTable.isDataTable('#tableEditPenjualanTagihan')) {
            tableEditPenjualan.destroy();
            tableEditPenjualan = null;
        }
        $('#tableEditPenjualanTagihan tbody').empty();
    }

    function updateTotalPreview() {
        var jml = parseAngkaId($('#pj_jumlah').val());
        var harga = parseAngkaId($('#pj_harga_satuan').val());
        $('#pj_total_preview').val(fmtNumber(jml * harga));
    }

    function updateTotalPreviewModalRow() {
        var jml = parseAngkaId($('#pjm_jumlah').val());
        var harga = parseAngkaId($('#pjm_harga_satuan').val());
        $('#pjm_total_preview').val(fmtNumber(jml * harga));
    }

    function resetFormPenjualan(mode) {
        mode = mode || 'update';
        $('#pj_mode').val(mode);
        $('#pj_id').val('');
        $('#judulFormPenjualanTagihan').text(mode === 'create' ? 'Tambah Baris Penjualan' : 'Ubah Data Penjualan');
        $('#btnSimpanPenjualanTagihan').html('<i class="fa fa-save"></i> ' + (mode === 'create' ? 'Tambah' : 'Simpan'));
        if (mode === 'create') {
            $('#pj_jumlah').val(1);
            // keep other fields from last selected / meta
        }
        updateTotalPreview();
    }

    function formatAngkaIdInput(n) {
        n = Number(n || 0);
        if (Math.abs(n - Math.round(n)) < 0.0000001) {
            return String(Math.round(n));
        }
        return String(Math.round(n * 100) / 100).replace('.', ',');
    }

    function fillFormFromRow(r, mode) {
        mode = mode || 'update';
        $('#pj_mode').val(mode);
        $('#pj_id').val(r.id || '');
        $('#pj_uuid_penjualan').val(r.uuid_penjualan || currentCtx.uuid_penjualan || '');
        $('#pj_tgl_jual').val(r.tgl_jual_raw || '');
        $('#pj_nmrpesan').val(r.nmrpesan || '');
        $('#pj_nmrkirim').val(r.nmrkirim || '');
        $('#pj_kode_barang').val(r.kode_barang || '');
        $('#pj_nama_barang').val(r.nama_barang || '');
        $('#pj_jumlah').val(r.jumlah != null ? formatAngkaIdInput(r.jumlah) : '');
        $('#pj_satuan').val(r.satuan || '');
        $('#pj_harga_satuan').val(r.harga_satuan != null ? formatAngkaIdInput(r.harga_satuan) : '');
        $('#judulFormPenjualanTagihan').text(mode === 'create' ? 'Tambah Baris Penjualan' : 'Ubah Data Penjualan #' + (r.id || ''));
        $('#btnSimpanPenjualanTagihan').html('<i class="fa fa-save"></i> ' + (mode === 'create' ? 'Tambah' : 'Simpan'));
        updateTotalPreview();
    }

    function fillModalRowFromRow(r) {
        $('#pjm_id').val(r.id || '');
        $('#pjm_uuid_penjualan').val(r.uuid_penjualan || currentCtx.uuid_penjualan || '');
        $('#pjm_tgl_jual').val(r.tgl_jual_raw || '');
        $('#pjm_nmrpesan').val(r.nmrpesan || '');
        $('#pjm_nmrkirim').val(r.nmrkirim || '');
        $('#pjm_kode_barang').val(r.kode_barang || '');
        $('#pjm_nama_barang').val(r.nama_barang || '');
        $('#pjm_jumlah').val(r.jumlah != null ? formatAngkaIdInput(r.jumlah) : '');
        $('#pjm_satuan').val(r.satuan || '');
        $('#pjm_harga_satuan').val(r.harga_satuan != null ? formatAngkaIdInput(r.harga_satuan) : '');
        updateTotalPreviewModalRow();
    }

    function renderValidasiBox(resp) {
        var $box = $('#modalEditPenjualanValidasi');
        if (resp.valid && resp.total_match_record) {
            $box.html('<div class="alert alert-success mb-0"><i class="fa fa-check-circle"></i> Data penjualan <strong>VALID</strong>: total detail sama dengan nominal record yang diklik (' + resp.total_nominal_record_fmt + ').</div>');
        } else if (resp.data_konsisten) {
            $box.html('<div class="alert alert-info mb-0"><i class="fa fa-info-circle"></i> Data penjualan konsisten. Nominal baris diklik: <strong>' + resp.total_nominal_record_fmt + '</strong>, total seluruh item: <strong>' + resp.total_detail_fmt + '</strong> (' + resp.row_count + ' baris).</div>');
        } else {
            $box.html('<div class="alert alert-warning mb-0"><i class="fa fa-exclamation-triangle"></i> Periksa data: total detail (' + (resp.total_detail_fmt || '-') + ') vs nominal record (' + (resp.total_nominal_record_fmt || '-') + ').</div>');
        }
    }

    function renderRows(resp) {
        currentCtx.rows = resp.rows || [];
        destroyEditPenjualanTable();

        var rowsHtml = '';
        currentCtx.rows.forEach(function(r, idx) {
            var trClass = [];
            if (r.is_clicked) trClass.push('row-clicked-penjualan');
            if (!r.line_ok) trClass.push('row-line-invalid');
            var aksi = '';
            if (r.can_edit) {
                aksi =
                    '<button type="button" class="btn btn-primary btn-xs btn-edit-row-pj" data-id="' + r.id + '" onclick="if(window.handleEditPenjualanRowTagihan){window.handleEditPenjualanRowTagihan(' + r.id + ', this);} return false;"><i class="fa fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-xs btn-del-row-pj" data-id="' + r.id + '" onclick="if(window.handleDeletePenjualanRowTagihan){window.handleDeletePenjualanRowTagihan(' + r.id + ', this);} return false;"><i class="fa fa-trash"></i></button>';
            } else {
                aksi = '<span class="badge badge-secondary">Locked</span>';
            }
            rowsHtml += '<tr class="' + trClass.join(' ') + '">' +
                '<td>' + (idx + 1) + '</td>' +
                '<td>' + aksi + '</td>' +
                '<td>' + (r.tgl_jual || '') + '</td>' +
                '<td>' + (r.nmrpesan || '') + '</td>' +
                '<td>' + (r.nmrkirim || '') + '</td>' +
                '<td>' + (r.kode_barang || '') + '</td>' +
                '<td>' + (r.nama_barang || '') + '</td>' +
                '<td style="text-align:right">' + (r.jumlah_fmt || '') + '</td>' +
                '<td>' + (r.satuan || '') + '</td>' +
                '<td style="text-align:right">' + (r.harga_satuan_fmt || '') + '</td>' +
                '<td style="text-align:right">' + (r.total_nominal_fmt || '') + '</td>' +
                '<td>' + (r.line_ok ? '<span class="badge badge-success">OK</span>' : '<span class="badge badge-danger">Selisih</span>') +
                (r.is_clicked ? ' <span class="badge badge-warning">Diklik</span>' : '') + '</td>' +
                '</tr>';
        });

        $('#tableEditPenjualanTagihan tbody').html(
            rowsHtml || '<tr><td colspan="12" class="text-center text-muted">Tidak ada data penjualan.</td></tr>'
        );

        if ($.fn.DataTable) {
            tableEditPenjualan = $('#tableEditPenjualanTagihan').DataTable({
                scrollX: true,
                scrollY: '48vh',
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                ordering: true,
                info: true
            });
        }

        // Prefill form dengan baris diklik / baris pertama
        var selected = null;
        for (var i = 0; i < currentCtx.rows.length; i++) {
            if (currentCtx.rows[i].is_clicked) {
                selected = currentCtx.rows[i];
                break;
            }
        }
        if (!selected && currentCtx.rows.length) {
            selected = currentCtx.rows[0];
        }
        if (selected) {
            fillFormFromRow(selected, 'update');
        } else {
            resetFormPenjualan('create');
            $('#pj_uuid_penjualan').val(currentCtx.uuid_penjualan || '');
        }
    }

    function loadDetailPenjualan(options) {
        options = options || {};
        $('#modalEditPenjualanValidasi').html('<div class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data penjualan…</div>');
        $('#modalTotalRecord').text('…');
        $('#modalTotalDetail').text('…');
        $('#modalTotalHitung').text('…');
        $('#modalFooterTotal').text('…');

        return $.ajax({
            url: ajaxUrlDetail,
            type: 'POST',
            dataType: 'json',
            data: {
                uuid_penjualan: currentCtx.uuid_penjualan,
                uuid_penjualan_proses: currentCtx.uuid_penjualan_proses,
                total_nominal_record: currentCtx.total_nominal_record
            }
        }).done(function(resp) {
            if (!resp || !resp.success) {
                $('#modalEditPenjualanValidasi').html('<div class="alert alert-danger mb-0">' + ((resp && resp.message) ? resp.message : 'Gagal memuat data.') + '</div>');
                return;
            }

            // Setelah tambah/edit: samakan "nominal record" dengan total semua baris penjualan
            if ((options.syncRecordToDetail || currentCtx.dirty) && resp.total_detail != null) {
                currentCtx.total_nominal_record = resp.total_detail;
                resp.total_nominal_record = resp.total_detail;
                resp.total_nominal_record_fmt = resp.total_detail_fmt;
                resp.total_match_record = true;
                resp.valid = !!resp.data_konsisten;
            }

            var meta = resp.meta || {};
            currentCtx.meta = meta;
            if (meta.uuid_penjualan) {
                currentCtx.uuid_penjualan = meta.uuid_penjualan;
            }
            $('#modalEditPenjualanMeta').text(
                [meta.konsumen_nama, meta.tgl_jual ? ('Tgl: ' + meta.tgl_jual) : '', meta.nmrpesan ? ('Pesan: ' + meta.nmrpesan) : '', meta.nmrkirim ? ('Kirim: ' + meta.nmrkirim) : '', meta.bulan_label ? ('Bulan: ' + meta.bulan_label) : '']
                    .filter(Boolean).join(' | ')
            );
            $('#modalTotalRecord').text(resp.total_nominal_record_fmt || '—');
            $('#modalTotalDetail').text(resp.total_detail_fmt || '—');
            $('#modalTotalHitung').text(resp.total_hitung_fmt || '—');
            $('#modalFooterTotal').text(resp.total_detail_fmt || '—');
            if (meta.nmrpesan) currentCtx.nmrpesan = meta.nmrpesan;
            if (meta.nmrkirim) currentCtx.nmrkirim = meta.nmrkirim;
            // Sinkronkan kolom Total di tabel tagihan (total dokumen/grup)
            if (resp.total_detail != null) {
                updateParentTagihanGroupTotal(
                    resp.total_detail,
                    resp.total_detail_fmt,
                    currentCtx.nmrpesan,
                    currentCtx.nmrkirim,
                    currentCtx.uuid_penjualan
                );
            }
            if (resp.url_edit) {
                $('#btnBukaHalamanEditPenjualan').attr('href', resp.url_edit);
            }
            renderValidasiBox(resp);
            renderRows(resp);
        }).fail(function(xhr) {
            var msg = 'Gagal menghubungi server.';
            if (xhr && xhr.responseText && xhr.responseText.indexOf('<!DOCTYPE') === 0) {
                msg = 'Sesi login berakhir / response bukan JSON. Silakan refresh halaman lalu coba lagi.';
            }
            $('#modalEditPenjualanValidasi').html('<div class="alert alert-danger mb-0">' + msg + '</div>');
        });
    }

    function updateParentTagihanGroupTotal(totalGroup, totalGroupFmt, nmrpesan, nmrkirim, uuidPenjualan) {
        nmrpesan = String(nmrpesan || '').trim();
        nmrkirim = String(nmrkirim || '').trim();
        uuidPenjualan = String(uuidPenjualan || '').trim();
        var fmt = totalGroupFmt || fmtNumber(totalGroup);

        $('#tglSPOPFreeze tbody tr').each(function() {
            var $tr = $(this);
            var match = false;
            if (nmrpesan || nmrkirim) {
                match = (String($tr.attr('data-nmrpesan') || '').trim() === nmrpesan)
                    && (String($tr.attr('data-nmrkirim') || '').trim() === nmrkirim);
            }
            if (!match && uuidPenjualan) {
                match = String($tr.attr('data-uuid-penjualan') || '').trim() === uuidPenjualan;
            }
            if (!match) return;

            $tr.attr('data-total-group', totalGroup);
            $tr.find('.td-total-group-tagihan').html(
                '<strong class="text-danger">' + fmt + '</strong>'
            );
            $tr.find('.btn-edit-penjualan-tagihan')
                .attr('data-total-nominal', totalGroup)
                .data('total-nominal', totalGroup);
        });
    }

    function openEditPenjualanModal(btn) {
        var $btn = $(btn);
        currentCtx.uuid_penjualan = $btn.attr('data-uuid-penjualan') || $btn.data('uuid-penjualan') || '';
        currentCtx.uuid_penjualan_proses = $btn.attr('data-uuid-penjualan-proses') || $btn.data('uuid-penjualan-proses') || '';
        currentCtx.total_nominal_record = $btn.attr('data-total-nominal') || $btn.data('total-nominal') || 0;
        currentCtx.nmrpesan = $btn.attr('data-nmrpesan') || $btn.data('nmrpesan') || '';
        currentCtx.nmrkirim = $btn.attr('data-nmrkirim') || $btn.data('nmrkirim') || '';
        currentCtx.rows = [];

        var nmrpesan = currentCtx.nmrpesan;
        var nmrkirim = currentCtx.nmrkirim;
        var namaBarang = $btn.attr('data-nama-barang') || $btn.data('nama-barang') || '';
        $('#modalEditPenjualanMeta').text(
            [nmrpesan ? ('No Pesan: ' + nmrpesan) : '', nmrkirim ? ('No Kirim: ' + nmrkirim) : '', namaBarang]
                .filter(Boolean).join(' | ')
        );
        $('#btnBukaHalamanEditPenjualan').attr('href', '#');
        $('#pj_uuid_penjualan').val(currentCtx.uuid_penjualan);

        destroyEditPenjualanTable();
        $('#modalEditPenjualanTagihan').modal('show');
        loadDetailPenjualan({ syncRecordToDetail: true });
    }

    // Global agar onclick di tombol (termasuk hasil clone FixedColumns) selalu jalan
    window.openEditPenjualanTagihan = openEditPenjualanModal;

    function setupEditRowModalStacking() {
        var $child = $('#modalEditRowBarangPenjualanTagihan');
        if (!$child.length) return;

        $child.off('shown.bs.modal.editrowstack hidden.bs.modal.editrowstack');

        $child.on('shown.bs.modal.editrowstack', function() {
            window.setTimeout(function() {
                var $backdrop = $('.modal-backdrop').last();
                if ($backdrop.length) {
                    $backdrop.addClass('backdrop-edit-row-penjualan');
                }
                $('body').addClass('modal-open');
            }, 10);
        });

        $child.on('hidden.bs.modal.editrowstack', function() {
            $('.modal-backdrop.backdrop-edit-row-penjualan').remove();
            if ($('#modalEditPenjualanTagihan').hasClass('show')) {
                $('body').addClass('modal-open');
            }
        });
    }

    window.handleEditPenjualanRowTagihan = function(id, el) {
        var row = findRowById(id);
        if (!row) {
            notify('warning', 'Perhatian', 'Data baris penjualan tidak ditemukan. Silakan klik Muat Ulang.');
            return false;
        }

        fillModalRowFromRow(row);
        $('#modalEditRowBarangPenjualanTagihan').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        fillFormFromRow(row, 'update');

        var $rowEl = $(el).closest('tr');
        $('#tableEditPenjualanTagihan tbody tr').removeClass('table-active');
        if ($rowEl.length) {
            $rowEl.addClass('table-active');
        }

        var panel = $('#panelFormPenjualanTagihan')[0];
        if (panel && panel.scrollIntoView) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        return false;
    };

    function doDeleteRowPenjualan(id) {
        $.ajax({
            url: ajaxUrlDelete,
            type: 'POST',
            dataType: 'json',
            data: { id: id }
        }).done(function(resp) {
            if (!resp || !resp.success) {
                notify('error', 'Gagal', (resp && resp.message) ? resp.message : 'Gagal menghapus data.');
                return;
            }
            if (resp.uuid_penjualan) {
                currentCtx.uuid_penjualan = resp.uuid_penjualan;
            }
            notify('success', 'Berhasil', resp.message || 'Data penjualan berhasil dihapus.');
            loadDetailPenjualan({ syncRecordToDetail: true });
        }).fail(function() {
            notify('error', 'Gagal', 'Gagal menghubungi server saat hapus.');
        });
    }

    window.handleDeletePenjualanRowTagihan = function(id) {
        id = parseInt(id, 10);
        if (!id) {
            notify('warning', 'Perhatian', 'ID data penjualan tidak valid.');
            return false;
        }

        if (window.Swal) {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Data Penjualan?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    doDeleteRowPenjualan(id);
                }
            });
            return false;
        }

        if (window.confirm('Hapus baris penjualan ini?')) {
            doDeleteRowPenjualan(id);
        }
        return false;
    };

    $(document).on('click', '.btn-edit-penjualan-tagihan', function(e) {
        e.preventDefault();
        e.stopPropagation();
        openEditPenjualanModal(this);
    });

    $(document).on('click', '.btn-edit-row-pj', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id') || $(this).data('id') || 0;
        window.handleEditPenjualanRowTagihan(id, this);
    });

    $(document).on('click', '.btn-del-row-pj', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id') || $(this).data('id') || 0;
        window.handleDeletePenjualanRowTagihan(id, this);
    });

    $('#btnTambahBarangPenjualanTagihan').on('click', function() {
        openModalPilihPersediaan();
    });

    function destroyPilihPersediaanTable() {
        if (tablePilihPersediaan && $.fn.DataTable && $.fn.DataTable.isDataTable('#tablePilihPersediaanTagihan')) {
            tablePilihPersediaan.destroy();
            tablePilihPersediaan = null;
        }
        $('#tablePilihPersediaanTagihan tbody').empty();
    }

    function updateIsiJumlahPreview() {
        var jml = parseAngkaId($('#isi_jumlah').val());
        var harga = parseAngkaId($('#isi_harga_satuan').val());
        $('#isi_total_preview').val(fmtNumber(jml * harga));
    }

    function openModalPilihPersediaan() {
        if (!currentCtx.uuid_penjualan) {
            alert('UUID penjualan belum tersedia. Buka ulang Edit Penjualan.');
            return;
        }
        var meta = currentCtx.meta || {};
        var tglJual = meta.tgl_jual_raw || '';
        var uuidUnit = meta.uuid_unit || '';
        var kategori = (meta.kategori || meta.barang_jasa || 'barang').toLowerCase();
        var tipe = (kategori === 'jasa') ? 'jasa' : 'barang';
        var bulanLabel = meta.bulan_label || '';
        var idPersediaanRef = meta.id_persediaan_barang || 0;

        $('#modalPilihPersediaanBulanLabel').text(
            'Bulan/Tahun: ' + (bulanLabel || '-') +
            ' | Kategori: ' + String(kategori || tipe).toUpperCase() +
            (meta.unit ? ' | Unit: ' + meta.unit : '')
        );
        $('#modalPilihPersediaanInfo').html('<div class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat data persediaan…</div>');
        destroyPilihPersediaanTable();
        $('#modalPilihPersediaanTagihan').modal('show');

        $.ajax({
            url: ajaxUrlListPersediaan,
            type: 'POST',
            dataType: 'json',
            data: {
                uuid_penjualan: currentCtx.uuid_penjualan,
                tgl_jual: tglJual,
                uuid_unit: uuidUnit,
                tipe: tipe,
                kategori: kategori,
                id_persediaan_barang: idPersediaanRef
            }
        }).done(function(resp) {
            if (!resp || !resp.success) {
                $('#modalPilihPersediaanInfo').html('<div class="alert alert-danger mb-0">' + ((resp && resp.message) ? resp.message : 'Gagal memuat persediaan.') + '</div>');
                return;
            }

            var labelKat = String(resp.kategori || resp.tipe || tipe).toUpperCase();
            $('#modalPilihPersediaanBulanLabel').text(
                'Bulan/Tahun: ' + (resp.bulan_label || bulanLabel || '-') +
                ' | Kategori: ' + labelKat +
                (resp.label_unit ? (' | Unit: ' + resp.label_unit) : '') +
                ' | ' + (resp.row_count || 0) + ' item'
            );
            $('#modalPilihPersediaanInfo').html(
                '<div class="alert alert-light border mb-0">Menampilkan stok <strong>' +
                (String(resp.tipe || tipe).toLowerCase() === 'jasa' ? 'JASA' : 'BARANG') +
                '</strong> persediaan bulan <strong>' +
                (resp.bulan_label || '-') + '</strong>. Pilih item, lalu isi jumlah penjualan.</div>'
            );

            var rowsHtml = '';
            (resp.rows || []).forEach(function(r) {
                var btn = r.bisa_pilih
                    ? '<button type="button" class="btn btn-success btn-xs btn-pilih-persediaan-tagihan"' +
                      ' data-id="' + r.id + '"' +
                      ' data-uuid-persediaan="' + (r.uuid_persediaan || '') + '"' +
                      ' data-nama="' + String(r.nama_barang || '').replace(/"/g, '&quot;') + '"' +
                      ' data-satuan="' + String(r.satuan || '').replace(/"/g, '&quot;') + '"' +
                      ' data-harga="' + r.harga_satuan + '"' +
                      ' data-sisa="' + r.sisa_stock + '">' +
                      '<i class="fa fa-check"></i> Pilih</button>'
                    : '<button type="button" class="btn btn-secondary btn-xs" disabled>Habis</button>';
                rowsHtml += '<tr>' +
                    '<td>' + (r.no || '') + '</td>' +
                    '<td>' + btn + '</td>' +
                    '<td>' + (r.tgl_po || '') + '</td>' +
                    '<td>' + (r.spop || '') + '</td>' +
                    '<td>' + (r.kategori || '') + '</td>' +
                    '<td>' + (r.nama_barang || '') + '</td>' +
                    '<td style="text-align:right">' + (r.harga_satuan_fmt || '') + '</td>' +
                    '<td>' + (r.satuan || '') + '</td>' +
                    '<td style="text-align:right">' + (r.sisa_stock_fmt || '0') +
                    (r.label_unit ? ('<br><small class="text-muted">' + r.label_unit + ': ' + r.nilai_unit + '</small>') : '') +
                    '</td>' +
                    '</tr>';
            });

            $('#tablePilihPersediaanTagihan tbody').html(
                rowsHtml || '<tr><td colspan="9" class="text-center text-muted">Tidak ada stok persediaan untuk bulan ini.</td></tr>'
            );

            if ($.fn.DataTable) {
                tablePilihPersediaan = $('#tablePilihPersediaanTagihan').DataTable({
                    scrollX: true,
                    scrollY: '50vh',
                    scrollCollapse: true,
                    paging: true,
                    pageLength: 10,
                    ordering: true,
                    info: true
                });
            }
        }).fail(function() {
            $('#modalPilihPersediaanInfo').html('<div class="alert alert-danger mb-0">Gagal menghubungi server saat memuat persediaan.</div>');
        });
    }

    function openModalIsiJumlah(item) {
        selectedPersediaan = item || null;
        if (!selectedPersediaan) return;

        $('#isi_id_persediaan').val(selectedPersediaan.id || '');
        $('#isi_uuid_persediaan').val(selectedPersediaan.uuid_persediaan || '');
        $('#isi_nama_barang').val(selectedPersediaan.nama || '');
        $('#isi_satuan').val(selectedPersediaan.satuan || '');
        $('#isi_sisa_stock').val(selectedPersediaan.sisa || 0);
        // Tampilkan harga dengan koma jika ada desimal
        var hargaAwal = Number(selectedPersediaan.harga || 0);
        if (Math.abs(hargaAwal - Math.round(hargaAwal)) < 0.0000001) {
            $('#isi_harga_satuan').val(String(Math.round(hargaAwal)));
        } else {
            $('#isi_harga_satuan').val(String(hargaAwal).replace('.', ','));
        }
        $('#isi_jumlah').val('1');
        $('#isi_jumlah_hint').text('Maksimal: ' + (selectedPersediaan.sisa || 0) + ' (harga boleh pakai koma, contoh 8000,45)');
        updateIsiJumlahPreview();
        $('#modalIsiJumlahPersediaanTagihan').modal('show');
        window.setTimeout(function() { $('#isi_jumlah').focus().select(); }, 350);
    }

    $(document).on('click', '.btn-pilih-persediaan-tagihan', function(e) {
        e.preventDefault();
        var $b = $(this);
        openModalIsiJumlah({
            id: $b.data('id'),
            uuid_persediaan: $b.attr('data-uuid-persediaan') || $b.data('uuid-persediaan') || '',
            nama: $b.attr('data-nama') || $b.data('nama') || '',
            satuan: $b.attr('data-satuan') || $b.data('satuan') || '',
            harga: parseFloat($b.attr('data-harga') || $b.data('harga') || 0),
            sisa: parseInt($b.attr('data-sisa') || $b.data('sisa') || 0, 10)
        });
    });

    $('#isi_jumlah, #isi_harga_satuan').on('input change', updateIsiJumlahPreview);

    $('#formIsiJumlahPersediaanTagihan').on('submit', function(e) {
        e.preventDefault();
        if (!currentCtx.uuid_penjualan) {
            alert('UUID penjualan tidak tersedia.');
            return;
        }
        var jumlah = parseAngkaId($('#isi_jumlah').val());
        var harga = parseAngkaId($('#isi_harga_satuan').val());
        var sisa = parseAngkaId($('#isi_sisa_stock').val());
        if (jumlah <= 0) {
            alert('Jumlah harus lebih dari 0.');
            return;
        }
        if (jumlah > sisa) {
            alert('Jumlah melebihi sisa stok (' + sisa + ').');
            return;
        }
        if (harga <= 0) {
            alert('Harga satuan harus lebih dari 0.');
            return;
        }

        $('#btnSimpanIsiJumlahTagihan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan…');
        $.ajax({
            url: ajaxUrlTambahDariPersediaan,
            type: 'POST',
            dataType: 'json',
            data: {
                uuid_penjualan: currentCtx.uuid_penjualan,
                id_persediaan_barang: $('#isi_id_persediaan').val(),
                uuid_persediaan: $('#isi_uuid_persediaan').val(),
                jumlah: $('#isi_jumlah').val(),
                harga_satuan: $('#isi_harga_satuan').val()
            }
        }).done(function(resp) {
            if (!resp || !resp.success) {
                alert((resp && resp.message) ? resp.message : 'Gagal menambah barang.');
                return;
            }
            currentCtx.dirty = true;
            // Update total konteks ke total semua record penjualan (lama + baru)
            if (resp.total_detail != null) {
                currentCtx.total_nominal_record = resp.total_detail;
            }
            var nmrpesan = resp.nmrpesan || currentCtx.nmrpesan || '';
            var nmrkirim = resp.nmrkirim || currentCtx.nmrkirim || '';
            // Langsung update kolom Total di datatable tagihan (grup no pesan/kirim)
            updateParentTagihanGroupTotal(
                resp.total_detail,
                resp.total_detail_fmt,
                nmrpesan,
                nmrkirim,
                resp.uuid_penjualan || currentCtx.uuid_penjualan
            );
            $('#modalIsiJumlahPersediaanTagihan').modal('hide');
            // Refresh datatable + TOTAL footer di modal Edit Penjualan
            loadDetailPenjualan({ syncRecordToDetail: true }).always(function() {
                // Pastikan TOTAL footer modal = total semua item
                if (resp.total_detail_fmt) {
                    $('#modalFooterTotal').text(resp.total_detail_fmt);
                    $('#modalTotalDetail').text(resp.total_detail_fmt);
                    $('#modalTotalRecord').text(resp.total_detail_fmt);
                }
            });
            // Refresh daftar stok di modal pilih (jika masih terbuka)
            if ($('#modalPilihPersediaanTagihan').hasClass('show')) {
                openModalPilihPersediaan();
            }
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: (resp.message || 'Barang ditambahkan.') +
                        (resp.total_detail_fmt ? (' Total dokumen: ' + resp.total_detail_fmt) : ''),
                    timer: 1400,
                    showConfirmButton: false
                });
            }
            // Reload halaman agar baris baru muncul di DATA TAGIHAN
            window.setTimeout(function() {
                window.location.reload();
            }, 1500);
        }).fail(function() {
            alert('Gagal menghubungi server saat menambah barang.');
        }).always(function() {
            $('#btnSimpanIsiJumlahTagihan').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan');
        });
    });

    $('#modalPilihPersediaanTagihan').on('hidden.bs.modal', function() {
        destroyPilihPersediaanTable();
    });

    $('#btnReloadPenjualanTagihan').on('click', function() {
        loadDetailPenjualan();
    });

    $('#pj_jumlah, #pj_harga_satuan').on('input change', updateTotalPreview);
    $('#pjm_jumlah, #pjm_harga_satuan').on('input change', updateTotalPreviewModalRow);

    $('#formEditRowBarangPenjualanTagihan').on('submit', function(e) {
        e.preventDefault();
        var payload = {
            id: $('#pjm_id').val(),
            uuid_penjualan: $('#pjm_uuid_penjualan').val() || currentCtx.uuid_penjualan,
            tgl_jual: $('#pjm_tgl_jual').val(),
            nmrpesan: $('#pjm_nmrpesan').val(),
            nmrkirim: $('#pjm_nmrkirim').val(),
            kode_barang: $('#pjm_kode_barang').val(),
            nama_barang: $('#pjm_nama_barang').val(),
            jumlah: $('#pjm_jumlah').val(),
            satuan: $('#pjm_satuan').val(),
            harga_satuan: $('#pjm_harga_satuan').val()
        };

        if (!payload.id) {
            notify('warning', 'Perhatian', 'ID data penjualan tidak ditemukan.');
            return;
        }

        $('#btnSimpanRowBarangPenjualanTagihan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: ajaxUrlUpdate,
            type: 'POST',
            dataType: 'json',
            data: payload
        }).done(function(resp) {
            if (!resp || !resp.success) {
                notify('error', 'Gagal', (resp && resp.message) ? resp.message : 'Gagal menyimpan perubahan data.');
                return;
            }

            currentCtx.dirty = true;
            if (resp.uuid_penjualan) {
                currentCtx.uuid_penjualan = resp.uuid_penjualan;
            }
            if (resp.total_detail != null) {
                currentCtx.total_nominal_record = resp.total_detail;
            }

            $('#modalEditRowBarangPenjualanTagihan').modal('hide');
            notify('success', 'Berhasil', resp.message || 'Data penjualan berhasil diupdate.');
            loadDetailPenjualan({ syncRecordToDetail: true }).done(function() {
                if ($('#modalEditPenjualanTagihan').hasClass('show')) {
                    $('body').addClass('modal-open');
                }
            });
        }).fail(function() {
            notify('error', 'Gagal', 'Gagal menghubungi server saat simpan edit data.');
        }).always(function() {
            $('#btnSimpanRowBarangPenjualanTagihan').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Perubahan');
        });
    });

    $('#formPenjualanTagihanCrud').on('submit', function(e) {
        e.preventDefault();
        var mode = $('#pj_mode').val() || 'update';
        var payload = {
            id: $('#pj_id').val(),
            ref_id: $('#pj_id').val() || (currentCtx.rows[0] ? currentCtx.rows[0].id : ''),
            uuid_penjualan: $('#pj_uuid_penjualan').val() || currentCtx.uuid_penjualan,
            tgl_jual: $('#pj_tgl_jual').val(),
            nmrpesan: $('#pj_nmrpesan').val(),
            nmrkirim: $('#pj_nmrkirim').val(),
            kode_barang: $('#pj_kode_barang').val(),
            nama_barang: $('#pj_nama_barang').val(),
            jumlah: $('#pj_jumlah').val(),
            satuan: $('#pj_satuan').val(),
            harga_satuan: $('#pj_harga_satuan').val()
        };

        var url = (mode === 'create') ? ajaxUrlCreate : ajaxUrlUpdate;
        if (mode !== 'create' && !payload.id) {
            alert('Pilih baris yang akan diubah.');
            return;
        }

        $('#btnSimpanPenjualanTagihan').prop('disabled', true);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: payload
        }).done(function(resp) {
            if (!resp || !resp.success) {
                notify('error', 'Gagal', (resp && resp.message) ? resp.message : 'Gagal menyimpan data.');
                return;
            }
            currentCtx.dirty = true;
            if (resp.uuid_penjualan) {
                currentCtx.uuid_penjualan = resp.uuid_penjualan;
            }
            // Update nominal record context ke total baru setelah edit
            if (resp.total_detail != null) {
                currentCtx.total_nominal_record = resp.total_detail;
            } else if (mode === 'update' && resp.total_nominal != null && currentCtx.rows.length <= 1) {
                currentCtx.total_nominal_record = resp.total_nominal;
            }
            notify('success', 'Berhasil', resp.message || 'Data penjualan berhasil disimpan.');
            loadDetailPenjualan({ syncRecordToDetail: true });
        }).fail(function() {
            notify('error', 'Gagal', 'Gagal menghubungi server saat simpan.');
        }).always(function() {
            $('#btnSimpanPenjualanTagihan').prop('disabled', false);
        });
    });

    $('#modalEditPenjualanTagihan').on('hidden.bs.modal', function() {
        destroyEditPenjualanTable();
        if (currentCtx.dirty) {
            window.location.reload();
        }
    });

    if ($.fn.datetimepicker && $('#tanggal_bayar_input_nominal').length) {
        $('#tanggal_bayar_input_nominal').datetimepicker({
            format: 'D-M-YYYY'
        });
    }

    // Pastikan modal tidak terpotong overflow content-wrapper / FixedColumns
    ['#modalEditPenjualanTagihan', '#modalEditRowBarangPenjualanTagihan', '#modalPilihPersediaanTagihan', '#modalIsiJumlahPersediaanTagihan'].forEach(function(sel) {
        if ($(sel).length) {
            $(sel).appendTo('body');
        }
    });

    setupEditRowModalStacking();

    console.log('Edit Penjualan tagihan: script siap (+ Tambah Barang).');
})();
</script>
