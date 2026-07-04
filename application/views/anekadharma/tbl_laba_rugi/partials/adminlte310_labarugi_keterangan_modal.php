<?php defined('BASEPATH') OR exit('No direct script access allowed');
$list_url = site_url('Tbl_laba_rugi/list_labarugi_keterangan');
$save_url = site_url('Tbl_laba_rugi/save_labarugi_keterangan');
$move_up_url = site_url('Tbl_laba_rugi/move_up_labarugi_keterangan');
$status_keterangan_opts = array('Title', 'keterangan', 'jumlah', 'jumlah total');
$status_labarugi_opts = array('rinci', 'sederhana', 'utama');
$this->load->helper('laba_rugi_keterangan');
$group_opts = labarugi_keterangan_default_groups();
?>
<div class="modal fade" id="modalLabarugiKeterangan" tabindex="-1" role="dialog" aria-labelledby="modalLabarugiKeteranganLabel" aria-hidden="true">
    <div class="modal-dialog labarugi-ket-modal-dialog" role="document">
        <div class="modal-content labarugi-keterangan-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabarugiKeteranganLabel">
                    <i class="fa fa-cog"></i> Setting Keterangan Laba Rugi — <span id="labarugiKetModalTabLabel">Rinci</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="labarugi-ket-modal-toolbar mb-2">
                    <button type="button" class="btn btn-sm btn-success" id="labarugiKetBtnTambah">
                        <i class="fa fa-plus"></i> Tambah Keterangan
                    </button>
                </div>
                <div class="labarugi-ket-dt-shell" id="labarugiKetDtShell">
                    <div class="labarugi-ket-dt-wrap" id="labarugiKetDtWrap">
                        <table class="table table-bordered table-hover labarugi-ket-table" id="tblLabarugiKeterangan" style="width:100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="labarugi-ket-col-id">No</th>
                                    <th class="labarugi-ket-col-urutan">Urutan</th>
                                    <th class="labarugi-ket-col-uuid">UUID</th>
                                    <th class="labarugi-ket-col-nama">Nama Keterangan</th>
                                    <th class="labarugi-ket-col-group">Group</th>
                                    <th class="labarugi-ket-col-status-ket">Status Keterangan</th>
                                    <th class="labarugi-ket-col-status-lr">Status Laba Rugi</th>
                                    <th class="labarugi-ket-col-ket">Keterangan</th>
                                    <th class="labarugi-ket-col-aksi">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="labarugiKetTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
window.labarugiKeteranganConfig = {
    listUrlBase: <?php echo json_encode($list_url); ?>,
    saveUrl: <?php echo json_encode($save_url); ?>,
    moveUpUrl: <?php echo json_encode($move_up_url); ?>,
    statusKeteranganOptions: <?php echo json_encode($status_keterangan_opts); ?>,
    statusLabarugiOptions: <?php echo json_encode($status_labarugi_opts); ?>,
    groupOptions: <?php echo json_encode($group_opts); ?>
};
</script>
