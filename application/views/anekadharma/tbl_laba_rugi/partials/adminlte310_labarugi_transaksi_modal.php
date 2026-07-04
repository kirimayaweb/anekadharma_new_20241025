<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="modalLabarugiTransaksiUnit" tabindex="-1" role="dialog" aria-labelledby="modalLabarugiTransaksiUnitLabel" aria-hidden="true">
    <div class="modal-dialog labarugi-trx-modal-dialog" role="document">
        <div class="modal-content labarugi-trx-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabarugiTransaksiUnitLabel">
                    <i class="fa fa-list"></i> Rincian Transaksi — <span id="labarugiTrxKetLabel">-</span><span id="labarugiTrxUnitWrap"> / <span id="labarugiTrxUnitLabel">-</span></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="labarugiTrxLoading" class="labarugi-trx-loading">Memuat data transaksi...</div>
                <div id="labarugiTrxInfo" class="alert alert-warning labarugi-trx-info" style="display:none;"></div>
                <div class="labarugi-trx-total mb-2">Total NS: <strong class="text-danger" id="labarugiTrxTotal">0,00</strong></div>
                <div class="labarugi-trx-dt-scroll-wrap" id="labarugiTrxTableWrap">
                    <table id="tblLabarugiTransaksiUnit" class="table table-bordered table-hover labarugi-trx-table w-100">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>PL/Unit</th>
                                <th>Kode</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Sumber</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
