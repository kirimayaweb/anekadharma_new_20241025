<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function hak_akses_keuangan_config($key, $default = array())
{
    $ci =& get_instance();
    $ci->config->load('hak_akses_keuangan', true);
    $val = $ci->config->item($key, 'hak_akses_keuangan');
    return ($val !== null && $val !== false) ? $val : $default;
}

function hak_akses_keuangan_level_ids()
{
    $ids = hak_akses_keuangan_config('keuangan_level_ids', array(888));
    return array_map('intval', (array) $ids);
}

function hak_akses_is_keuangan_level($id_user_level)
{
    return in_array((int) $id_user_level, hak_akses_keuangan_level_ids(), true);
}

/**
 * Level admin/supervisor yang melihat semua submenu top-nav tanpa filter tbl_hak_akses.
 */
function hak_akses_admin_level_ids()
{
    $ci =& get_instance();
    $ci->config->load('cms', true);
    $levels = $ci->config->item('cms_default_admin_levels', 'cms');
    if ($levels === null || $levels === false || !is_array($levels) || empty($levels)) {
        $levels = array(1, 2, 99);
    }

    return array_map('intval', $levels);
}

function hak_akses_is_admin_level($id_user_level)
{
    return in_array((int) $id_user_level, hak_akses_admin_level_ids(), true);
}

function hak_akses_keuangan_main_menu_ids()
{
    $ids = hak_akses_keuangan_config('keuangan_main_menu_ids', array(500, 600, 700));
    return array_map('intval', (array) $ids);
}

function hak_akses_keuangan_submenu_rows($ci = null)
{
    if ($ci === null) {
        $ci =& get_instance();
    }

    $parents = hak_akses_keuangan_main_menu_ids();
    if (empty($parents)) {
        return array();
    }

    $ci->db->where('is_active', '1');
    $ci->db->where_in('is_parent', $parents);
    $ci->db->order_by('is_parent', 'ASC');
    $ci->db->order_by('id', 'ASC');
    return $ci->db->get('menu')->result();
}

function hak_akses_menu_query_for_user($ci, $main_menu_id, $id_user, $id_user_level)
{
    $ci->db->where('main_menu', (int) $main_menu_id);
    $ci->db->group_start();
    $ci->db->where('id_user', (int) $id_user);
    $ci->db->or_group_start();
    $ci->db->where('id_user', 0);
    $ci->db->where('id_user_level', (int) $id_user_level);
    $ci->db->group_end();
    $ci->db->group_end();
    return $ci->db->get('tbl_hak_akses');
}

/**
 * Submenu top-nav untuk satu parent menu (baris tabel `menu`).
 * Admin: semua submenu aktif. User lain: sesuai tbl_hak_akses.
 */
function hak_akses_topnav_submenu_rows($ci, $main_menu_id, $id_user, $id_user_level)
{
    $main_menu_id = (int) $main_menu_id;

    if (hak_akses_is_admin_level($id_user_level)) {
        $ci->db->where('is_parent', $main_menu_id);
        $ci->db->where('is_active', '1');
        $ci->db->order_by('id', 'ASC');
        return $ci->db->get('menu')->result();
    }

    $menus = array();
    foreach (hak_akses_menu_query_for_user($ci, $main_menu_id, $id_user, $id_user_level)->result() as $grant) {
        $ci->db->where('id', (int) $grant->id_menu);
        $ci->db->where('is_active', '1');
        $detail = $ci->db->get('menu')->row();
        if ($detail) {
            $menus[] = $detail;
        }
    }

    return $menus;
}

function hak_akses_keuangan_has_level_grant($ci, $id_user_level, $id_menu)
{
    $row = $ci->db->get_where('tbl_hak_akses', array(
        'id_user' => 0,
        'id_user_level' => (int) $id_user_level,
        'id_menu' => (int) $id_menu,
    ), 1);

    return ($row && $row->num_rows() > 0);
}

function hak_akses_keuangan_grant_tbl_menu($ci, $id_user_level)
{
    $menu_ids = hak_akses_keuangan_config('keuangan_tbl_menu_ids', array(130));
    $id_user_level = (int) $id_user_level;
    $granted = 0;

    foreach ((array) $menu_ids as $id_menu) {
        $id_menu = (int) $id_menu;
        if ($id_menu < 1) {
            continue;
        }

        $exists = $ci->db->get_where('tbl_hak_akses', array(
            'id_menu' => $id_menu,
            'id_user_level' => $id_user_level,
        ), 1);

        if ($exists->num_rows() < 1) {
            $ci->db->insert('tbl_hak_akses', array(
                'id_user' => 0,
                'id_user_level' => $id_user_level,
                'main_menu' => null,
                'id_menu' => $id_menu,
            ));
            $granted++;
        }
    }

    return $granted;
}

