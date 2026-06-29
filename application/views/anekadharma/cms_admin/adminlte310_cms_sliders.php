<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Slider Hero (Halaman Publik)</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/slider_save'); ?>" method="post" enctype="multipart/form-data" id="sliderForm">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" id="sliderId" value="0">
                    <div class="form-group"><label>Judul</label><input type="text" name="title" id="sliderTitle" class="form-control"></div>
                    <div class="form-group"><label>Subjudul</label><textarea name="subtitle" id="sliderSubtitle" class="form-control" rows="2"></textarea></div>
                    <div class="form-group"><label>Gambar Background</label><input type="file" name="image" class="form-control-file" accept="image/*"></div>
                    <div class="form-group"><label>Link URL</label><input type="url" name="link_url" id="sliderLink" class="form-control"></div>
                    <div class="form-group"><label>Teks Tombol</label><input type="text" name="button_text" id="sliderBtn" class="form-control" placeholder="Selengkapnya"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" id="sliderSort" class="form-control" value="0"></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" id="sliderActive" class="form-check-input" checked><label for="sliderActive" class="form-check-label">Aktif</label></div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="resetSliderForm()">Reset</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card"><div class="card-header"><strong>Daftar Slider</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead><tr><th>Gambar</th><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($sliders as $s): ?>
                        <tr>
                            <td><?php if ($s->image): ?><img src="<?php echo cms_featured_image_url($s->image); ?>" height="40"><?php endif; ?></td>
                            <td><?php echo htmlspecialchars($s->title, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo $s->is_active ? 'Aktif' : 'Nonaktif'; ?></td>
                            <td>
                                <button type="button" class="btn btn-xs btn-info" onclick='editSlider(<?php echo json_encode($s); ?>)'>Edit</button>
                                <form action="<?php echo site_url('cms-admin/slider_delete/' . $s->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Hapus</button></form>
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
function editSlider(s){
    document.getElementById('sliderId').value=s.id;
    document.getElementById('sliderTitle').value=s.title||'';
    document.getElementById('sliderSubtitle').value=s.subtitle||'';
    document.getElementById('sliderLink').value=s.link_url||'';
    document.getElementById('sliderBtn').value=s.button_text||'';
    document.getElementById('sliderSort').value=s.sort_order||0;
    document.getElementById('sliderActive').checked=!!(+s.is_active);
}
function resetSliderForm(){ document.getElementById('sliderForm').reset(); document.getElementById('sliderId').value=0; document.getElementById('sliderActive').checked=true; }
</script>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
