/**
 * DataTable untuk Modal PILIH JASA di Penjualan Jasa
 * File: datatable-pilih-jasa-penjualan.js
 */

(function() {
    'use strict';

    window.penjualanDtPilihBarang = null;
    window.penjualanTablePilihBarangId = '#table-pilih-jasa-penjualan';

    /**
     * Destroy DataTable jika sudah ada
     */
    window.destroyDataTablePilihBarang = function() {
        var $ = window.jQuery;
        if (!$) {
            return;
        }
        
        var $table = $(window.penjualanTablePilihBarangId);
        if (!$table.length) {
            return;
        }
        
        if ($.fn.DataTable.isDataTable($table)) {
            try {
                $table.DataTable().destroy();
            } catch (err) {
                console.warn('Error destroy DataTable:', err);
            }
        }
        
        window.penjualanDtPilihBarang = null;
    };

    /**
     * Hitung scroll Y untuk DataTable
     */
    window.hitungScrollYPilihBarangPenjualan = function() {
        var $modal = $('#modal-xl.modal-pilih-jasa-penjualan');
        if (!$modal.length) {
            return Math.max(360, Math.floor(window.innerHeight * 0.62));
        }
        
        var tinggiModal = $modal.find('.modal-dialog').innerHeight() || (window.innerHeight - 75);
        var headerH = $modal.find('.modal-header').outerHeight(true) || 52;
        var toolH = 40;
        var footDt = 40;
        
        var hasil = Math.max(300, Math.floor(tinggiModal - headerH - toolH - footDt - 20));
        return hasil;
    };

    /**
     * Inisialisasi DataTable
     */
    window.initDataTablePilihBarang = function() {
        var $ = window.jQuery;
        if (!$ || !$.fn.DataTable) {
            console.warn('jQuery atau DataTable tidak tersedia');
            return;
        }
        
        window.destroyDataTablePilihBarang();
        
        var $table = $(window.penjualanTablePilihBarangId);
        if (!$table.length) {
            console.warn('Tabel tidak ditemukan');
            return;
        }
        
        console.log('Inisialisasi DataTable PILIH JASA');
        
        try {
            var scrollHeight = window.hitungScrollYPilihBarangPenjualan();
            
            window.penjualanDtPilihBarang = $table.DataTable({
                paging: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], ['10', '25', '50', '100', 'Semua']],
                searching: true,
                search: {
                    caseInsensitive: true
                },
                info: true,
                ordering: true,
                order: [[5, 'asc']],
                autoWidth: false,
                responsive: false,
                scrollY: scrollHeight,
                scrollX: true,
                scrollCollapse: false,
                stateSave: false,
                fixedHeader: false,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        width: '50px'
                    },
                    {
                        targets: 1,
                        orderable: false,
                        searchable: false,
                        width: '80px'
                    },
                    {
                        targets: 9,
                        orderable: false,
                        searchable: false,
                        width: '80px'
                    },
                    {
                        targets: '_all',
                        orderable: true,
                        searchable: true
                    }
                ],
                dom: '<"row mb-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row mt-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: 'Cari...',
                    lengthMenu: '_MENU_ baris',
                    zeroRecords: 'Tidak ada jasa yang cocok',
                    info: 'Baris _START_ hingga _END_ dari _TOTAL_ jasa',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(filter dari _MAX_ total jasa)',
                    paginate: {
                        first: 'Awal |',
                        last: '| Akhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });
            
            console.log('DataTable PILIH JASA berhasil diinisialisasi');
            
        } catch (errDt) {
            console.error('Error saat inisialisasi DataTable PILIH JASA:', errDt);
        }
    };

    /**
     * Sesuaikan DataTable saat modal ditampilkan
     */
    window.sesuaikanDataTablePilihBarang = function() {
        if (!window.penjualanDtPilihBarang) {
            window.initDataTablePilihBarang();
            return;
        }
        
        try {
            var $ = window.jQuery;
            if (!$) {
                return;
            }
            
            var scrollHeight = window.hitungScrollYPilihBarangPenjualan();
            var $tableContainer = $(window.penjualanTablePilihBarangId).closest('.dataTables_wrapper');
            
            if ($tableContainer.length) {
                $tableContainer.find('.dataTables_scrollBody').css({
                    'max-height': scrollHeight + 'px',
                    'height': scrollHeight + 'px'
                });
            }
            
            window.penjualanDtPilihBarang.columns.adjust().draw(false);
            
        } catch (eAdj) {
            console.warn('Error sesuaikan DataTable:', eAdj);
        }
    };

})();
