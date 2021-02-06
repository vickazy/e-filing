<?php $this->load->view('template/v_header'); ?>

<div class="wrapper">
	<!-- Navbar -->
	<?php $this->load->view('template/v_navbar'); ?>
	<!-- End of Navbar -->

	<!-- Sidebar -->
	<?php $this->load->view('template/v_sidebar'); ?>
	<!-- End of Sidebar -->

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark"><?= $title; ?></h1>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<blockquote class="ml-0 mt-0">
							<strong>Perhatian!</strong><br>
							Setiap perubahan yang terjadi akan berpengaruh pada data yang berhubungan. Mohon berhati-hati ketika <b>mengubah</b> atau <b>menghapus</b> data.
						</blockquote>
					</div>
					<div class="col-8">
						<div class="card">
							<div class="card-header">
								<button type="button" class="btn btn-xs btn-primary" onclick="show_modal()">
									<i class="fa fa-plus"></i> Kategori Dokumen
								</button>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-hover" id="table">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th>Kategori Dokumen</th>
											<th>Keterangan</th>
											<th class="text-center">Opsi</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data as $key => $dt) : ?>
											<tr>
												<td class="text-center"><?= ($key + 1) ?></td>
												<td><?= $dt['jns_kategori']; ?></td>
												<td><?= $dt['keterangan']; ?></td>
												<td class="text-center">
													<span class="badge badge-success" style="cursor: pointer" onclick="sunting('<?= $dt['id_kategori'] ?>')">
														<i class="fa fa-edit"></i>
													</span>
													<span class="badge badge-danger" style="cursor: pointer" onclick="hapus('<?= $dt['id_kategori'] ?>')">
														<i class="fa fa-trash"></i>
													</span>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div>
			<!--/. container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
</div>

<!-- Modal -->
<div class="modal fade" id="modal_form" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Form Kategori Dokumen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" autocomplete="off">
					<input type="hidden" class="form-control" name="id_kategori" id="id_kategori">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis Kategori <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="jns_kategori" id="jns_kategori">
							<span class="help-text"></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Keterangan <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<textarea class="form-control" name="ket_kategori" id="ket_kategori"></textarea>
							<span class="help-text"></span>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-2 col-sm-6">
							<span class="btn btn-primary btn_save" onclick="save_form()"></span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<?php $this->load->view('template/v_footer'); ?>
<!-- End of Footer -->

<script src="<?= base_url('assets/script/kategori-dokumen.js') ?>"></script>
