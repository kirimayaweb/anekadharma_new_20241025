<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal fade" id="modalLabarugiKodeAkun" tabindex="-1" role="dialog" aria-labelledby="modalLabarugiKodeAkunLabel" aria-hidden="true">
    <div class="modal-dialog labarugi-ka-modal-dialog" role="document">
        <div class="modal-content labarugi-kode-akun-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabarugiKodeAkunLabel">
                    <i class="fa fa-book"></i> Setting Kode Akun (<span id="labarugiKaModalKetLabel">-</span>)
                </h5>
                <button type="button" class="close labarugi-ka-modal-close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="labarugiKaUuidKeterangan" value="">
                <input type="hidden" id="labarugiKaJenisTab" value="">
                <input type="hidden" id="labarugiKaNamaKeterangan" value="">
                <div id="labarugiKaLoading" class="labarugi-ka-loading">Memuat data kode akun...</div>

                <div class="row labarugi-ka-dt-row" id="labarugiKaTablesWrap">
                    <div class="col-lg-6 col-md-12 labarugi-ka-dt-col mb-3 mb-lg-0">
                        <div class="labarugi-ka-section-title">Kode Akun (sys_kode_akun)</div>
                        <div class="labarugi-ka-dt-scroll-wrap">
                            <table id="tblLabarugiKodeAkunAvailable" class="table table-bordered table-hover labarugi-ka-table w-100">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th style="width:90px;">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 labarugi-ka-dt-col">
                        <div class="labarugi-ka-section-title">Kode Akun Terpilih untuk <span id="labarugiKaSelectedKetLabel">-</span></div>
                        <div class="labarugi-ka-dt-scroll-wrap">
                            <table id="tblLabarugiKodeAkunSelected" class="table table-bordered table-hover labarugi-ka-table w-100">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode Akun</th>
                                        <th>Nominal NS</th>
                                        <th style="width:90px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary labarugi-ka-modal-close" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
