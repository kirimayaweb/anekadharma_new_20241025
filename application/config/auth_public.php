<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| Controller yang BOLEH diakses tanpa session login.
| Nama controller huruf kecil (sesuai fetch_class / URI).
*/
$config['auth_public_controllers'] = array(
    'anekadharmamasuk',
    'masuk',
    'masukgo',
    'authx',
    'auth',
    'authpage',
    'blokir',
    'cms',
);
