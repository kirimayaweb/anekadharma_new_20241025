<!-- Modal Referensi Persediaan (tab Verifikasi Pembelian) -->
<div class="modal fade" id="modal-pem-referensi-persediaan" tabindex="-1" role="dialog" aria-labelledby="modalPemReferensiPersediaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:96%;">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title" id="modalPemReferensiPersediaanLabel"><i class="fas fa-link"></i> Referensi Persediaan — Pembelian</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pb-2">
                <div id="pem-referensi-alert" class="mb-2"></div>
                <div id="pem-referensi-meta" class="mb-3 px-3 py-2 border rounded bg-light" style="font-size:1.15rem; line-height:1.45;"></div>
                <div id="pem-referensi-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat persediaan bulan terpilih...</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped mb-0" id="tbl-pem-referensi-persediaan" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Referensi</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>HPP</th>
                                <th>SA</th>
                                <th>Beli</th>
                                <th>Penjualan</th>
                                <th>Total 10</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Isi Jumlah Refered (pembelian menambah stok) -->
<div class="modal fade" id="modal-pem-isi-jumlah-refered" tabindex="-1" role="dialog" aria-labelledby="modalPemIsiJumlahReferedLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-isi-jumlah-barang" role="document">
        <div class="modal-content">
            <form id="form-pem-isi-jumlah-refered" action="javascript:void(0);" method="post">
                <div class="modal-header bg-primary text-white py-2">
                    <h4 class="modal-title" id="modalPemIsiJumlahReferedLabel">Referensi Persediaan — Isi Jumlah (Pembelian)</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="pem-isi-jumlah-alert" class="mb-2"></div>
                    <p class="mb-2 text-dark">Detail barang persediaan yang akan dijadikan referensi:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 pem-isi-jumlah-detail-table">
                            <tbody>
                                <tr><th>ID Persediaan</th><td id="pem-isi-jumlah-d-id"></td></tr>
                                <tr><th>Nama Barang</th><td id="pem-isi-jumlah-d-nama"></td></tr>
                                <tr><th>Kode / SPOP</th><td id="pem-isi-jumlah-d-kode"></td></tr>
                                <tr><th>Satuan persediaan</th><td id="pem-isi-jumlah-d-satuan"></td></tr>
                                <tr><th>HPP</th><td id="pem-isi-jumlah-d-hpp"></td></tr>
                                <tr><th>SA / Beli / Penjualan</th><td id="pem-isi-jumlah-d-mutasi"></td></tr>
                                <tr><th>Total 10 (stok)</th><td id="pem-isi-jumlah-d-total10"></td></tr>
                                <tr><th>UUID</th><td id="pem-isi-jumlah-d-uuid"><small></small></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-light border py-2 px-3 mb-3" id="pem-isi-jumlah-meta-qty"></div>
                    <div class="form-group mb-0">
                        <label class="d-block" for="pem-isi-jumlah-input" id="pem-isi-jumlah-label">
                            Jumlah (default = qty pembelian)
                        </label>
                        <input type="number" class="form-control form-control-lg" id="pem-isi-jumlah-input" name="jumlah" min="1" step="1" placeholder="Isi jumlah barang" required>
                        <small class="form-text text-muted">Pembelian menambah stok: <code>beli += jumlah</code>, <code>total_10 += jumlah</code>.</small>
                    </div>
                    <p class="small text-info mt-2 mb-0">SIMPAN meng-update tabel <strong>persediaan</strong> dan menandai pembelian <code>verified_persediaan = refered manual</code>.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="pem-isi-jumlah-id-persediaan" value="">
                    <input type="hidden" id="pem-isi-jumlah-id-pembelian" value="">
                    <input type="hidden" id="pem-isi-jumlah-bulan" value="">
                    <input type="hidden" id="pem-isi-jumlah-force" value="0">
                    <input type="hidden" id="pem-isi-jumlah-tabel" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-pem-isi-jumlah-simpan">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
