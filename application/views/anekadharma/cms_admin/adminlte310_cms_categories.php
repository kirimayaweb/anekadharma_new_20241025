<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-primary">
            <div class="card-header"><strong>Tambah / Edit Kategori</strong></div>
            <div class="card-body">
                <form action="<?php echo site_url('cms-admin/category_save'); ?>" method="post" id="catForm">
                    <?php echo cms_csrf_field(); ?>
                    <input type="hidden" name="id" id="catId" value="0">
                    <div class="form-group"><label>Nama</label><input type="text" name="name" id="catName" class="form-control" required></div>
                    <div class="form-group"><label>Slug</label><input type="text" name="slug" id="catSlug" class="form-control"></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="description" id="catDesc" class="form-control" rows="2"></textarea></div>
                    <div class="form-group"><label>Icon (FontAwesome class)</label><input type="text" name="icon" id="catIcon" class="form-control" placeholder="fa fa-newspaper-o"></div>
                    <div class="form-group"><label>Urutan</label><input type="number" name="sort_order" id="catSort" class="form-control" value="0"></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" id="catActive" class="form-check-input" checked><label for="catActive" class="form-check-label">Aktif</label></div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-default" onclick="resetCatForm()">Reset</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><strong>Daftar Kategori</strong></div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead><tr><th>Nama</th><th>Slug</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c->name, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($c->slug, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo $c->is_active ? 'Aktif' : 'Nonaktif'; ?></td>
                            <td>
                                <button type="button" class="btn btn-xs btn-info" onclick='editCat(<?php echo json_encode($c); ?>)'>Edit</button>
                                <form action="<?php echo site_url('cms-admin/category_delete/' . $c->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus?');"><?php echo cms_csrf_field(); ?><button class="btn btn-xs btn-danger">Hapus</button></form>
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
function editCat(c){
    document.getElementById('catId').value=c.id;
    document.getElementById('catName').value=c.name;
    document.getElementById('catSlug').value=c.slug;
    document.getElementById('catDesc').value=c.description||'';
    document.getElementById('catIcon').value=c.icon||'';
    document.getElementById('catSort').value=c.sort_order||0;
    document.getElementById('catActive').checked=!!(+c.is_active);
}
function resetCatForm(){
    document.getElementById('catForm').reset();
    document.getElementById('catId').value=0;
    document.getElementById('catActive').checked=true;
}
</script>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
