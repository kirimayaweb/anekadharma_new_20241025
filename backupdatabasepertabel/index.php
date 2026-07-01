<?php
/**
 * Fallback router jika mod_rewrite belum aktif di server.
 * URL: /backupdatabasepertabel/ atau /backupdatabasepertabel/index.php
 */
$ci_index = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'index.php';

if (!is_file($ci_index)) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Application bootstrap not found.');
}

$_SERVER['REQUEST_URI'] = '/index.php/backupdatabasepertabel';
require $ci_index;
