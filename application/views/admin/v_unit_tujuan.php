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
					<div class="col-10">
						<div class="card">
							<div class="card-header">
								<button type="button" class="btn btn-xs btn-primary" onclick="show_modal()">
									<i class="fa fa-plus"></i> Unit Tujuan
								</button>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-hover" id="table">
									<thead>
										<tr>
											<th class="text-center" style="width: 25px;">#</th>
											<th>Kode Unit</th>
											<th>Nama Unit</th>
											<th class="text-center">Opsi</th>
										</tr>
									</thead>
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
				<h5 class="modal-title">Form Unit Tujuan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" autocomplete="off">
					<input type="hidden" class="form-control" name="no" id="no">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Kode Unit <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="kd_unit" id="kd_unit">
							<span class="help-text"></span>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Nama Unit <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<textarea class="form-control" name="nm_unit" id="nm_unit"></textarea>
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

<script src="<?= base_url('assets/script/unit-tujuan.js') ?>"></script>
