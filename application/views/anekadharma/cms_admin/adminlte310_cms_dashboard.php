<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<?php if (!$this->db->table_exists('cms_services') || !$this->db->table_exists('cms_media_embeds')): ?>
<div class="alert alert-warning">
    <strong>Upgrade tersedia!</strong> Fitur layanan bisnis & video sosial belum aktif.
    <form action="<?php echo site_url('cms-admin/upgrade_media'); ?>" method="post" class="d-inline ml-2"><?php echo cms_csrf_field(); ?>
        <button type="submit" class="btn btn-sm btn-warning">Jalankan Upgrade Media</button>
    </form>
</div>
<?php else: ?>
<div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between">
    <span><strong>Presentasi Direktur:</strong> Muat ulang data demo (galeri 18+ foto, berita harga ATK, video percetakan).</span>
    <form action="<?php echo site_url('cms-admin/demo_seed'); ?>" method="post" class="mb-0"><?php echo cms_csrf_field(); ?>
        <button type="submit" class="btn btn-sm btn-info">Muat Data Demo Presentasi</button>
    </form>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-info"><i class="fa fa-file-text-o"></i></span><div class="info-box-content"><span class="info-box-text">Total Konten</span><span class="info-box-number"><?php echo (int) $total_posts; ?></span></div></div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-success"><i class="fa fa-check"></i></span><div class="info-box-content"><span class="info-box-text">Published</span><span class="info-box-number"><?php echo (int) $published_posts; ?></span></div></div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-warning"><i class="fa fa-picture-o"></i></span><div class="info-box-content"><span class="info-box-text">Slider</span><span class="info-box-number"><?php echo (int) $total_sliders; ?></span></div></div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="info-box"><span class="info-box-icon bg-danger"><i class="fa fa-camera"></i></span><div class="info-box-content"><span class="info-box-text">Galeri</span><span class="info-box-number"><?php echo (int) $total_gallery; ?></span></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Konten Terbaru</strong>
        <a href="<?php echo site_url('cms-admin/post_create'); ?>" class="btn btn-sm btn-primary">+ Tambah Konten</a>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead><tr><th>Judul</th><th>Tipe</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($recent_posts)): ?>
                <tr><td colspan="5" class="text-center text-muted">Belum ada konten.</td></tr>
            <?php else: foreach ($recent_posts as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo cms_post_type_label($p->post_type); ?></td>
                    <td><?php echo $p->is_published ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-secondary">Draft</span>'; ?></td>
                    <td><?php echo cms_format_date($p->published_at); ?></td>
                    <td><a href="<?php echo site_url('cms-admin/post_edit/' . $p->id); ?>" class="btn btn-xs btn-info">Edit</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
