<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="box box-warning box-solid">

            <div class="col-md-12">
                <div class="card card-warning">

                    <div class="card-header">
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-12" text-align="center"> <strong>
                                            Data Barang
                                        </strong></div>

                                </div>


                            </div>
                            <div class="col-6">

                            </div>



                        </div>



                    </div>
                    <br />



                    <div class="card-body">


                        <div class="form-group">


                            <!--  -->



                            <!-- <h2 style="margin-top:0px">Sys_nama_barang List</h2> -->
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4">
                                    <?php echo anchor(site_url('sys_nama_barang/create'), 'Tambah Barang Baru', 'class="btn btn-primary"'); ?>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div style="margin-top: 8px" id="message">
                                        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                    </div>
                                </div>
                                <div class="col-md-1 text-right">
                                </div>
                                <div class="col-md-3 text-right">
                                    <form action="<?php echo site_url('sys_nama_barang/index'); ?>" class="form-inline" method="get">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                                            <span class="input-group-btn">
                                                <?php
                                                if ($q <> '') {
                                                ?>
                                                    <a href="<?php echo site_url('sys_nama_barang'); ?>" class="btn btn-default">Reset</a>
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
                                    <!-- <th>Uuid Barang</th> -->
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr><?php
                                        foreach ($sys_nama_barang_data as $sys_nama_barang) {
                                        ?>
                                    <tr>
                                        <td width="80px"><?php echo ++$start ?></td>
                                        <!-- <td><?php //echo $sys_nama_barang->uuid_barang 
                                                    ?></td> -->
                                        <td><?php echo $sys_nama_barang->kode_barang ?></td>
                                        <td><?php echo $sys_nama_barang->nama_barang ?></td>
                                        <td><?php echo $sys_nama_barang->satuan ?></td>
                                        <td><?php echo $sys_nama_barang->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            // echo anchor(site_url('sys_nama_barang/read/' . $sys_nama_barang->id), 'Read');
                                            // echo ' | ';
                                            echo anchor(site_url('sys_nama_barang/update/' . $sys_nama_barang->id), 'Update');
                                            echo ' | ';
                                            echo anchor(site_url('sys_nama_barang/delete/' . $sys_nama_barang->id), 'Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
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
                                    <?php echo anchor(site_url('sys_nama_barang/excel'), 'Excel', 'class="btn btn-primary"'); ?>
                                </div>
                                <div class="col-md-6 text-right">
                                    <?php echo $pagination ?>
                                </div>
                            </div>

                            <!--  -->



                        </div>
                    </div>




                </div>
            </div>
        </div>
    </section>
</div>