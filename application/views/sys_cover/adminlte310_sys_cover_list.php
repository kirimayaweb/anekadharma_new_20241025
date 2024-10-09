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
                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">MANAJEMEN DATA COVER</h3>
                    </div>
                    <br />

                    <div class="col-sm-6">
                        <?php echo anchor(site_url('sys_cover/create'), '<i class="fa fa-wpforms" aria-hidden="true"></i> Tambah Data Cover', 'class="btn btn-danger btn-sm"'); ?>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li> -->

                        </ol>
                    </div><!-- /.col -->

                    <div class="card-body">

                        <!-- <table id="example1" class="table table-bordered table-striped"> -->
                        <table id="example" class="stripe row-border order-column" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tingkat</th>
                                    <th>Nama Cover</th>
                                    
                                    <!-- <th>Mapel</th>
                                    <th>Kelas</th>
                                    <th>Tahun</th>
                                    <th>Semester</th>
                                    <th>Halaman</th> -->
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $start = 0;

                                foreach ($sys_cover_data as $sys_cover) {
                                ?>
                                    <tr>
                                        <td width="10px"><?php echo ++$start ?></td>
                                        <td><?php echo $sys_cover->tingkat ?></td>
                                        <td><?php echo $sys_cover->nama_cover ?></td>
                                        
                                        <!-- <td><?php //echo $sys_cover->mapel ?></td>
                                        <td><?php //echo $sys_cover->kelas ?></td>
                                        <td><?php //echo $sys_cover->tahun ?></td>
                                        <td><?php //echo $sys_cover->semester ?></td>
                                        <td><?php //echo $sys_cover->halaman ?></td> -->
                                        <td><?php echo $sys_cover->keterangan ?></td>
                                        <td style="text-align:center" width="200px">
                                            <?php
                                            // echo anchor(site_url('sys_cover/read/' . $sys_cover->id), '<i class="fa fa-eye" aria-hidden="true"></i>', 'class="btn btn-danger btn-sm"');
                                            // echo '  ';
                                            echo anchor(site_url('sys_cover/update/' . $sys_cover->id), '<i class="fa fa-pencil-square-o" aria-hidden="true">UBAH</i>', 'class="btn btn-danger btn-sm"');
                                            echo '  ';
                                            echo anchor(site_url('sys_cover/delete/' . $sys_cover->id), '<i class="fa fa-trash-o" aria-hidden="true">HAPUS</i>', 'class="btn btn-danger btn-sm" Delete', 'onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                        </table>

                    </div>

                    <!-- /.card-body -->
                </div>

            </div>

        </div>
    </section>
</div>


<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<style type="text/css">
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "scrollY": 350,
            "scrollX": true
        });
    });
</script>