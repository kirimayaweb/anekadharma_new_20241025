<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$table_id = isset($table_id) ? $table_id : 'dashboard_laporan_table';
$card_title = isset($card_title) ? $card_title : 'Laporan';
$card_icon = isset($card_icon) ? $card_icon : 'fa-table';
$theme_class = isset($theme_class) ? $theme_class : 'dashboard-dt-theme-blue';
$rows = isset($rows) ? $rows : array();
$can_edit = !empty($can_edit);
$can_publish = !empty($can_publish);
$report_type = isset($report_type) ? $report_type : '';
$publish_ajax_url = isset($publish_ajax_url) ? $publish_ajax_url : '';
$update_label = isset($update_label) ? $update_label : 'Update';
$cetak_label = isset($cetak_label) ? $cetak_label : 'Cetak';
$col_class = isset($col_class) ? $col_class : 'col-lg-6 col-xs-12';
$yellow_border = !empty($yellow_border);
$supports_publish = $can_publish && $report_type !== '' && $publish_ajax_url !== '';
$card_extra_class = $yellow_border ? ' dashboard-dt-yellow-border' : '';
?>
<div class="<?php echo htmlspecialchars($col_class, ENT_QUOTES, 'UTF-8'); ?>">
    <div class="card dashboard-dt-card shadow-sm <?php echo htmlspecialchars($theme_class, ENT_QUOTES, 'UTF-8'); ?>-card<?php echo $card_extra_class; ?>"<?php if ($supports_publish) { ?> data-publish-report-type="<?php echo htmlspecialchars($report_type, ENT_QUOTES, 'UTF-8'); ?>" data-publish-ajax-url="<?php echo htmlspecialchars($publish_ajax_url, ENT_QUOTES, 'UTF-8'); ?>"<?php } ?>>
        <div class="card-header dashboard-dt-card-header">
            <h3 class="card-title mb-0">
                <span class="dashboard-dt-title-icon"><i class="fas <?php echo htmlspecialchars($card_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                <?php echo htmlspecialchars($card_title, ENT_QUOTES, 'UTF-8'); ?>
            </h3>
        </div>
        <div class="card-body dashboard-dt-card-body">
            <div class="dashboard-dt-wrap">
                <table id="<?php echo htmlspecialchars($table_id, ENT_QUOTES, 'UTF-8'); ?>" class="table table-hover dashboard-dt-table mb-0 dashboard-laporan-dt" style="width:100%"<?php if ($supports_publish) { ?> data-publish-report-type="<?php echo htmlspecialchars($report_type, ENT_QUOTES, 'UTF-8'); ?>" data-publish-ajax-url="<?php echo htmlspecialchars($publish_ajax_url, ENT_QUOTES, 'UTF-8'); ?>" data-cetak-label="<?php echo htmlspecialchars($cetak_label, ENT_QUOTES, 'UTF-8'); ?>" data-has-publish-actions="1"<?php } ?>>
                    <thead>
                        <tr>
                            <th class="text-center dt-col-no">No</th>
                            <th class="text-center dt-col-tahun">Tahun</th>
                            <th class="dt-col-bulan">Bulan</th>
                            <th class="text-center dt-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $list_data) {
                            if (!dashboard_bulan_in_valid_range($list_data->year_process, $list_data->month_process)) {
                                continue;
                            }
                            $bulan_order = sprintf('%04d%02d', (int) $list_data->year_process, (int) $list_data->month_process);
                            $has_data = isset($list_data->has_data) ? (bool) $list_data->has_data : false;
                            $is_current = !empty($list_data->is_current_month);
                            $show_update = isset($list_data->show_update) ? (bool) $list_data->show_update : $has_data;
                            $show_publish = isset($list_data->show_publish) ? (bool) $list_data->show_publish : false;
                            $show_cancel_publish = isset($list_data->show_cancel_publish) ? (bool) $list_data->show_cancel_publish : false;
                            $cetak_enabled = isset($list_data->cetak_enabled) ? (bool) $list_data->cetak_enabled : false;
                            $show_cetak = isset($list_data->show_cetak) ? (bool) $list_data->show_cetak : $cetak_enabled;
                            $update_url = isset($list_data->update_url) ? $list_data->update_url : '#';
                            $cetak_url = isset($list_data->cetak_url) ? $list_data->cetak_url : '#';
                            $view_url = isset($list_data->view_url) ? $list_data->view_url : '';
                            $has_any_action = false;
                            if ($supports_publish && $can_edit) {
                                $has_any_action = $show_update || $show_publish || $show_cancel_publish || $cetak_enabled;
                            } else {
                                $has_any_action = ($can_edit && $show_update && $update_url !== '#')
                                    || ($cetak_enabled && $view_url !== '')
                                    || ($cetak_enabled && $cetak_url !== '#');
                            }
                        ?>
                            <tr class="<?php echo $is_current ? 'dashboard-dt-row-current' : ''; ?>" data-year="<?php echo (int) $list_data->year_process; ?>" data-month="<?php echo (int) $list_data->month_process; ?>" data-has-data="<?php echo $has_data ? '1' : '0'; ?>" data-published="<?php echo !empty($list_data->is_published) ? '1' : '0'; ?>">
                                <td class="text-center dt-cell-no"></td>
                                <td class="text-center">
                                    <span class="dashboard-dt-year-badge"><?php echo (int) $list_data->year_process; ?></span>
                                </td>
                                <td data-order="<?php echo $bulan_order; ?>">
                                    <div class="dashboard-dt-bulan-cell">
                                        <span class="dashboard-dt-bulan-nama"><?php echo bulan_teks($list_data->month_process); ?></span>
                                        <span class="dashboard-dt-bulan-num"><?php echo str_pad((int) $list_data->month_process, 2, '0', STR_PAD_LEFT); ?></span>
                                        <?php if ($is_current) { ?>
                                            <span class="badge badge-pill dashboard-dt-badge-current">Bulan ini</span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="text-center dashboard-dt-actions dashboard-dt-actions-triple">
                                    <?php
                                    if ($supports_publish && $can_edit) {
                                        if ($show_update && $update_url !== '#') {
                                            echo anchor(
                                                $update_url,
                                                '<i class="fa fa-pencil-square-o"></i> ' . htmlspecialchars($update_label, ENT_QUOTES, 'UTF-8'),
                                                'class="btn btn-dt-update btn-sm dashboard-dt-btn-update"'
                                            );
                                        }

                                        $publish_style = $show_publish ? '' : ' style="display:none;"';
                                        $cancel_style = $show_cancel_publish ? '' : ' style="display:none;"';
                                        $publish_disabled = $has_data ? '' : ' disabled';
                                        echo '<button type="button" class="btn btn-dt-publish btn-sm dashboard-dt-btn-publish"' . $publish_style . $publish_disabled . '><i class="fa fa-upload"></i> Publish</button>';
                                        echo '<button type="button" class="btn btn-dt-cancel-publish btn-sm dashboard-dt-btn-cancel-publish"' . $cancel_style . '><i class="fa fa-ban"></i> Cancel Publish</button>';

                                        if ($cetak_enabled) {
                                            if ($report_type === 'laba_rugi') {
                                                echo dashboard_laba_rugi_cetak_buttons_html($list_data->year_process, $list_data->month_process);
                                            } elseif ($cetak_url !== '#') {
                                                echo anchor(
                                                    $cetak_url,
                                                    '<i class="fa fa-print"></i> ' . htmlspecialchars($cetak_label, ENT_QUOTES, 'UTF-8'),
                                                    'class="btn btn-dt-cetak btn-sm dashboard-dt-btn-cetak" target="_blank"'
                                                );
                                            }
                                        }
                                    } else {
                                        if ($can_edit && $show_update && $update_url !== '#') {
                                            echo anchor(
                                                $update_url,
                                                '<i class="fa fa-pencil-square-o"></i> ' . htmlspecialchars($update_label, ENT_QUOTES, 'UTF-8'),
                                                'class="btn btn-dt-update btn-sm dashboard-dt-btn-update"'
                                            );
                                        }
                                        if ($cetak_enabled && $view_url !== '') {
                                            echo anchor(
                                                $view_url,
                                                '<i class="fa fa-eye"></i> View',
                                                'class="btn btn-dt-view btn-sm dashboard-dt-btn-view" target="_blank"'
                                            );
                                        }
                                        if ($cetak_enabled && $cetak_url !== '#') {
                                            echo anchor(
                                                $cetak_url,
                                                '<i class="fa fa-print"></i> ' . htmlspecialchars($cetak_label, ENT_QUOTES, 'UTF-8'),
                                                'class="btn btn-dt-cetak btn-sm dashboard-dt-btn-cetak" target="_blank"'
                                            );
                                        }
                                    }

                                    if (!$has_any_action) {
                                        echo '<span class="dashboard-dt-no-action">—</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
