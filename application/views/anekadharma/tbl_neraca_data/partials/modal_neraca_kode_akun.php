<style>
	#modalNeracaKodeAkun {
		z-index: 20050 !important;
	}

	#modalNeracaKodeAkun .modal-dialog {
		max-width: 960px;
	}

	#modalNeracaKodeAkun .table-section-title {
		font-weight: 600;
		margin-bottom: 8px;
	}

	.neraca-kode-akun-backdrop {
		z-index: 20040 !important;
	}
</style>

<div class="modal fade" id="modalNeracaKodeAkun" tabindex="-1" role="dialog" aria-labelledby="modalNeracaKodeAkunLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-warning">
				<h5 class="modal-title" id="modalNeracaKodeAkunLabel">
					<i class="fa fa-cog"></i> Setting Kode Akun — <span id="neracaKodeAkunFieldLabel">-</span>
				</h5>
				<button type="button" class="close neraca-kode-akun-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="neracaKodeAkunFieldName" value="">

				<div class="table-section-title">Daftar Kode Akun (sys_kode_akun)</div>
				<div class="table-responsive mb-4">
					<table id="tblNeracaKodeAkunAvailable" class="table table-bordered table-striped table-sm w-100">
						<thead class="thead-light">
							<tr>
								<th style="width:120px;">Kode Akun</th>
								<th>Nama Akun</th>
								<th style="width:90px;">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

				<div class="table-section-title">Kode Akun Terpilih untuk <span id="neracaKodeAkunFieldLabelBottom">-</span></div>
				<div class="table-responsive">
					<table id="tblNeracaKodeAkunSelected" class="table table-bordered table-striped table-sm w-100">
						<thead class="thead-light">
							<tr>
								<th style="width:120px;">Kode Akun</th>
								<th>Nama Akun</th>
								<th style="width:90px;">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary neraca-kode-akun-close" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
