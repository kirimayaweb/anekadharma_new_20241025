<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <strong>Daftar Konten</strong>
        <a href="<?php echo site_url('cms-admin/post_create'); ?>" class="btn btn-sm btn-primary">+ Tambah</a>
    </div>
    <div class="card-body table-responsive">
        <table id="cmsPostsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th><th>Judul</th><th>Tipe</th><th>Featured</th><th>Status</th><th>Views</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; foreach ($posts as $p): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo cms_post_type_label($p->post_type); ?></td>
                    <td><?php echo $p->is_featured ? 'Ya' : '-'; ?></td>
                    <td><?php echo $p->is_published ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-secondary">Draft</span>'; ?></td>
                    <td><?php echo (int) $p->view_count; ?></td>
                    <td>
                        <a href="<?php echo site_url('cms-admin/post_edit/' . $p->id); ?>" class="btn btn-xs btn-info">Edit</a>
                        <form action="<?php echo site_url('cms-admin/post_delete/' . $p->id); ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus konten ini?');">
                            <?php echo cms_csrf_field(); ?>
                            <button class="btn btn-xs btn-danger" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(function(){ $('#cmsPostsTable').DataTable({ scrollX:true }); });
</script>

<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
