<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Anti XSS Filter
|--------------------------------------------------------------------------
|
| untuk keamanan XSS Injection dari form input.
|
*/

if (!function_exists('input')) {
	function input($var)
	{
		$ci = get_instance();
		$input = strip_tags(trim($ci->input->post($var, true)));
		return $input;
	}
}


/*
|--------------------------------------------------------------------------
| Parse tanggal dari database
|--------------------------------------------------------------------------
|
| merubah format tanggal database menjadi format tanggal indonesia.
|
*/

if (!function_exists('tgl_indo')) {
	function tgl_indo($date)
	{
		$arr_bln = array(
			1 => 'januari', 'februari', 'maret', 'april', 'mei', 'juni', 'jul', 'agustus', 'september', 'oktober', 'november', 'desember'
		);

		$exp = explode('-', $date);

		$d = $exp[2];
		$m = $arr_bln[(int) $exp[1]];
		$y = $exp[0];

		$tgl = $d . ' ' . substr(ucfirst($m), 0, 3) . ' ' . $y;
		return $tgl;
	}
}

/*
|--------------------------------------------------------------------------
| Parse tanggal dari form input ke database
|--------------------------------------------------------------------------
|
| merubah format tanggal form input menjadi format tanggal database.
|
*/

if (!function_exists('parse_tgl')) {
	function parse_tgl($date)
	{
		$exp = explode('/', $date);

		$d = $exp[0];
		$m = $exp[1];
		$y = $exp[2];

		$tgl = $y . '-' . $m . '-' . $d;
		return $tgl;
	}
}

/*
|--------------------------------------------------------------------------
| Parse tanggal dari database ke form input
|--------------------------------------------------------------------------
|
| merubah format tanggal database menjadi format tanggal form input.
|
*/

if (!function_exists('parse_tgl_db')) {
	function parse_tgl_db($date)
	{
		$exp = explode('-', $date);

		$d = $exp[2];
		$m = $exp[1];
		$y = $exp[0];

		$tgl = $y . '/' . $m . '/' . $d;
		return $tgl;
	}
}

/*
|--------------------------------------------------------------------------
| Check status config yang digunakan
|--------------------------------------------------------------------------
|
| check status config yang digunakan untuk menjadi referensi dokumen.
|
*/

function sts_check($id)
{
	$ci = get_instance();

	$result = $ci->db->get_where('tbl_config', ['no' => $id, 'status' => '1']);
	if ($result->num_rows() > 0) {
		return "checked='checked'";
	}
}
