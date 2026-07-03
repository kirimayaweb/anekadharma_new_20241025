<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Level user yang dianggap "Keuangan" (otomatis dapat paket Jurnal + Accounting + Laporan).
| Default: 888 = kabagkeuangan
*/
$config['keuangan_level_ids'] = array(666, 888);

/*
| Parent menu (tabel `menu`) untuk paket keuangan.
| 500 = Jurnal, 600 = Accounting, 700 = Laporan
*/
$config['keuangan_main_menu_ids'] = array(500, 600, 700);

/*
| id_menu di tbl_menu yang ikut diberi hak (guard is_login).
*/
$config['keuangan_tbl_menu_ids'] = array(130);
