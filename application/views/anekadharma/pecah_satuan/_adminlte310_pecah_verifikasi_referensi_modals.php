<!-- Modal Referensi Persediaan — Pecah Satuan -->
<div class="modal fade" id="modal-pecah-referensi-persediaan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:96%;">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title"><i class="fas fa-link"></i> Referensi Persediaan Sumber — Pecah Satuan</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body pb-2">
                <div id="pecah-referensi-alert" class="mb-2"></div>
                <div id="pecah-referensi-meta" class="mb-3 px-3 py-2 border rounded bg-light"></div>
                <p class="small text-muted mb-2">
                    Pilih baris persediaan sumber, klik <strong>Refered</strong>, lalu isi jumlah yang diambil dari sumber. Nama/satuan/HPP sumber boleh berbeda dari data pecah satuan.
                </p>
                <div id="pecah-referensi-loading" class="text-center py-4 text-muted d-none"><i class="fas fa-spinner fa-spin"></i> Memuat persediaan...</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped mb-0" id="tbl-pecah-referensi-persediaan" style="width:100%;">
                        <thead class="thead-light">
                            <tr>
                                <th>Refered</th>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>HPP</th>
                                <th>SA</th>
                                <th>Beli</th>
                                <th>Pecah Satuan</th>
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

<!-- Modal Isi Jumlah Refered — pecah satuan sumber -->
<div class="modal fade" id="modal-pecah-isi-jumlah-refered" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-isi-jumlah-barang" role="document">
        <div class="modal-content">
            <form id="form-pecah-isi-jumlah-refered" action="javascript:void(0);" method="post">
                <div class="modal-header bg-primary text-white py-2">
                    <h4 class="modal-title">Cari Sumber Barang — Isi Jumlah Dipecah</h4>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="pecah-isi-jumlah-alert" class="mb-2"></div>
                    <p class="mb-2 text-dark">Persediaan sumber terpilih:</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered mb-0 pecah-isi-jumlah-detail-table">
                            <tbody>
                                <tr><th>ID Persediaan</th><td id="pecah-isi-jumlah-d-id"></td></tr>
                                <tr><th>Nama Barang</th><td id="pecah-isi-jumlah-d-nama"></td></tr>
                                <tr><th>Kode / SPOP</th><td id="pecah-isi-jumlah-d-kode"></td></tr>
                                <tr><th>Satuan</th><td id="pecah-isi-jumlah-d-satuan"></td></tr>
                                <tr><th>HPP</th><td id="pecah-isi-jumlah-d-hpp"></td></tr>
                                <tr><th>SA / Beli / Pecah Satuan</th><td id="pecah-isi-jumlah-d-mutasi"></td></tr>
                                <tr><th>Total 10 (stok)</th><td id="pecah-isi-jumlah-d-total10"></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-light border py-2 px-3 mb-3" id="pecah-isi-jumlah-meta-qty"></div>
                    <div class="form-group mb-0">
                        <label class="d-block" for="pecah-isi-jumlah-input" id="pecah-isi-jumlah-label">Jumlah diambil dari sumber (default = qty pecah satuan)</label>
                        <input type="number" class="form-control form-control-lg" id="pecah-isi-jumlah-input" name="jumlah" min="1" step="1" placeholder="Isi jumlah dipecah dari sumber" required>
                        <small class="form-text text-muted">Jumlah ini mengurangi stok sumber (<code>pecah_satuan += jumlah</code>, <code>total_10 -= jumlah</code>) dan menambah barang baru sesuai qty record pecah satuan.</small>
                    </div>
                    <p class="small text-info mt-2 mb-0">SIMPAN meng-update <strong>persediaan</strong> dan menandai <strong>tbl_pembelian_pecah_satuan</strong>: <code>verified_persediaan</code>, <code>uuid_persediaan_refered_manual</code>, <code>nama_barang_refered_manual</code>, <code>satuan_refered_manual</code>, <code>hpp_refered_manual</code>, <code>jumlah_terpecah_dari_refered</code>.</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden" id="pecah-isi-jumlah-id-persediaan" value="">
                    <input type="hidden" id="pecah-isi-jumlah-id-pecah" value="">
                    <input type="hidden" id="pecah-isi-jumlah-bulan" value="">
                    <input type="hidden" id="pecah-isi-jumlah-is-update" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-pecah-isi-jumlah-simpan">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>
