<?php
$this->load->helper('neraca_saldo_list');

if (!isset($grand_totals) || !is_array($grand_totals)) {
    if (!isset($Get_month_from_date) || $Get_month_from_date === '' || $Get_month_from_date === null) {
        $Get_month_from_date = isset($month_selected) ? (int) $month_selected : (int) date('m');
    }
    if (!isset($Get_year_Tahun_ini) || $Get_year_Tahun_ini === '' || $Get_year_Tahun_ini === null) {
        $Get_year_Tahun_ini = isset($year_selected) ? (int) $year_selected : (int) date('Y');
    }
    $ns_data = neraca_saldo_compute_list_data(get_instance(), (int) $Get_month_from_date, (int) $Get_year_Tahun_ini);
    $grand_totals = isset($ns_data['grand_totals']) ? $ns_data['grand_totals'] : array();
}

echo neraca_saldo_render_tfoot_html($grand_totals);
