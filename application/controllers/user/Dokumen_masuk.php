<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dokumen_masuk extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_login', 'm_login');
		$this->load->model('M_dokumen_masuk', 'm_dok_masuk');
		$this->load->model('M_jenis_dokumen', 'm_jns_dokumen');
		$this->load->model('M_kategori', 'm_kategori');
		$this->load->model('M_unit_tujuan', 'm_tujuan');
		$this->load->model('M_config', 'm_config');
		$this->load->model('M_pegawai', 'm_pegawai');

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
		$page = 'user/v_dokumen_masuk';
		$group = $this->m_config->read(['status' => 1])->row_array();

		$data['title'] = 'Dokumen Masuk';
		// tampilkan jenis dokumen Memo, Nota, Surat saja
		$data['jns_dokumen'] = $this->m_jns_dokumen->read(['id_jns_dokumen <=' => 3])->result_array();
		$data['kategori'] = $this->m_kategori->show();
		$data['pegawai'] = $this->m_pegawai->show();
		$qry = 'SELECT * FROM tbl_unit WHERE kd_unit != \'' . $group['nm_group'] . '\' ORDER BY CASE WHEN nm_unit LIKE \'%group%\' THEN 1 ELSE 2 END';
		$data['dari'] = $this->db->query($qry)->result_array();

		$this->load->view($page, $data);
	}

	public function validasi()
	{
		$data = array();
		$data['inputerror'] = array();
		$data['error'] = array();
		$data['status'] = true;

		// var_dump($_POST); die;

		$post = array(
			'jns_dokumen', 'perihal', 'no_dok', 'kategori', 'dari', 'tgl_diterima', 'tgl_dokumen'
		);

		foreach ($post as $post) {
			if (input($post) == '') {
				$data['inputerror'][] = $post;
				$data['error'][] = 'Bagian ini harus diisi';
				$data['status'] = false;
			}
		}

		if (isset($_POST['disposisi']) && input('tgl_disposisi') == '') {
			$data['inputerror'][] = 'tgl_disposisi';
			$data['error'][] = 'Bagian ini harus diisi juga';
			$data['status'] = false;
		}
		
		if (!isset($_POST['disposisi']) && input('tgl_disposisi') != '') {
			$data['inputerror'][] = 'disposisi';
			$data['error'][] = 'Bagian ini harus diisi juga';
			$data['status'] = false;
		}

		if ($data['status'] === false) {
			echo json_encode($data);
			exit();
		}
	}

	public function get_list()
	{
		$list = $this->m_dok_masuk->get_datatables();
		$data = array();
		$no = $_POST['start'] + 1;
		foreach ($list as $li) {
			$row = array();
			$row[] = '<center>' . $no++ . '</center>';

			$jns_dokumen = $li['jns_dokumen'] . '<br>';
			$jns_dokumen .= $li['jns_kategori'] != 'Umum' ? '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' . $li['jns_kategori'] . '</span>' : '';
			$row[] = $jns_dokumen;

			$detail = '<b>' . $li['perihal'] . '</b><br>';
			$detail .= '<span>Dari: ' . $li['dari'] . '<hr>No. ' . $li['jns_dokumen'] . ': ' . $li['no_dokumen'] . '</span>';
			$row[] = $detail;

			$exp = '';
			if ($li['tgl_disposisi'] != null) {
				$exp .= '<p class="text-success my-0"><i class="fa fa-share"></i> Didisposisikan</p>';
				$exp .= '<span>Pada ' . tgl_indo($li['tgl_disposisi']) . '</span>';
			} else {
				$exp .= '<p class="text-info"><i class="fa fa-envelope-open"></i> Diterima</p>';
			}
			$row[] = $exp;

			$row[] = tgl_indo($li['tgl_diterima']);

			$aksi = '<center>';
			// priview file before download
			$download = $li['file_dokumen'] != null ? '<a href="' . base_url('assets/' . $li['path_folder'] . '/' . $li['file_dokumen']) . '" target="_blank" class="badge badge-warning" style="cursor: pointer"><i class="fa fa-download"></i></a>&nbsp;' : '';
			$aksi .= $download;

			$aksi .= '<span class="badge badge-info" style="cursor: pointer" onclick="view(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-eye"></i></span>&nbsp;';
			$aksi .= '<span class="badge badge-success" style="cursor: pointer" onclick="sunting(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-edit"></i></span>&nbsp;';
			if ($_SESSION['lv_user'] == 'sekre') :
				$aksi .= '<span class="badge badge-danger" style="cursor: pointer" onclick="hapus(\'' . $li['id_dokumen'] . '\')"><i class="fa fa-trash"></i></span>';
			endif;
			$aksi .= '</center>';
			$row[] = $aksi;

			$data[] = $row;
		}

		$output = array(
			'draw' => intval($_POST['draw']),
			'recordsTotal' => $this->m_dok_masuk->get_all_data(),
			'recordsFiltered' => $this->m_dok_masuk->count_filtered(),
			'data' => $data
		);
		echo json_encode($output);
		exit();
	}

	public function get_data($id)
	{
		$key['id_dokumen'] = $id;
		$data = $this->m_dok_masuk->read($key)->row_array();

		$list = unserialize($data['disposisi']);

		$respon = array(
			'id_dokumen' => $data['id_dokumen'],
			'no_dokumen' => $data['no_dokumen'],
			'jns_dokumen' => $data['jns_dokumen'],
			'dari' => $data['dari'],
			'disposisi' => $list,
			'perihal' => $data['perihal'],
			'lampiran' => $data['lampiran'],
			'kategori' => $data['kategori'],
			'catatan' => $data['catatan'],
			'file_dokumen' => $data['file_dokumen'],
			'tgl_dokumen' => parse_tgl_db($data['tgl_dokumen']),
			'tgl_disposisi' => $data['tgl_disposisi'] != null ? parse_tgl_db($data['tgl_disposisi']) : '',
			'tgl_diterima' => parse_tgl_db($data['tgl_diterima'])
		);
		echo json_encode($respon);
		exit;
	}

	public function insert()
	{
		$this->validasi();

		// get jenis dokumen yang dipilih
		$dokumen = $this->m_jns_dokumen->read(['id_jns_dokumen' => input('jns_dokumen')])->row_array();

		// buat folder sesuai tahun dan bulan
		$nm_folder = date('Y-m');
		// buat folder sesuai dengan jenis dokumen yang dipilih
		$nm_dok = strtoupper($dokumen['jns_dokumen']);

		// periksa folder $nm_folder sudah ada atau belum
		if (!is_dir('assets/berkas-masuk/' . $nm_folder)) {
			// buat folder $nm_folder jika belum ada
			mkdir('./assets/berkas-masuk/' . $nm_folder, 0777, true);
		}

		// periksa folder $nm_dok didalam folder $nm_folder sudah ada atau belum
		if (!is_dir('assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok)) {
			// buat folder $nm_dok jika belum ada
			mkdir('./assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok, 0777, true);
		}

		$config = array(
			'upload_path' => './assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok,
			'allowed_types' => 'pdf'
		);

		$this->load->library('upload', $config);

		$data = array(
			'jns_dokumen' => input('jns_dokumen'),
			'no_dokumen' => strtoupper(input('no_dok')),
			'dari' => input('dari'),
			'perihal' => strtoupper(input('perihal')),
			'lampiran' => input('lampiran') == '' ? 0 : input('lampiran'),
			'kategori' => input('kategori'),
			'tgl_dokumen' => parse_tgl(input('tgl_dokumen')),
			'tgl_diterima' => parse_tgl(input('tgl_diterima')),
			'tgl_disposisi' => input('tgl_disposisi') == '' ? NULL : parse_tgl(input('tgl_disposisi')),
			'catatan' => input('catatan') == '' ? NULL : input('catatan')
		);

		if (isset($_POST['disposisi'])) {
			$data['disposisi'] = serialize($_POST['disposisi']);
		} else {
			$data['disposisi'] = null;
		}

		if ($this->upload->do_upload('file')) {
			$fileData = $this->upload->data();
			$data['path_folder'] = 'berkas-masuk/' . $nm_folder . '/' . $nm_dok;
			$data['file_dokumen'] = $fileData['file_name'];
		}

		$this->m_dok_masuk->create($data);

		$title = 'Sukses';
		$text = 'Dokumen telah berhasil tersimpan ';
		$icon = 'success';

		echo json_encode(['status' => true, 'title' => $title, 'icon' => $icon, 'text' => $text]);
		exit;
	}

	public function update()
	{
		$this->validasi();

		// get jenis dokumen yang dipilih
		$dokumen = $this->m_jns_dokumen->read(['id_jns_dokumen' => input('jns_dokumen')])->row_array();

		// buat folder sesuai tahun dan bulan
		$nm_folder = date('Y-m');
		// buat folder sesuai dengan jenis dokumen yang dipilih
		$nm_dok = strtoupper($dokumen['jns_dokumen']);

		// periksa folder $nm_folder sudah ada atau belum
		if (!is_dir('assets/berkas-masuk/' . $nm_folder)) {
			// buat folder $nm_folder jika belum ada
			mkdir('./assets/berkas-masuk/' . $nm_folder, 0777, true);
		}

		// periksa folder $nm_dok didalam folder $nm_folder sudah ada atau belum
		if (!is_dir('assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok)) {
			// buat folder $nm_dok jika belum ada
			mkdir('./assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok, 0777, true);
		}

		$config = array(
			'upload_path' => './assets/berkas-masuk/' . $nm_folder . '/' . $nm_dok,
			'allowed_types' => 'pdf'
		);

		$this->load->library('upload', $config);

		$key['id_dokumen'] = input('id_dok');

		$data = array(
			'jns_dokumen' => input('jns_dokumen'),
			'no_dokumen' => strtoupper(input('no_dok')),
			'dari' => input('dari'),
			'perihal' => strtoupper(input('perihal')),
			'lampiran' => input('lampiran') == '' ? 0 : input('lampiran'),
			'kategori' => input('kategori'),
			'tgl_dokumen' => parse_tgl(input('tgl_dokumen')),
			'tgl_diterima' => parse_tgl(input('tgl_diterima')),
			'tgl_disposisi' => input('tgl_disposisi') == '' ? NULL : parse_tgl(input('tgl_disposisi')),
			'catatan' => input('catatan') == '' ? NULL : input('catatan')
		);

		if (isset($_POST['disposisi'])) {
			$data['disposisi'] = serialize($_POST['disposisi']);
		} else {
			$data['disposisi'] = null;
		}

		if ($this->upload->do_upload('file')) {
			$fileData = $this->upload->data();
			$data['file_dokumen'] = $fileData['file_name'];

			// perikasa apakah path_folder pada database sudah ada atau belum
			$path = $this->m_dok_masuk->read($key)->row_array();
			if ($path['path_folder'] == null) {
				// tambahkan path_folder jika belum ada
				$data['path_folder'] = 'berkas-masuk/' . $nm_folder . '/' . $nm_dok;
			} else {
				unlink('./assets/' . $path['path_folder'] . '/' . $path['file_dokumen']);
			}
		}

		$this->m_dok_masuk->update($data, $key);

		$title = 'Sukses';
		$text = 'Dokumen telah berhasil diubah';
		$icon = 'success';

		echo json_encode(['status' => true, 'title' => $title, 'icon' => $icon, 'text' => $text]);
		exit;
	}

	public function delete($id)
	{
		$key['id_dokumen'] = $id;

		$file = $this->m_dok_masuk->read($key)->row_array();
		if ($file['file_dokumen'] != null) {
			unlink('./assets/' . $file['path_folder'] . '/' . $file['file_dokumen']);
		}
		$this->m_dok_masuk->delete($key);

		echo json_encode(['status' => true]);
		exit;
	}
}
