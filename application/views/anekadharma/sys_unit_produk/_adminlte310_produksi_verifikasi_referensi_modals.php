<!-- Modal Referensi Persediaan — tab Verifikasi Persediaan (Produksi) -->
<div class="modal fade" id="modal-prod-referensi-persediaan" tabindex="-1" role="dialog" aria-labelledby="modalProdReferensiPersediaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:96%;">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title" id="modalProdReferensiPersediaanLabel"><i class="fas fa-link"></i> Referensi Persediaan — Bahan Produksi</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pb-2">
                <div id="prod-referensi-alert" class="mb-2"></div>
                <div id="prod-referensi-meta" class="mb-3 px-3 py-2 border rounded bg-light" style="font-size:1.05rem; line-height:1.45;"></div>
                <div id="prod-referensi-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat persediaan bulan terpilih...</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped mb-0" id="tbl-prod-referensi-persediaan" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Refered</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>HPP</th>
                                <th>SA</th>
                                <th>Beli</th>
                                <th>Bahan Produksi</th>
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

<!-- Modal Isi Jumlah Refered — bahan produksi -->
<div class="modal fade" id="modal-prod-isi-jumlah-refered" tabindex="-1" role="dialog" aria-labelledby="modalProdIsiJumlahReferedLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-isi-jumlah-barang" role="document">
        <div class="modal-content">
            <form id="form-prod-isi-jumlah-refered" action="javascript:void(0);" method="post">
                <div class="modal-header bg-primary text-white py-2">
                    <h4 class="modal-title" id="modalProdIsiJumlahReferedLabel">Referensi Persediaan — Isi Jumlah Bahan</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="prod-isi-jumlah-alert" class="mb-2"></div>
                    <p class="mb-2 text-dark">Detail barang persediaan yang akan dijadikan referensi:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 prod-isi-jumlah-detail-table">
                            <tbody>
                                <tr><th>ID Persediaan</th><td id="prod-isi-jumlah-d-id"></td></tr>
                                <tr><th>Nama Barang</th><td id="prod-isi-jumlah-d-nama"></td></tr>
                                <tr><th>Kode / SPOP</th><td id="prod-isi-jumlah-d-kode"></td></tr>
                                <tr><th>Satuan persediaan</th><td id="prod-isi-jumlah-d-satuan"></td></tr>
                                <tr><th>HPP</th><td id="prod-isi-jumlah-d-hpp"></td></tr>
                                <tr><th>SA / Beli / Bahan Produksi</th><td id="prod-isi-jumlah-d-mutasi"></td></tr>
                                <tr><th>Total 10 (stok)</th><td id="prod-isi-jumlah-d-total10"></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-light border py-2 px-3 mb-3" id="prod-isi-jumlah-meta-qty"></div>
                    <div class="form-group mb-0">
                        <label class="d-block" for="prod-isi-jumlah-input" id="prod-isi-jumlah-label">Jumlah (default = jumlah bahan produksi)</label>
                        <input type="number" class="form-control form-control-lg" id="prod-isi-jumlah-input" name="jumlah" min="1" step="1" placeholder="Isi jumlah bahan" required>
                        <small class="form-text text-muted">Jika tidak diubah, jumlah = qty bahan produksi lalu klik SIMPAN.</small>
                    </div>
                    <p class="small text-info mt-2 mb-0">SIMPAN meng-update tabel <strong>persediaan</strong> (<code>bahan_produksi += jumlah</code>, <code>total_10 -= jumlah</code>) dan menandai record <strong>sys_unit_produk_bahan</strong> <code>verified_persediaan = refered manual</code>.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="prod-isi-jumlah-id-persediaan" value="">
                    <input type="hidden" id="prod-isi-jumlah-id-bahan" value="">
                    <input type="hidden" id="prod-isi-jumlah-bulan" value="">
                    <input type="hidden" id="prod-isi-jumlah-is-update" value="0">
                    <input type="hidden" id="prod-isi-jumlah-force" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-prod-isi-jumlah-simpan">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
