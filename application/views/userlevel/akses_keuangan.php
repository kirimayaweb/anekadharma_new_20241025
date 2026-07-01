<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <?php
                if ($this->session->flashdata('message')) {
                    echo alert('alert-success', 'Berhasil', $this->session->flashdata('message'));
                }
                echo alert(
                    'alert-info',
                    'Paket Keuangan',
                    'Memberikan akses ke semua menu <strong>Jurnal</strong>, <strong>Accounting</strong>, dan <strong>Laporan</strong> untuk level ini. User dengan level ini otomatis melihat menu tersebut setelah login.'
                );
                ?>
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Kendali Keuangan — Level: <b><?php echo htmlspecialchars($level['nama_level'], ENT_QUOTES, 'UTF-8'); ?></b>
                            (ID <?php echo (int) $level['id_user_level']; ?>)
                        </h3>
                        <div class="box-tools pull-right">
                            <?php echo anchor(site_url('userlevel'), 'Kembali ke Level User', 'class="btn btn-default btn-sm"'); ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <p>
                            Status paket:
                            <strong><?php echo (int) $status['granted']; ?> / <?php echo (int) $status['total']; ?></strong> submenu
                            <?php if ($status['complete']) { ?>
                                <span class="label label-success">Lengkap</span>
                            <?php } else { ?>
                                <span class="label label-warning">Belum lengkap</span>
                            <?php } ?>
                        </p>
                        <p>
                            <?php echo anchor(
                                site_url('userlevel/grant_keuangan_action/' . $level['id_user_level']),
                                '<i class="fa fa-check"></i> Berikan Semua (Jurnal + Accounting + Laporan)',
                                'class="btn btn-success" onclick="return confirm(\'Berikan paket keuangan lengkap untuk level ini?\');"'
                            ); ?>
                            <?php echo anchor(
                                site_url('userlevel/revoke_keuangan_action/' . $level['id_user_level']),
                                '<i class="fa fa-times"></i> Cabut Paket Keuangan',
                                'class="btn btn-danger" onclick="return confirm(\'Cabut semua hak keuangan level ini?\');"'
                            ); ?>
                            <?php echo anchor(
                                site_url('userlevel/sync_keuangan_users_action/' . $level['id_user_level']),
                                '<i class="fa fa-refresh"></i> Sinkron ke Semua User Level Ini',
                                'class="btn btn-info"'
                            ); ?>
                        </p>
                        <?php if ($is_keuangan_level) { ?>
                            <p class="text-muted">
                                Level ini terdaftar sebagai <strong>level keuangan default</strong> di konfigurasi sistem.
                            </p>
                        <?php } ?>
                    </div>
                </div>

                <?php foreach ($groups as $group) { ?>
                    <div class="box box-warning box-solid">
                        <div class="box-header">
                            <h3 class="box-title"><?php echo htmlspecialchars($group['parent_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="40">No</th>
                                        <th>Menu</th>
                                        <th>Link</th>
                                        <th width="100">Akses Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($group['items'] as $item) { ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><code><?php echo htmlspecialchars($item['link'], ENT_QUOTES, 'UTF-8'); ?></code></td>
                                            <td class="text-center">
                                                <?php if ($item['granted']) { ?>
                                                    <span class="label label-success">Ya</span>
                                                <?php } else { ?>
                                                    <span class="label label-default">Tidak</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
