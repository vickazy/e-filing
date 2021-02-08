<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cari_dokumen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
		$this->load->model('M_jenis_dokumen', 'm_jns_dokumen');
		$this->load->model('M_unit_tujuan', 'm_tujuan');
		$this->load->model('M_config', 'm_config');

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

		// var_dump($_POST); die;

		$post = array('tipe_dokumen');

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
		$page = 'user/v_pencarian_dokumen';

		$data['title'] = 'Pencarian Dokumen';
		$group = $this->m_config->read(['status' => 1])->row_array();

		$data['jns_dokumen'] = $this->m_jns_dokumen->show();
		$qry = 'SELECT * FROM tbl_unit WHERE kd_unit != \'' . $group['nm_group'] . '\' ORDER BY CASE WHEN nm_unit LIKE \'%group%\' THEN 1 ELSE 2 END';
		$data['dari'] = $this->db->query($qry)->result_array();

		$this->load->view($page, $data);
	}


	public function get_list()
	{
		$this->validasi();

		if (input('jns_dokumen') != '') $this->db->where(['a.jns_dokumen' => input('jns_dokumen')]);
		if (input('no_dokumen') != '') $this->db->like(['a.no_dokumen' => input('no_dokumen')]);
		if (input('perihal') != '') $this->db->like(['a.perihal' => input('perihal')]);

		$exp = explode(' - ', input('dari'));

		if (input('tipe_dokumen') == 'Dokumen Masuk') {
			if (input('dari') != '') $this->db->like(['a.dari' => $exp[0]]);

			$this->db->select('a.*, b.jns_dokumen, c.jns_kategori')->from('tbl_dok_masuk a')
				->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
				->join('tbl_kategori c', 'a.kategori = c.id_kategori', 'left')->order_by('a.tgl_diterima desc');
			$list = $this->db->get()->result_array();
		} else {
			if (input('dari') != '') $this->db->like(['a.unit_tujuan' => $exp[0]]);

			$this->db->select('a.*, c.nm_pegawai, b.jns_dokumen, d.jns_kategori')->from('tbl_dok_keluar a')
				->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
				->join('tbl_pegawai c', 'a.pembuat = c.id_pegawai', 'left')
				->join('tbl_kategori d', 'a.kategori = d.id_kategori', 'left')->order_by('a.tgl_dokumen desc');
			$data = $this->db->get()->result_array();

			// buat struktur data yang akan ditampilkan pada tabel yang akan di tampung pada $list = array();
			$list = array();
			foreach ($data as $key => $val) {
				$row = array();
				$row[] = '<tr>';
				$row[] = '<td class="text-center">' . ($key + 1) . '</td>';

				$jns_dokumen = $val['jns_dokumen'] . '<br>';
				$jns_dokumen .= $val['jns_kategori'] != 'Umum' ? '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' . $val['jns_kategori'] . '</span>' : '';
				$row[] = '<td>' . $jns_dokumen . '</td>';

				$detail = '<b>' . $val['perihal'] . '</b><br>';
				$detail .= '<span>Pembuat: ' . $val['nm_pegawai'] . '<hr>No. ' . $val['jns_dokumen'] . ': ' . $val['no_dokumen'] . '</span>';
				$row[] = '<td>' . $detail . '</td>';

				$exp = '';
				foreach (unserialize($val['unit_tujuan']) as $str) {
					$exp .= $str . '<br>';
				}
				$row[] = '<td>' . $exp . '</td>';

				$row[] = '<td>'.tgl_indo($val['tgl_dokumen']).'</td>';
				if ($val['sts_dokumen'] == 'Booking') {
					$status = 'info';
				} else if ($val['sts_dokumen'] == 'Sent') {
					$status = 'success';
				} else if ($val['sts_dokumen'] == 'Pending') {
					$status = 'warning';
				} else {
					$status = 'danger';
				}
				$row[] = '<td><span class="badge badge-' . $status . '">' . $val['sts_dokumen'] . '</span></td>';
				$row[] = '</tr>';

				$list[] = $row;
			}
		}

		echo json_encode(['status' => true, 'data' => $list]);
		exit;
	}
}