function hak_akses_keuangan_grant_level($ci, $id_user_level)
{
    $id_user_level = (int) $id_user_level;
    $granted = 0;

    foreach (hak_akses_keuangan_submenu_rows($ci) as $sub) {
        if (hak_akses_keuangan_has_level_grant($ci, $id_user_level, $sub->id)) {
            continue;
        }

        $ci->db->insert('tbl_hak_akses', array(
            'id_user' => 0,
            'id_user_level' => $id_user_level,
            'main_menu' => (int) $sub->is_parent,
            'id_menu' => (int) $sub->id,
        ));
        $granted++;
    }

    $granted += hak_akses_keuangan_grant_tbl_menu($ci, $id_user_level);

    return $granted;
}

function hak_akses_keuangan_revoke_level($ci, $id_user_level)
{
    $id_user_level = (int) $id_user_level;
    $removed = 0;

    $submenus = hak_akses_keuangan_submenu_rows($ci);
    $menu_ids = array();
    foreach ($submenus as $sub) {
        $menu_ids[] = (int) $sub->id;
    }

    if (!empty($menu_ids)) {
        $ci->db->where('id_user', 0);
        $ci->db->where('id_user_level', $id_user_level);
        $ci->db->where_in('id_menu', $menu_ids);
        $removed += $ci->db->count_all_results('tbl_hak_akses');

        $ci->db->where('id_user', 0);
        $ci->db->where('id_user_level', $id_user_level);
        $ci->db->where_in('id_menu', $menu_ids);
        $ci->db->delete('tbl_hak_akses');
    }

    $tbl_menu_ids = hak_akses_keuangan_config('keuangan_tbl_menu_ids', array(130));
    if (!empty($tbl_menu_ids)) {
        $ci->db->where('id_user', 0);
        $ci->db->where('id_user_level', $id_user_level);
        $ci->db->where_in('id_menu', array_map('intval', (array) $tbl_menu_ids));
        $removed += $ci->db->count_all_results('tbl_hak_akses');

        $ci->db->where('id_user', 0);
        $ci->db->where('id_user_level', $id_user_level);
        $ci->db->where_in('id_menu', array_map('intval', (array) $tbl_menu_ids));
        $ci->db->delete('tbl_hak_akses');
    }

    return $removed;
}

function hak_akses_keuangan_sync_user($ci, $id_user)
{
    $id_user = (int) $id_user;
    $user = $ci->db->get_where('tbl_user', array('id_users' => $id_user), 1)->row();
    if (!$user) {
        return 0;
    }

    if (!hak_akses_is_keuangan_level($user->id_user_level)) {
        return 0;
    }

    hak_akses_keuangan_grant_level($ci, $user->id_user_level);

    $synced = 0;
    foreach (hak_akses_keuangan_submenu_rows($ci) as $sub) {
        $exists = $ci->db->get_where('tbl_hak_akses', array(
            'id_user' => $id_user,
            'id_menu' => (int) $sub->id,
        ), 1);

        if ($exists->num_rows() > 0) {
            continue;
        }

        $ci->db->insert('tbl_hak_akses', array(
            'id_user' => $id_user,
            'id_user_level' => (int) $user->id_user_level,
            'main_menu' => (int) $sub->is_parent,
            'id_menu' => (int) $sub->id,
        ));
        $synced++;
    }

    return $synced;
}

function hak_akses_keuangan_sync_all_users_by_level($ci, $id_user_level)
{
    $users = $ci->db->get_where('tbl_user', array('id_user_level' => (int) $id_user_level))->result();
    $total = 0;
    foreach ($users as $user) {
        $total += hak_akses_keuangan_sync_user($ci, $user->id_users);
    }
    return $total;
}

function hak_akses_keuangan_level_status($ci, $id_user_level)
{
    $submenus = hak_akses_keuangan_submenu_rows($ci);
    $total = count($submenus);
    $granted = 0;

    foreach ($submenus as $sub) {
        if (hak_akses_keuangan_has_level_grant($ci, $id_user_level, $sub->id)) {
            $granted++;
        }
    }

    return array(
        'total' => $total,
        'granted' => $granted,
        'complete' => ($total > 0 && $granted >= $total),
    );
}
