<!-- Modal Referensi Persediaan (tab Belum ke Persediaan) -->
<div class="modal fade" id="modal-pj-referensi-persediaan" tabindex="-1" role="dialog" aria-labelledby="modalPjReferensiPersediaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:96%;">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title" id="modalPjReferensiPersediaanLabel"><i class="fas fa-link"></i> Referensi Persediaan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pb-2">
                <div id="pj-referensi-alert" class="mb-2"></div>
                <div id="pj-referensi-meta" class="mb-3 px-3 py-2 border rounded bg-light" style="font-size:1.15rem; line-height:1.45;"></div>
                <div id="pj-referensi-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat persediaan bulan terpilih...</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped mb-0" id="tbl-pj-referensi-persediaan" style="width:100%;">
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

<!-- Modal Isi Jumlah Refered (detail persediaan + qty) -->
<div class="modal fade" id="modal-pj-isi-jumlah-refered" tabindex="-1" role="dialog" aria-labelledby="modalPjIsiJumlahReferedLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-isi-jumlah-barang" role="document">
        <div class="modal-content">
            <form id="form-pj-isi-jumlah-refered" action="javascript:void(0);" method="post">
                <div class="modal-header bg-primary text-white py-2">
                    <h4 class="modal-title" id="modalPjIsiJumlahReferedLabel">Referensi Persediaan â€” Isi Jumlah</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="pj-isi-jumlah-alert" class="mb-2"></div>
                    <p class="mb-2 text-dark">Detail barang persediaan yang akan dijadikan referensi:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 pj-isi-jumlah-detail-table">
                            <tbody>
                                <tr><th>ID Persediaan</th><td id="pj-isi-jumlah-d-id"></td></tr>
                                <tr><th>Nama Barang</th><td id="pj-isi-jumlah-d-nama"></td></tr>
                                <tr><th>Kode / SPOP</th><td id="pj-isi-jumlah-d-kode"></td></tr>
                                <tr><th>Satuan persediaan</th><td id="pj-isi-jumlah-d-satuan"></td></tr>
                                <tr><th>HPP</th><td id="pj-isi-jumlah-d-hpp"></td></tr>
                                <tr><th>SA / Beli / Penjualan</th><td id="pj-isi-jumlah-d-mutasi"></td></tr>
                                <tr><th>Total 10 (stok)</th><td id="pj-isi-jumlah-d-total10"></td></tr>
                                <tr><th>UUID</th><td id="pj-isi-jumlah-d-uuid"><small></small></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group d-none">
                        <label>Barang (persediaan terpilih)</label>
                        <input type="text" class="form-control" id="pj-isi-jumlah-nama" value="" disabled>
                    </div>
                    <div class="form-group d-none">
                        <label>Satuan</label>
                        <input type="text" class="form-control" id="pj-isi-jumlah-satuan" value="" disabled>
                    </div>
                    <div class="alert alert-light border py-2 px-3 mb-3" id="pj-isi-jumlah-meta-qty"></div>
                    <div class="form-group mb-0">
                        <label class="penjualan-label-info-jumlah d-block" for="pj-isi-jumlah-input" id="pj-isi-jumlah-label">
                            Jumlah (default = qty penjualan)
                        </label>
                        <input type="number" class="form-control form-control-lg" id="pj-isi-jumlah-input" name="jumlah" min="1" step="1" placeholder="Isi jumlah barang" required>
                        <small class="form-text text-muted">Jika tidak diubah, jumlah = qty penjualan lalu klik SIMPAN.</small>
                    </div>
                    <p class="small text-info mt-2 mb-0">SIMPAN meng-update tabel <strong>persediaan</strong> (<code>penjualan += jumlah</code>, <code>total_10 -= jumlah</code>, kolom unit sesuai konsumen/unit) dan menandai penjualan <code>verified_persediaan = refered manual</code> (pindah ke tab Terverifikasi Manual).</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="pj-isi-jumlah-id-persediaan" value="">
                    <input type="hidden" id="pj-isi-jumlah-id-penjualan" value="">
                    <input type="hidden" id="pj-isi-jumlah-bulan" value="">
                    <input type="hidden" id="pj-isi-jumlah-force" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-pj-isi-jumlah-simpan">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
