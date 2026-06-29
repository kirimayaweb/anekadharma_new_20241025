<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Video & Embed Media Sosial</strong></div>
            <div class="card-body">
                <p class="small text-muted">Tempel link YouTube, TikTok, Instagram, Facebook, atau kode embed. Atur jadwal publish & sembunyikan sementara kapan saja.</p>
                <form action="<?php echo site_url('cms-admin/media_embed_save'); ?>" method="post" id="embedForm">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" id="embId" value="0">
                    <div class="form-group"><label>Judul *</label><input type="text" name="title" id="embTitle" class="form-control" required></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="description" id="embDesc" class="form-control" rows="2"></textarea></div>
                    <div class="form-group"><label>Platform</label>
                        <select name="platform" id="embPlatform" class="form-control">
                            <?php foreach ($platforms as $k => $p): ?>
                                <option value="<?php echo $k; ?>"><?php echo $p['label']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>URL Sumber *</label><input type="url" name="source_url" id="embUrl" class="form-control" required placeholder="https://youtube.com/watch?v=..."></div>
                    <div class="form-group"><label>Kode Embed (opsional, override URL)</label><textarea name="embed_code" id="embCode" class="form-control" rows="3" placeholder="<iframe ...>"></textarea></div>
                    <div class="form-group"><label>Thumbnail URL</label><input type="url" name="thumbnail_url" id="embThumb" class="form-control" placeholder="https://img.youtube.com/vi/ID/hqdefault.jpg"></div>
                    <div class="form-group"><label>Kategori</label>
                        <select name="category" id="embCat" class="form-control">
                            <?php foreach ($categories as $k => $label): ?>
                                <option value="<?php echo $k; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Publish</label><input type="datetime-local" name="published_at" id="embPub" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>"></div>
                    <div class="form-group"><label>Kadaluarsa (opsional — sembunyikan otomatis)</label><input type="datetime-local" name="expire_at" id="embExp" class="form-control"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" id="embSort" class="form-control" value="0"></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" id="embActive" class="form-check-input" checked><label for="embActive">Aktif</label></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_published" value="1" id="embPublished" class="form-check-input" checked><label for="embPublished">Publish</label></div>
                    <div class="form-check mb-3"><input type="checkbox" name="share_enabled" value="1" id="embShare" class="form-check-input" checked><label for="embShare">Tombol Share</label></div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="resetEmb()">Reset</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-header"><strong>Daftar Video & Embed</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <thead><tr><th>Judul</th><th>Platform</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($media_embeds as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m->title, ENT_QUOTES, 'UTF-8'); ?><br><small class="text-muted"><?php echo htmlspecialchars($m->category, ENT_QUOTES, 'UTF-8'); ?></small></td>
                            <td><span class="badge badge-info"><?php echo htmlspecialchars($m->platform, ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td>
                                <?php echo $m->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Sembunyi</span>'; ?>
                                <?php echo $m->is_published ? ' <span class="badge badge-primary">Publish</span>' : ' <span class="badge badge-warning">Draft</span>'; ?>
                            </td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-xs btn-info" onclick='editEmb(<?php echo json_encode($m); ?>)'>Edit</button>
                                <form action="<?php echo site_url('cms-admin/media_embed_toggle_active/' . $m->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-secondary"><?php echo $m->is_active ? 'Sembunyi' : 'Tampil'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/media_embed_toggle_publish/' . $m->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-primary"><?php echo $m->is_published ? 'Unpub' : 'Pub'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/media_embed_delete/' . $m->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">X</button></form>
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
function editEmb(m){
    document.getElementById('embId').value=m.id;
    document.getElementById('embTitle').value=m.title||'';
    document.getElementById('embDesc').value=m.description||'';
    document.getElementById('embPlatform').value=m.platform||'youtube';
    document.getElementById('embUrl').value=m.source_url||'';
    document.getElementById('embCode').value=m.embed_code||'';
    document.getElementById('embThumb').value=m.thumbnail_url||'';
    document.getElementById('embCat').value=m.category||'umum';
    document.getElementById('embSort').value=m.sort_order||0;
    document.getElementById('embActive').checked=!!(+m.is_active);
    document.getElementById('embPublished').checked=!!(+m.is_published);
    document.getElementById('embShare').checked=!!(+m.share_enabled);
    if(m.published_at&&m.published_at!=='0000-00-00 00:00:00'){ document.getElementById('embPub').value=m.published_at.replace(' ','T').substring(0,16); }
    if(m.expire_at&&m.expire_at!=='0000-00-00 00:00:00'){ document.getElementById('embExp').value=m.expire_at.replace(' ','T').substring(0,16); }
}
function resetEmb(){ document.getElementById('embedForm').reset(); document.getElementById('embId').value=0; document.getElementById('embActive').checked=true; document.getElementById('embPublished').checked=true; document.getElementById('embShare').checked=true; }
</script>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
