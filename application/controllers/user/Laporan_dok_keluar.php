<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_dok_keluar extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
		$this->load->model('M_laporan_dok_keluar', 'm_laporan_dok_keluar');

		$is_login = $this->session->userdata('is_login');

		if ($is_login === true) {
			$cek_role = $this->m_login->get_user($this->session->userdata('username'));
			if ($cek_role['lv_user'] != $this->uri->segment('1')) {
				session_destroy();
				redirect(base_url());
			}
		} else {
			session_destroy();
			redirect(base_url());
		}
	}

	public function validasi()
	{
		$data = array();
		$data['inputerror'] = array();
		$data['error'] = array();
		$data['status'] = true;

		$post = array('tgl_awal', 'tgl_akhir');

		foreach ($post as $post) {
			if (input($post) == '') {
				$data['inputerror'][] = $post;
				$data['error'][] = 'Bagian ini harus diisi';
				$data['status'] = false;
			}
		}

		if ($data['status'] === false) {
			echo json_encode($data);
			exit();
		}
	}

	public function index()
	{
		$page = 'user/v_laporan_dok_keluar';

		$data['title'] = 'Laporan Dokumen Keluar';

		$this->load->view($page, $data);
	}

	public function get_list()
	{
		$list = $this->m_laporan_dok_keluar->get_datatables();
		$data = array();
		$no = $_POST['start'] + 1;
		foreach ($list as $li) {
			$row = array();
			$row[] = '<center>' . $no++ . '</center>';

			$jns_dokumen = $li['jns_dokumen'] . '<br>';
			$jns_dokumen .= $li['jns_kategori'] != 'Umum' ? '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' . $li['jns_kategori'] . '</span>' : '';
			$row[] = $jns_dokumen;

			$detail = '<b>' . $li['perihal'] . '</b><br>';
			$detail .= '<span>Pembuat: ' . $li['nm_pegawai'] . '<hr>No. ' . $li['jns_dokumen'] . ': ' . $li['no_dokumen'] . '</span>';
			$row[] = $detail;

			$exp = '';
			foreach (unserialize($li['unit_tujuan']) as $val) {
				$exp .= $val . '<br>';
			}
			$row[] = $exp;

			$date = explode(' ', $li['createDate']);
			$row[] = tgl_indo($date[0]);
			$row[] = $li['sts_dokumen'];

			$data[] = $row;
		}

		$output = array(
			'draw' => intval($_POST['draw']),
			'recordsTotal' => $this->m_laporan_dok_keluar->get_all_data(),
			'recordsFiltered' => $this->m_laporan_dok_keluar->count_filtered(),
			'data' => $data
		);
		echo json_encode($output);
		exit();
	}

	public function export()
	{
		// $this->validasi();

		if (input('tgl_awal') == '' && input('tgl_akhir') == '') {
			$filename = 'dokumen-keluar.xlsx';
		} else {
			if (input('tgl_awal') == input('tgl_akhir')) {
				$filename = 'dokumen-keluar_' . str_replace('-', '', parse_tgl(input('tgl_awal'))) . '.xlsx';
			} else {
				$filename = 'dokumen-keluar_' . str_replace('-', '', parse_tgl(input('tgl_awal'))) . '-' . str_replace('-', '', parse_tgl(input('tgl_akhir'))) . '.xlsx';
			}
		}

		$this->db->select('a.*, c.nm_pegawai, b.jns_dokumen, d.jns_kategori')->from('tbl_dok_keluar a')
			->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
			->join('tbl_pegawai c', 'a.pembuat = c.id_pegawai', 'left')
			->join('tbl_kategori d', 'a.kategori = d.id_kategori', 'left');
		if (input('tgl_awal') != '' && input('tgl_akhir') != '') {
			$where = "a.tgl_dokumen between '" . parse_tgl(input('tgl_awal')) . "' and '" . parse_tgl(input('tgl_akhir')) . "'";
			$this->db->where($where);
		}
		$list = $this->db->get()->result_array();

		echo json_encode(['data' => $list]);
		exit;
	}
}
