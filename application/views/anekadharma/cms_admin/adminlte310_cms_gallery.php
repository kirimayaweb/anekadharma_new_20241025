<?php
$this->load->config('cms', true);
$biz_cats = $this->config->item('cms_business_categories', 'cms');
?>
<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Tambah / Edit Galeri</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/gallery_save'); ?>" method="post" enctype="multipart/form-data" id="galleryForm">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" id="galId" value="0">
                    <div class="form-group"><label>Judul</label><input type="text" name="title" id="galTitle" class="form-control"></div>
                    <div class="form-group"><label>Judul Share (sosial media)</label><input type="text" name="share_title" id="galShare" class="form-control" placeholder="Judul saat di-share"></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="description" id="galDesc" class="form-control" rows="2"></textarea></div>
                    <div class="form-group"><label>Kategori Bisnis</label>
                        <select name="category" id="galCat" class="form-control">
                            <?php foreach ($biz_cats as $k => $label): ?>
                                <option value="<?php echo $k; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Tipe Media</label>
                        <select name="media_type" id="galType" class="form-control">
                            <option value="image">Gambar</option>
                            <option value="video">Video Upload</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Upload File</label><input type="file" name="file_path" class="form-control-file"></div>
                    <div class="form-group"><label>URL Gambar / Video (eksternal)</label><input type="url" name="external_url" id="galUrl" class="form-control" placeholder="https://..."></div>
                    <div class="form-group"><label>URL Thumbnail (opsional)</label><input type="url" name="thumbnail_url" id="galThumb" class="form-control"></div>
                    <div class="form-group"><label>Tanggal Publish</label><input type="datetime-local" name="published_at" id="galPub" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" id="galSort" class="form-control" value="0"></div>
                    <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" id="galActive" class="form-check-input" checked><label for="galActive" class="form-check-label">Aktif (tampil)</label></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_published" value="1" id="galPublished" class="form-check-input" checked><label for="galPublished" class="form-check-label">Publish</label></div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="document.getElementById('galleryForm').reset();document.getElementById('galId').value=0;">Reset</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-header"><strong>Daftar Galeri</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-sm">
                    <thead><tr><th>Preview</th><th>Judul</th><th>Kat.</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($gallery as $g): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars(cms_gallery_image_url($g), ENT_QUOTES, 'UTF-8'); ?>" height="36" style="object-fit:cover;border-radius:4px;"></td>
                            <td><?php echo htmlspecialchars($g->title, ENT_QUOTES, 'UTF-8'); ?><br><small class="text-muted"><?php echo htmlspecialchars($g->media_type, ENT_QUOTES, 'UTF-8'); ?></small></td>
                            <td><?php echo htmlspecialchars(isset($g->category) ? $g->category : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php echo $g->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Sembunyi</span>'; ?>
                                <?php echo (isset($g->is_published) && $g->is_published) ? ' <span class="badge badge-primary">Publish</span>' : ' <span class="badge badge-warning">Draft</span>'; ?>
                            </td>
                            <td class="text-nowrap">
                                <form action="<?php echo site_url('cms-admin/gallery_toggle_active/' . $g->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-secondary" title="Toggle aktif"><?php echo $g->is_active ? 'Sembunyikan' : 'Tampilkan'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/gallery_toggle_publish/' . $g->id); ?>" method="post" class="d-inline"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-outline-primary"><?php echo (isset($g->is_published) && $g->is_published) ? 'Unpublish' : 'Publish'; ?></button></form>
                                <form action="<?php echo site_url('cms-admin/gallery_delete/' . $g->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Hapus</button></form>
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
