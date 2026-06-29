<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="card card-primary">
    <div class="card-header"><strong>Instalasi CMS Publikasi</strong></div>
    <div class="card-body">
        <p>Database CMS belum terinstal. Klik tombol di bawah untuk membuat tabel dan data awal secara otomatis.</p>
        <p class="text-muted small">File SQL: <code>database/sql/cms_publication.sql</code></p>
        <form action="<?php echo site_url('cms-admin/install_action'); ?>" method="post">
            <?php echo cms_csrf_field(); ?>
            <button type="submit" class="btn btn-primary" onclick="return confirm('Jalankan instalasi CMS?');">
                <i class="fa fa-database"></i> Instal CMS Sekarang
            </button>
        </form>
    </div>
</div>

<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
