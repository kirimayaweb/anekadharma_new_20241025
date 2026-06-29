<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header"><strong>Berikan Akses ke User Tertentu</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/access_grant_user'); ?>" method="post">
                    <?php echo cms_csrf_field(); ?>
                    <div class="form-group">
                        <label>Pilih User</label>
                        <select name="id_user" class="form-control" required>
                            <option value="">- Pilih User -</option>
                            <?php foreach ($users as $u): ?>
                                <?php $uid = isset($u->id_users) ? $u->id_users : (isset($u->id) ? $u->id : 0); ?>
                                <option value="<?php echo (int) $uid; ?>"><?php echo htmlspecialchars(isset($u->full_name) ? $u->full_name : (isset($u->email_user) ? $u->email_user : 'User'), ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Berikan Akses</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header"><strong>Berikan Akses ke Level User</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/access_grant_level'); ?>" method="post">
                    <?php echo cms_csrf_field(); ?>
                    <div class="form-group">
                        <label>Pilih Level</label>
                        <select name="id_user_level" class="form-control" required>
                            <option value="">- Pilih Level -</option>
                            <?php foreach ($user_levels as $lv): ?>
                                <?php $lid = isset($lv->id_user_level) ? $lv->id_user_level : (isset($lv->id) ? $lv->id : 0); ?>
                                <option value="<?php echo (int) $lid; ?>"><?php echo htmlspecialchars(isset($lv->nama_level) ? $lv->nama_level : ('Level ' . $lid), ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Berikan Akses Level</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header"><strong>Daftar Akses Khusus CMS</strong></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead><tr><th>Tipe</th><th>ID</th><th>Diberikan</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php if (empty($access_rows)): ?>
                <tr><td colspan="4" class="text-muted text-center">Belum ada akses khusus. Gunakan level default (<?php echo htmlspecialchars(isset($settings['allowed_admin_levels']) ? $settings['allowed_admin_levels'] : '1,2,99', ENT_QUOTES, 'UTF-8'); ?>) atau tbl_hak_akses menu.</td></tr>
            <?php else: foreach ($access_rows as $a): ?>
                <tr>
                    <td><?php echo $a->id_user ? 'User' : 'Level'; ?></td>
                    <td><?php echo $a->id_user ? (int)$a->id_user : (int)$a->id_user_level; ?></td>
                    <td><?php echo cms_format_date($a->granted_at); ?></td>
                    <td>
                        <?php if ($a->id_user): ?>
                            <form action="<?php echo site_url('cms-admin/access_revoke_user/' . $a->id_user); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Cabut</button></form>
                        <?php else: ?>
                            <form action="<?php echo site_url('cms-admin/access_revoke_level/' . $a->id_user_level); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Cabut</button></form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info mt-3">
    <strong>Keamanan akses CMS Admin:</strong>
    <ul class="mb-0">
        <li>Wajib login + verifikasi MFA (jika aktif)</li>
        <li>Pengecekan hak menu <code>cms_admin</code> via <code>tbl_menu</code> / <code>tbl_hak_akses</code></li>
        <li>Level admin dari pengaturan CMS + akses khusus di atas</li>
        <li>Semua form admin dilindungi CSRF token</li>
    </ul>
</div>

<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
