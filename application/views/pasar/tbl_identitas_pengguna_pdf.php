<!doctype html>
<html>

<head>
    <title>DAFTAR IDENTITAS PENGGUNA</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />

</head>

<body>
    <h2 align="center"> <strong> DAFTAR IDENTITAS PENGGUNA </strong></h2>
    <br><br>

    <table width='430' cellspacing='0' padding-right='150px' border-collapse: collapse;>

        <tr valign='top' align='center'>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='2' width='20'>
                NO
            </td>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>
                NIK
            </td>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>

                ID PENYEWA
            </td>



            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>
                NAMA

            </td>


            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='8' width='80'>

                ALAMAT

            </td>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.00in;font-size: 8' align='center' colspan='4' width='40'>

                JENIS KELAMIN

            </td>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='3' width='30'>

                STATUS

            </td>

            <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='5' width='50'>

                HANDPHONE

            </td>



        </tr>


        <?php
        $start = 0;
        foreach ($tbl_identitas_pengguna_data as $tbl_identitas_pengguna) {
        ?>
            <tr>

                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='2' width='20'>
                    <?php echo ++$start ?>
                </td>



                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>
                    <?php tampil($tbl_identitas_pengguna->nik) ?>
                </td>




                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>
                    <?php tampil($tbl_identitas_pengguna->idpedagang) ?>
                </td>



                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='7' width='70'>
                    <?php tampil($tbl_identitas_pengguna->nama) ?>
                </td>



                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='8' width='80'>
                    <?php tampil($tbl_identitas_pengguna->alamat) ?>
                </td>



                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.00in;font-size: 8' align='center' colspan='4' width='40'>
                    <?php tampil($tbl_identitas_pengguna->jeniskelamin) ?>

                </td>


                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='3' width='30'>
                    <?php tampil($tbl_identitas_pengguna->status) ?>

                </td>



                <td style='border: 1px solid #000000; padding: 0.00in 0.08in 0.05in 0.08in;font-size: 8' align='center' colspan='5' width='50'>
                    <?php tampil($tbl_identitas_pengguna->no_hp) ?>

                </td>

            </tr>
        <?php
        }
        ?>
    </table>
    <!-- </table> -->
</body>

</html>