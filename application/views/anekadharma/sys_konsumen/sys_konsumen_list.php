<div class="content-wrapper">
    <h2 style="margin-top:0px">Daftar Konsumen / Unit</h2>
    <div class="row" style="margin-bottom: 10px">
        <div class="col-md-4">
            <?php echo anchor(site_url('sys_konsumen/create'), 'Create', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-4 text-center">
            <div style="margin-top: 8px" id="message">
                <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
            </div>
        </div>
        <div class="col-md-1 text-right">
        </div>
        <div class="col-md-3 text-right">
            <form action="<?php echo site_url('sys_konsumen/index'); ?>" class="form-inline" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                    <span class="input-group-btn">
                        <?php
                        if ($q <> '') {
                        ?>
                            <a href="<?php echo site_url('sys_konsumen'); ?>" class="btn btn-default">Reset</a>
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
            <th>Uuid Konsumen</th>
            <th>Kode Konsumen</th>
            <th>Nama Konsumen</th>
            <th>Nmr Kontak Konsumen</th>
            <th>Alamat Konsumen</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr><?php
                foreach ($sys_konsumen_data as $sys_konsumen) {
                ?>
            <tr>
                <td width="80px"><?php echo ++$start ?></td>
                <td><?php echo $sys_konsumen->uuid_konsumen ?></td>
                <td><?php echo $sys_konsumen->kode_konsumen ?></td>
                <td><?php echo $sys_konsumen->nama_konsumen ?></td>
                <td><?php echo $sys_konsumen->nmr_kontak_konsumen ?></td>
                <td><?php echo $sys_konsumen->alamat_konsumen ?></td>
                <td><?php echo $sys_konsumen->keterangan ?></td>
                <td style="text-align:center" width="200px">
                    <?php
                    echo anchor(site_url('sys_konsumen/read/' . $sys_konsumen->id), 'Read');
                    echo ' | ';
                    echo anchor(site_url('sys_konsumen/update/' . $sys_konsumen->id), 'Update');
                    echo ' | ';
                    echo anchor(site_url('sys_konsumen/delete/' . $sys_konsumen->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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
            <?php echo anchor(site_url('sys_konsumen/excel'), 'Excel', 'class="btn btn-primary"'); ?>
        </div>
        <div class="col-md-6 text-right">
            <?php echo $pagination ?>
        </div>
    </div>
</div>