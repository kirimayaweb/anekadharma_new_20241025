<?php
function cmb_dinamis($name, $table, $field, $pk, $selected = null, $order = null)
{
    $ci = get_instance();
    $cmb = "<select name='$name' class='form-control'>";
    if ($order) {
        $ci->db->order_by($field, $order);
    }
    $data = $ci->db->get($table)->result();
    foreach ($data as $d) {
        $cmb .= "<option value='" . $d->$pk . "'";
        $cmb .= $selected == $d->$pk ? " selected='selected'" : '';
        $cmb .= ">" .  strtoupper($d->$field) . "</option>";
    }
    $cmb .= "</select>";
    return $cmb;
}

function select2_dinamis($name, $table, $field, $placeholder)
{
    $ci = get_instance();
    $select2 = '<select name="' . $name . '" class="form-control select2 select2-hidden-accessible" multiple="" 
               data-placeholder="' . $placeholder . '" style="width: 100%;" tabindex="-1" aria-hidden="true">';
    $data = $ci->db->get($table)->result();
    foreach ($data as $row) {
        $select2 .= ' <option>' . $row->$field . '</option>';
    }
    $select2 .= '</select>';
    return $select2;
}

function datalist_dinamis($name, $table, $field, $value = null)
{
    $ci = get_instance();
    $string = '<input value="' . $value . '" name="' . $name . '" list="' . $name . '" class="form-control">
    <datalist id="' . $name . '">';
    $data = $ci->db->get($table)->result();
    foreach ($data as $row) {
        $string .= '<option value="' . $row->$field . '">';
    }
    $string .= '</datalist>';
    return $string;
}

function rename_string_is_aktif($string)
{
    return $string == 'y' ? 'Aktif' : 'Tidak Aktif';
}


function is_logged_in()
{
    $ci = get_instance();
    $user_id = $ci->session->userdata('sess_iduser');

    if ($user_id === null || $user_id === '') {
        $user_id = $ci->session->userdata('id_users');
    }

    return !($user_id === null || $user_id === '' || $user_id === false);
}

function is_login()
{
    $ci = get_instance();

    if (!is_logged_in()) {
        $ci->session->set_flashdata('status_login', 'Silahkan login untuk masuk ke aplikasi.');
        redirect('Anekadharmamasuk');
        exit;
    }

    if (function_exists('login_mfa_has_pending') === false) {
        $ci->load->helper('login_security');
    }

    if (function_exists('login_mfa_has_pending') && login_mfa_has_pending()) {
        redirect('Anekadharmamasuk/verifymfa');
        exit;
    }

    $id_user_level = $ci->session->userdata('sess_id_user_level');
    if ($id_user_level === null || $id_user_level === '') {
        $id_user_level = $ci->session->userdata('id_user_level');
    }

    if ($id_user_level === null || $id_user_level === '' || $id_user_level === false) {
        $ci->session->sess_destroy();
        redirect('Anekadharmamasuk');
        exit;
    }

    if (function_exists('login_is_admin_level') === false) {
        $ci->load->helper('login_security');
    }

    if (login_is_admin_level($id_user_level)) {
        return;
    }

    $modul = strtolower((string) $ci->router->fetch_class());
    if ($modul === '') {
        $modul = strtolower((string) $ci->uri->segment(1));
    }

    $menu = $ci->db->query(
        'SELECT * FROM tbl_menu WHERE LOWER(url) = ? LIMIT 1',
        array($modul)
    )->row_array();

    if ($menu) {
        $hak_akses = $ci->db->get_where('tbl_hak_akses', array(
            'id_menu' => $menu['id_menu'],
            'id_user_level' => $id_user_level,
        ));

        if ($hak_akses->num_rows() < 1) {
            redirect('blokir');
            exit;
        }
    }
}

function alert($class, $title, $description)
{
    return '<div class="alert ' . $class . ' alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> ' . $title . '</h4>
                ' . $description . '
              </div>';
}

// untuk chek akses level pada modul peberian akses
function checked_akses($id_user_level, $id_menu)
{
    $ci = get_instance();
    $ci->db->where('id_user_level', $id_user_level);
    $ci->db->where('id_menu', $id_menu);
    $data = $ci->db->get('tbl_hak_akses');
    if ($data->num_rows() > 0) {
        return "checked='checked'";
    }
}


function autocomplate_json($table, $field)
{
    $ci = get_instance();
    $ci->db->like($field, $_GET['term']);
    $ci->db->select($field);
    $collections = $ci->db->get($table)->result();
    foreach ($collections as $collection) {
        $return_arr[] = $collection->$field;
    }
    echo json_encode($return_arr);
}
