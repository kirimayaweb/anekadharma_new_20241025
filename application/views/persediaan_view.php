<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Persediaan</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
    <h1>Persediaan</h1>
    <p><?php echo $teks_update; ?></p>
    <table id="persediaan-table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>UUID Persediaan</th>
                <th>UUID SPOP</th>
                <th>Tanggal Beli</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>HPP</th>
                <th>SA</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh DataTables -->
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#persediaan-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?php echo site_url('persediaan/data_tables'); ?>",
                    "type": "POST"
                },
                "columns": [
                    { "data": "id" },
                    { "data": "uuid_persediaan" },
                    { "data": "uuid_spop" },
                    { "data": "tanggal_beli" },
                    { "data": "namabarang" },
                    { "data": "satuan" },
                    { "data": "hpp" },
                    { "data": "sa" }
                ]
            });
        });
    </script>
</body>
</html>