<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Ilustrasi Layanan Bisnis</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/service_save'); ?>" method="post" enctype="multipart/form-data" id="svcForm">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" id="svcId" value="0">
                    <div class="form-group"><label>Judul Layanan *</label><input type="text" name="title" id="svcTitle" class="form-control" required placeholder="Alat Tulis Kantor"></div>
                    <div class="form-group"><label>Slug</label><input type="text" name="slug" id="svcSlug" class="form-control"></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="description" id="svcDesc" class="form-control" rows="3"></textarea></div>
                    <div class="form-group"><label>Icon Bootstrap</label><input type="text" name="icon" id="svcIcon" class="form-control" placeholder="bi bi-printer"></div>
                    <div class="form-group"><label>URL Gambar (Unsplash/upload)</label><input type="url" name="image_url" id="svcImg" class="form-control" placeholder="https://images.unsplash.com/..."></div>
                    <div class="form-group"><label>Atau Upload Gambar</label><input type="file" name="image_file" class="form-control-file" accept="image/*"></div>
                    <div class="form-group"><label>Link (opsional)</label><input type="url" name="link_url" id="svcLink" class="form-control"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" id="svcSort" class="form-control" value="0"></div>
                    <div class="form-group"><label>Publish</label><input type="datetime-local" name="published_at" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>"></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" id="svcActive" class="form-check-input" checked><label for="svcActive">Aktif</label></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_published" value="1" id="svcPub" class="form-check-input" checked><label for="svcPub">Publish</label></div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-header"><strong>Daftar Layanan (ATK, Fotokopi, Percetakan, Jasa)</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead><tr><th>Gambar</th><th>Layanan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($services as $s): ?>
                        <tr>
                            <td><?php if ($s->image_url): ?><img src="<?php echo htmlspecialchars(cms_featured_image_url($s->image_url), ENT_QUOTES, 'UTF-8'); ?>" height="40"><?php endif; ?></td>
                            <td><strong><?php echo htmlspecialchars($s->title, ENT_QUOTES, 'UTF-8'); ?></strong><br><small><?php echo htmlspecialchars(cms_excerpt($s->description, 60), ENT_QUOTES, 'UTF-8'); ?></small></td>
                            <td><?php echo $s->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Sembunyi</span>'; ?> <?php echo $s->is_published ? '<span class="badge badge-primary">Publish</span>' : '<span class="badge badge-warning">Draft</span>'; ?></td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-xs btn-info" onclick='editSvc(<?php echo json_encode($s); ?>)'>Edit</button>
                                <form action="<?php echo site_url('cms-admin/service_toggle_active/' . $s->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-secondary"><?php echo $s->is_active ? 'Sembunyi' : 'Tampil'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/service_toggle_publish/' . $s->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-primary"><?php echo $s->is_published ? 'Unpub' : 'Pub'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/service_delete/' . $s->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">X</button></form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
function editSvc(s){
    document.getElementById('svcId').value=s.id;
    document.getElementById('svcTitle').value=s.title||'';
    document.getElementById('svcSlug').value=s.slug||'';
    document.getElementById('svcDesc').value=s.description||'';
    document.getElementById('svcIcon').value=s.icon||'';
    document.getElementById('svcImg').value=s.image_url||'';
    document.getElementById('svcLink').value=s.link_url||'';
    document.getElementById('svcSort').value=s.sort_order||0;
    document.getElementById('svcActive').checked=!!(+s.is_active);
    document.getElementById('svcPub').checked=!!(+s.is_published);
}
</script>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
