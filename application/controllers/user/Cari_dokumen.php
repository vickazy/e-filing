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
			$hak_akses = $cek_role['lv_user'] == 'admin' ? 'admin' : 'user';
			if ($hak_akses != $this->uri->segment('1')) {
				session_destroy();
				redirect(base_url());
			}
		} else {
			session_destroy();
			redirect(base_url());
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

	public function get_list_dok_masuk()
	{
		if (input('jns_dokumen') != '') $this->db->where(['a.jns_dokumen' => input('jns_dokumen')]);
		if (input('no_dokumen') != '') $this->db->like(['a.no_dokumen' => input('no_dokumen')]);
		if (input('perihal') != '') $this->db->like(['a.perihal' => input('perihal')]);

		$exp = explode(' - ', input('dari'));
		if (input('dari') != '') $this->db->like(['a.dari' => $exp[0]]);

		$this->db->select('a.*, b.jns_dokumen, c.jns_kategori')->from('tbl_dok_masuk a')
			->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
			->join('tbl_kategori c', 'a.kategori = c.id_kategori', 'left')->order_by('a.tgl_diterima desc');
		$data = $this->db->get()->result_array();

		$list = array();
		foreach ($data as $key => $dt) {
			$row = array();

			$row['no'] = ($key + 1);
			$kategori = $dt['jns_dokumen'] . '<br>';
			$kategori .= $dt['jns_kategori'] != 'Umum' ? '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' . $dt['jns_kategori'] . '</span>' : '';
			$row['kategori'] = $kategori;

			$detail = '<b>' . $dt['perihal'] . '</b><br>';
			$detail .= '<span>Dari: ' . $dt['dari'] . '<hr>No. ' . $dt['jns_dokumen'] . ': ' . $dt['no_dokumen'] . '</span>';
			$row['detail'] = $detail;

			$exp = '';
			if ($dt['tgl_disposisi'] != null) {
				$exp .= '<p class="text-success my-0"><i class="fa fa-share"></i> Didisposisikan</p>';
				$exp .= '<span>Pada ' . tgl_indo($dt['tgl_disposisi']) . '</span>';
			} else {
				$exp .= '<p class="text-info"><i class="fa fa-envelope-open"></i> Diterima</p>';
			}
			$row['status'] = $exp;

			$row['tgl_terima'] = tgl_indo($dt['tgl_diterima']);

			$list[] = $row;
		}

		echo json_encode(['status' => true, 'data' => $list]);
		exit;
	}

	public function get_list_dok_keluar()
	{
		if (input('jns_dokumen') != '') $this->db->where(['a.jns_dokumen' => input('jns_dokumen')]);
		if (input('no_dokumen') != '') $this->db->like(['a.no_dokumen' => input('no_dokumen')]);
		if (input('perihal') != '') $this->db->like(['a.perihal' => input('perihal')]);

		$exp = explode(' - ', input('dari'));
		if (input('dari') != '') $this->db->like(['a.unit_tujuan' => $exp[0]]);

		$this->db->select('a.*, c.nm_pegawai, b.jns_dokumen, d.jns_kategori')->from('tbl_dok_keluar a')
			->join('tbl_jns_dokumen b', 'a.jns_dokumen = b.id_jns_dokumen', 'left')
			->join('tbl_pegawai c', 'a.pembuat = c.id_pegawai', 'left')
			->join('tbl_kategori d', 'a.kategori = d.id_kategori', 'left')->order_by('a.nomor desc, a.tgl_dokumen desc');
		$data = $this->db->get()->result_array();

		$list = array();
		foreach ($data as $key => $dt) {
			$row = array();

			$row['no'] = ($key + 1);
			$kategori = $dt['jns_dokumen'] . '<br>';
			$kategori .= $dt['jns_kategori'] != 'Umum' ? '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' . $dt['jns_kategori'] . '</span>' : '';
			$row['kategori'] = $kategori;

			$detail = '<b>' . $dt['perihal'] . '</b><br>';
			$detail .= '<span>Pembuat: ' . $dt['nm_pegawai'] . '<hr>No. ' . $dt['jns_dokumen'] . ': ' . $dt['no_dokumen'] . '</span>';
			$row['detail'] = $detail;

			$exp = '';
			foreach (unserialize($dt['unit_tujuan']) as $val) {
				$exp .= $val . '<br>';
			}
			$row['unit_tujuan'] = $exp;

			$row['tgl_dokumen'] = tgl_indo($dt['tgl_dokumen']);
			if ($dt['sts_dokumen'] == 'Booking') {
				$status = 'info';
			} else if ($dt['sts_dokumen'] == 'Sent') {
				$status = 'success';
			} else if ($dt['sts_dokumen'] == 'Pending') {
				$status = 'warning';
			} else {
				$status = 'danger';
			}
			$row['status'] = '<span class="badge badge-' . $status . '">' . $dt['sts_dokumen'] . '</span>';

			$list[] = $row;
		}

		echo json_encode(['status' => true, 'data' => $list]);
		exit;
	}
}
