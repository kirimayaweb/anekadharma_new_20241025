<!doctype html>
<html>

<head>
    <title>harviacode.com - codeigniter crud generator</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <style>
        body {
            padding: 15px;
        }
    </style>
</head>

<body>
    <h2 style="margin-top:0px">Tbl_pendapatan_lain_lain List</h2>
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-4">
            <?php echo anchor(site_url('tbl_pendapatan_lain_lain/create'), 'Create', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-4 text-center">
            <div style="margin-top: 8px" id="message">
                <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            </div>
        </div>
        <div class="col-md-1 text-right">
        </div>
        <div class="col-md-3 text-right">
            <form action="<?php echo site_url('tbl_pendapatan_lain_lain/index'); ?>" class="form-inline" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <?php
                        if ($q <> '') {
                        ?>
                            <a href="<?php echo site_url('tbl_pendapatan_lain_lain'); ?>" class="btn btn-default">Reset</a>
                        <?php
                        }
                        ?>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <table class="table table-bordered" style="margin-bottom: 10px">
        <tr>
            <th>No</th>
            <th>Uuid Pendapatan Lain Lain</th>
            <th>Tgl Transaksi</th>
            <th>Kode</th>
            <th>Dari</th>
            <th>Uraian</th>
            <th>Nominal</th>
            <th>Bank</th>
            <th>Nmr Rekening</th>
            <th>Action</th>
        </tr><?php
                foreach ($tbl_pendapatan_lain_lain_data as $tbl_pendapatan_lain_lain) {
                ?>
            <tr>
                <td width="80px"><?php echo ++$start ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->uuid_pendapatan_lain_lain ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->tgl_transaksi ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->kode ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->dari ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->uraian ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->nominal ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->bank ?></td>
                <td><?php echo $tbl_pendapatan_lain_lain->nmr_rekening ?></td>
                <td style="text-align:center" width="200px">
                    <?php
                    echo anchor(site_url('tbl_pendapatan_lain_lain/read/' . $tbl_pendapatan_lain_lain->id), 'Read');
                    echo ' | ';
                    echo anchor(site_url('tbl_pendapatan_lain_lain/update/' . $tbl_pendapatan_lain_lain->id), 'Update');
                    echo ' | ';
                    echo anchor(site_url('tbl_pendapatan_lain_lain/delete/' . $tbl_pendapatan_lain_lain->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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
            <?php echo anchor(site_url('tbl_pendapatan_lain_lain/excel'), 'Excel', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination ?>
        </div>
    </div>
</body>

</html>