<!doctype html>
<html>

<head>
    <title>Anekadharma</title>

</head>

<body>
    <h2 style="margin-top:0px">Data Uang Muka di Depan</h2>
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-4">
            <?php //echo anchor(site_url('tbl_uang_muka_didepan/create'), 'Create', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-4 text-center">
            <div style="margin-top: 8px" id="message">
                <?php //echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            </div>
        </div>
        <div class="col-md-1 text-right">
        </div>
        <div class="col-md-3 text-right">
           
        </div>
    </div>
    <table class="table table-bordered" style="margin-bottom: 10px">
        <tr>
            <th>No</th>
            <th>Uuid Uang Muka Didepan</th>
            <th>Tgl Transaksi</th>
            <th>Kode</th>
            <th>Dari</th>
            <th>Uraian</th>
            <th>Nominal</th>
            <th>Bank</th>
            <th>Nmr Rekening</th>
            <th>Action</th>
        </tr><?php
                foreach ($tbl_uang_muka_didepan_data as $tbl_uang_muka_didepan) {
                ?>
            <tr>
                <td width="80px"><?php echo ++$start ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->uuid_uang_muka_didepan ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->tgl_transaksi ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->kode ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->dari ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->uraian ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->nominal ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->bank ?></td>
                <td><?php //echo $tbl_uang_muka_didepan->nmr_rekening ?></td>
                <td style="text-align:center" width="200px">
                    <?php
                    echo anchor(site_url('tbl_uang_muka_didepan/read/' . $tbl_uang_muka_didepan->id), 'Read');
                    echo ' | ';
                    echo anchor(site_url('tbl_uang_muka_didepan/update/' . $tbl_uang_muka_didepan->id), 'Update');
                    echo ' | ';
                    echo anchor(site_url('tbl_uang_muka_didepan/delete/' . $tbl_uang_muka_didepan->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                    ?>
                </td>
            </tr>
        <?php
                }
        ?>
    </table>
    <div class="row">
        <div class="col-md-6">
            <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
            <?php echo anchor(site_url('tbl_uang_muka_didepan/excel'), 'Excel', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination ?>
        </div>
    </div>
</body>

</html>