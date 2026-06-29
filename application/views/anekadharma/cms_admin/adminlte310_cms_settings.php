<?php
$s = isset($settings) ? $settings : array();
function cms_setting_val($s, $key, $default = '') { return isset($s[$key]) ? $s[$key] : $default; }
?>
<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="card card-primary">
    <div class="card-header"><strong>Pengaturan Halaman Publik</strong></div>
    <div class="card-body">
        <form action="<?php echo site_url('cms-admin/settings_save'); ?>" method="post">
            <?php echo cms_csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Judul Situs</label><input type="text" name="site_title" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'site_title'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Tagline</label><input type="text" name="site_tagline" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'site_tagline'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Nama Perusahaan</label><input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'company_name'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Tentang Perusahaan</label><textarea name="company_about" class="form-control" rows="4"><?php echo htmlspecialchars(cms_setting_val($s,'company_about'), ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Email Kontak</label><input type="email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'contact_email'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Telepon</label><input type="text" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'contact_phone'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Alamat</label><textarea name="contact_address" class="form-control" rows="2"><?php echo htmlspecialchars(cms_setting_val($s,'contact_address'), ENT_QUOTES, 'UTF-8'); ?></textarea></div>
                    <div class="form-group"><label>Warna Primary</label><input type="color" name="primary_color" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'primary_color','#2563eb'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Warna Secondary</label><input type="color" name="secondary_color" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'secondary_color','#7c3aed'), ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="form-group"><label>Level Admin CMS (ID, pisah koma)</label><input type="text" name="allowed_admin_levels" class="form-control" value="<?php echo htmlspecialchars(cms_setting_val($s,'allowed_admin_levels','1,2,99'), ENT_QUOTES, 'UTF-8'); ?>"><small class="text-muted">Contoh: 1,2,99 — selain hak akses menu tbl_menu</small></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>
<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
