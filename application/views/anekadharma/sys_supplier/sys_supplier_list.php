<div class="content-wrapper">
<h2 style="margin-top:0px">Daftar Supplier</h2>

<table class="table table-bordered" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Uuid Supplier</th>
        <th>Kode Supplier</th>
        <th>Nama Supplier</th>
        <th>Nmr Kontak Supplier</th>
        <th>Alamat Supplier</th>
        <th>Keterangan</th>
        <th>Action</th>
    </tr><?php
            foreach ($sys_supplier_data as $sys_supplier) {
            ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $sys_supplier->uuid_supplier ?></td>
            <td><?php echo $sys_supplier->kode_supplier ?></td>
            <td><?php echo $sys_supplier->nama_supplier ?></td>
            <td><?php echo $sys_supplier->nmr_kontak_supplier ?></td>
            <td><?php echo $sys_supplier->alamat_supplier ?></td>
            <td><?php echo $sys_supplier->keterangan ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('sys_supplier/read/' . $sys_supplier->id), 'Read');
                echo ' | ';
                echo anchor(site_url('sys_supplier/update/' . $sys_supplier->id), 'Update');
                echo ' | ';
                echo anchor(site_url('sys_supplier/delete/' . $sys_supplier->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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
        <?php echo anchor(site_url('sys_supplier/excel'), 'Excel', 'class="btn btn-primary"'); ?>
    </div>
    <div class="col-md-6 text-right">
        <?php echo $pagination ?>
    </div>
</div>
</div>