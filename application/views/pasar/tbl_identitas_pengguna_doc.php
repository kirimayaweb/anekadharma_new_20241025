<!doctype html>
<html>

<head>
    <title>hDAFTAR IDENTITAS PENGGUNA </title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <style>
        .word-table {
            border: 1px solid black !important;
            border-collapse: collapse !important;
            width: 100%;
        }

        .word-table tr th,
        .word-table tr td {
            border: 1px solid black !important;
            padding: 5px 10px;
        }
    </style>
</head>

<body>
    <h2>
        <center><strong> DAFTAR IDENTITAS PENGGUNA </strong></center>
    </h2>
    <br><br>
    <!-- <h2>Tbl_identitas_pengguna List</h2> -->
    <table class="word-table" style="margin-bottom: 10px">
        <tr>
            <th style="text-align:center">NO</th>
            <th style="text-align:center">NIK</th>
            <th style="text-align:center">ID PEDAGANG</th>
            <th style="text-align:center">NAMA</th>
            <th style="text-align:center">ALAMAT</th>
            <th style="text-align:center">JENIS KELAMIN</th>
            <th style="text-align:center">STATUS</th>
            <th style="text-align:center">NO HANDPHONE</th>

        </tr><?php
                foreach ($tbl_identitas_pengguna_data as $tbl_identitas_pengguna) {
                ?>
            <tr>
                <td><?php echo ++$start ?></td>
                <td><?php tampil($tbl_identitas_pengguna->nik) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->idpedagang) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->nama) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->alamat) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->jeniskelamin) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->status) ?></td>
                <td><?php tampil($tbl_identitas_pengguna->no_hp) ?></td>
            </tr>
        <?php
                }
        ?>
    </table>
</body>

</html>