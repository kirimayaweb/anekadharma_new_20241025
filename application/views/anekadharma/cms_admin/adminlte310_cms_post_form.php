<?php
$row = isset($row) ? $row : null;
$is_edit = (bool) $row;
?>
<?php $this->load->view('anekadharma/cms_admin/_layout_start'); ?>

<div class="card card-primary">
    <div class="card-header"><strong><?php echo $is_edit ? 'Edit Konten' : 'Tambah Konten'; ?></strong></div>
    <div class="card-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <?php echo cms_csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo $is_edit ? (int) $row->id : 0; ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Judul *</label>
                        <input type="text" name="title" class="form-control" required value="<?php echo $is_edit ? htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') : set_value('title'); ?>">
                        <?php echo form_error('title'); ?>
                    </div>
                    <div class="form-group">
                        <label>Slug URL</label>
                        <input type="text" name="slug" class="form-control" placeholder="auto dari judul jika kosong" value="<?php echo $is_edit ? htmlspecialchars($row->slug, ENT_QUOTES, 'UTF-8') : set_value('slug'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Ringkasan</label>
                        <textarea name="excerpt" class="form-control" rows="3"><?php echo $is_edit ? htmlspecialchars($row->excerpt, ENT_QUOTES, 'UTF-8') : set_value('excerpt'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Konten</label>
                        <textarea name="content" id="cmsContentEditor" class="form-control" rows="12"><?php echo $is_edit ? $row->content : set_value('content'); ?></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipe Konten</label>
                        <select name="post_type" class="form-control">
                            <?php foreach ($post_types as $k => $label): ?>
                                <option value="<?php echo $k; ?>" <?php echo ($is_edit && $row->post_type === $k) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control">
                            <option value="">- Pilih -</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int) $cat->id; ?>" <?php echo ($is_edit && (int)$row->category_id === (int)$cat->id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gambar Utama</label>
                        <input type="file" name="featured_image" class="form-control-file" accept="image/*">
                        <?php if ($is_edit && $row->featured_image): ?>
                            <img src="<?php echo cms_featured_image_url($row->featured_image); ?>" class="img-thumbnail mt-2" style="max-height:120px;">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Tipe Video</label>
                        <select name="video_type" class="form-control">
                            <?php foreach ($video_types as $k => $label): ?>
                                <option value="<?php echo $k; ?>" <?php echo ($is_edit && $row->video_type === $k) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>URL Video (YouTube/TikTok/Instagram/Embed)</label>
                        <input type="url" name="video_url" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($row->video_url, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tags (pisah koma)</label>
                        <input type="text" name="tags" class="form-control" value="<?php echo $is_edit ? htmlspecialchars($row->tags, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Publish</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="<?php echo $is_edit && $row->published_at ? date('Y-m-d\TH:i', strtotime($row->published_at)) : date('Y-m-d\TH:i'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo $is_edit ? (int) $row->sort_order : 0; ?>">
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="isFeatured" <?php echo ($is_edit && $row->is_featured) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="isFeatured">Sorotan (Featured)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" id="isPublished" <?php echo (!$is_edit || $row->is_published) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="isPublished">Publish</label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                    <a href="<?php echo site_url('cms-admin/posts'); ?>" class="btn btn-default btn-block">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<link href="<?php echo base_url('assets/AdminLTE310/plugins/summernote/summernote-bs4.min.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/AdminLTE310/plugins/summernote/summernote-bs4.min.js'); ?>"></script>
<script>
$(function(){
    $('#cmsContentEditor').summernote({ height: 320, toolbar: [
        ['style',['style']],['font',['bold','italic','underline','clear']],
        ['para',['ul','ol','paragraph']],['insert',['link','picture','video']],
        ['view',['fullscreen','codeview']]
    ]});
});
</script>

<?php $this->load->view('anekadharma/cms_admin/_layout_end'); ?>
