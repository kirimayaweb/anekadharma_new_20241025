<?php
/**
 * CLI test batch generate — bootstrap DB + helper tanpa full HTTP dispatch.
 */
define('BASEPATH', realpath(__DIR__ . '/../system') . DIRECTORY_SEPARATOR);
define('APPPATH', realpath(__DIR__ . '/../application') . DIRECTORY_SEPARATOR);
define('FCPATH', realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR);
define('ENVIRONMENT', 'development');

require_once BASEPATH . 'core/Common.php';
require_once APPPATH . 'config/database.php';

class GenRecalcTestSession {
	private $data = array();
	public function userdata($key = null) {
		if ($key === null) return $this->data;
		return array_key_exists($key, $this->data) ? $this->data[$key] : null;
	}
	public function set_userdata($key, $val = null) {
		if (is_array($key)) {
			foreach ($key as $k => $v) $this->data[$k] = $v;
		} else {
			$this->data[$key] = $val;
		}
	}
	public function unset_userdata($key) {
		if (is_array($key)) {
			foreach ($key as $k) unset($this->data[$k]);
		} else {
			unset($this->data[$key]);
		}
	}
}

class GenRecalcTestCI {
	public $db;
	public $session;
	public function __construct() {
		global $db;
		$this->db = $db['default'];
		$this->session = new GenRecalcTestSession();
	}
	public function load() {}
}

$db = array();
$active_group = 'default';
require APPPATH . 'config/database.php';

$CI = new GenRecalcTestCI();
$CI->db = DB($db['default'], true);

require_once APPPATH . 'helpers/persediaan_display_helper.php';
require_once APPPATH . 'helpers/pembelian_persediaan_helper.php';

$bulan = isset($argv[1]) ? $argv[1] : '2026-02';
$limit = 30;
$offset = 0;
$totalPers = 0;
$totalPem = 0;
$start = true;

echo "Testing persediaan_generate_recalculate_batch for $bulan\n";

for ($iter = 1; $iter <= 200; $iter++) {
	$result = persediaan_generate_recalculate_batch($CI, $bulan, $offset, $limit, $start);
	$start = false;

	if (empty($result['ok'])) {
		echo "FAIL iter=$iter offset=$offset: " . ($result['message'] ?? 'unknown') . "\n";
		exit(1);
	}

	$nP = isset($result['items_persediaan']) ? count($result['items_persediaan']) : 0;
	$nB = isset($result['items_pembelian']) ? count($result['items_pembelian']) : 0;
	$totalPers += $nP;
	$totalPem += $nB;

	echo sprintf(
		"iter=%d phase=%s done=%s offset_in=%d selesai=%s items_p=%d items_b=%d\n",
		$iter,
		$result['phase'] ?? '?',
		!empty($result['done']) ? 'Y' : 'N',
		$offset,
		$result['offset_selesai'] ?? 0,
		$nP,
		$nB
	);

	if (!empty($result['done'])) {
		echo "DONE: " . json_encode($result['summary'] ?? array(), JSON_UNESCAPED_UNICODE) . "\n";
		echo "Collected items_persediaan=$totalPers items_pembelian=$totalPem\n";
		exit(0);
	}

	$offset = (int) ($result['offset_selesai'] ?? 0);
}

echo "Max iterations reached\n";
exit(1);
