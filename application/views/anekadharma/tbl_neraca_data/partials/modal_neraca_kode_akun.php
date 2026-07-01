<style>
	#modalNeracaKodeAkun {
		z-index: 20050 !important;
	}

	#modalNeracaKodeAkun.show {
		display: block !important;
	}

	#modalNeracaKodeAkun .modal-dialog {
		max-width: 96vw;
		width: 1400px;
		z-index: 20051 !important;
	}

	#modalNeracaKodeAkun .modal-content {
		z-index: 20052 !important;
	}

	#modalNeracaKodeAkun .table-section-title {
		font-weight: 600;
		margin-bottom: 8px;
		font-size: 0.95rem;
	}

	#modalNeracaKodeAkun .neraca-kode-akun-dt-row {
		margin-left: 0;
		margin-right: 0;
	}

	#modalNeracaKodeAkun .neraca-kode-akun-dt-col {
		padding-left: 8px;
		padding-right: 8px;
	}

	#modalNeracaKodeAkun .neraca-dt-scroll-wrap {
		width: 100%;
		overflow: auto;
		border: 1px solid #dee2e6;
		border-radius: 4px;
		background: #fff;
	}

	#modalNeracaKodeAkun .dataTables_wrapper {
		width: 100%;
	}

	#modalNeracaKodeAkun .dataTables_scrollBody {
		overflow-x: auto !important;
		overflow-y: auto !important;
	}

	#modalNeracaKodeAkun .neraca-selected-total {
		margin-top: 10px;
		padding: 8px 12px;
		background: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: 4px;
		text-align: right;
		font-weight: bold;
		font-size: 1rem;
	}

	.neraca-kode-akun-backdrop {
		z-index: 20040 !important;
	}

	body.neraca-kode-akun-modal-open {
		overflow: hidden;
	}

	#neracaKodeAkunLoading {
		display: none;
		padding: 24px;
		text-align: center;
		font-weight: 600;
	}
</style>

<div class="modal fade" id="modalNeracaKodeAkun" tabindex="-1" role="dialog" aria-labelledby="modalNeracaKodeAkunLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<div>
					<h5 class="modal-title mb-1" id="modalNeracaKodeAkunLabel">
						<i class="fa fa-cog"></i> <span id="neracaKodeAkunModalTitle">Setting Kode Akun</span>
					</h5>
					<div class="small text-dark" id="neracaKodeAkunPeriodInfo">-</div>
				</div>
				<button type="button" class="close neraca-kode-akun-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="neracaKodeAkunFieldName" value="">
				<div id="neracaKodeAkunLoading">Memuat data kode akun...</div>

				<div class="row neraca-kode-akun-dt-row" id="neracaKodeAkunTablesWrap">
					<div class="col-lg-6 col-md-12 neraca-kode-akun-dt-col mb-3 mb-lg-0">
						<div class="table-section-title">Daftar Kode Akun (sys_kode_akun)</div>
						<div class="neraca-dt-scroll-wrap">
							<table id="tblNeracaKodeAkunAvailable" class="table table-bordered table-striped table-sm w-100">
								<thead class="thead-light">
									<tr>
										<th style="min-width:100px;">Kode Akun</th>
										<th style="min-width:180px;">Nama Akun</th>
										<th style="min-width:80px;">Aksi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>

					<div class="col-lg-6 col-md-12 neraca-kode-akun-dt-col">
						<div class="table-section-title">Kode Akun Terpilih — <span id="neracaKodeAkunFieldLabelBottom">-</span></div>
						<div class="neraca-dt-scroll-wrap">
							<table id="tblNeracaKodeAkunSelected" class="table table-bordered table-striped table-sm w-100">
								<thead class="thead-light">
									<tr>
										<th style="min-width:100px;">Kode Akun</th>
										<th style="min-width:160px;">Nama Akun</th>
										<th style="min-width:130px;">Nominal NS</th>
										<th style="min-width:80px;">Aksi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="neraca-selected-total">
							Total Nominal: Rp. <span id="neracaKodeAkunSelectedTotal">0,00</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary neraca-kode-akun-close" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
