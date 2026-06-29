<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Link Media Sosial</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/social_save'); ?>" method="post">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" value="0">
                    <div class="form-group"><label>Platform</label>
                        <select name="platform" class="form-control">
                            <?php foreach ($platforms as $key => $p): ?>
                                <option value="<?php echo $key; ?>"><?php echo $p['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>URL *</label><input type="url" name="url" class="form-control" required placeholder="https://instagram.com/..."></div>
                    <div class="form-group"><label>Label</label><input type="text" name="label" class="form-control"></div>
                    <div class="form-group"><label>Icon Class</label><input type="text" name="icon_class" class="form-control" placeholder="bi bi-instagram / fab fa-tiktok"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" class="form-control" value="0"></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" checked><label class="form-check-label">Aktif</label></div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-header"><strong>Daftar Link Sosial</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead><tr><th>Platform</th><th>URL</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($social_links as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s->platform, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><a href="<?php echo htmlspecialchars($s->url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($s->url, ENT_QUOTES, 'UTF-8'); ?></a></td>
                            <td><?php echo $s->is_active ? 'Aktif' : 'Nonaktif'; ?></td>
                            <td>
                                <form action="<?php echo site_url('cms-admin/social_delete/' . $s->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Hapus</button></form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
