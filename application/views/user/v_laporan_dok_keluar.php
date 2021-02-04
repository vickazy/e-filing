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
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h5 class="m-0 text-dark"><?= $title; ?></h5>
							</div>
							<div class="card-body">
								<form id="form" autocomplete="off">
									<div class="form-group row">
										<label class="col-form-label ml-2 mr-3">Tgl. dokumen dibuat</label>
										<div class="col-sm-2">
											<div class="input-group date" id="datepicker">
												<input type="text" class="form-control" name="tgl_awal" id="tgl_awal" placeholder="mm/dd/yyyy">
												<div class="input-group-append">
													<span class="input-group-text">
														<i class="fa fa-fw fa-calendar-alt"></i>
													</span>
												</div>
											</div>
											<small class="help-text" id="tgl_awal-feedback"></small>
										</div>
										<label class="col-form-label ml-2 mr-3">Sampai dengan</label>
										<div class="col-sm-2">
											<div class="input-group date" id="datepicker">
												<input type="text" class="form-control" name="tgl_akhir" id="tgl_akhir" placeholder="mm/dd/yyyy">
												<div class="input-group-append">
													<span class="input-group-text">
														<i class="fa fa-fw fa-calendar-alt"></i>
													</span>
												</div>
											</div>
											<small class="help-text" id="tgl_akhir-feedback"></small>
										</div>
										<div class="col-sm-2">
											<span class="btn btn-primary btn_show">Tampilkan</span>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row" id="view_result">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<button type="button" class="btn btn-xs btn-success float-right export">
									<i class="fa fa-file-export"></i> Export CSV
								</button>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-hover" id="table" style="width: 100%;">
									<thead>
										<tr>
											<th class="text-center" style="width: 25px;">#</th>
											<th>Jenis Dokumen</th>
											<th>Detail Dokumen</th>
											<th>Unit Tujuan</th>
											<th style="width: 10%;">Tgl. Dibuat</th>
											<th>Status</th>
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

<!-- Footer -->
<?php $this->load->view('template/v_footer'); ?>
<!-- End of Footer -->

<script>
	var save_method = '';

	$(document).ready(function() {
		//datatables
		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [],
			'ajax': {
				'url': "<?= site_url('user/laporan_dok_keluar/get_list') ?>",
				'type': 'POST',
				'data': function(data) {
					data.tgl_awal = $('#tgl_awal').val().replace('%2F', '/');
					data.tgl_akhir = $('#tgl_akhir').val().replace('%2F', '/');
				}
			},
			'ordering': false
		});

		$('.btn_show').click(function() {
			table.ajax.reload();
		});

		$('.export').click(function() {
			$.ajax({
				url: '<?= site_url('user/laporan_dok_keluar/export') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: $('#form').serialize(),
				success: function(data) {
					if (data.status === false) {
						$('.help-text').removeClass('text-red').empty();
						for (var i = 0; i < data.inputerror.length; i++) {
							$('#' + data.inputerror[i] + '-feedback').addClass('text-red').text(data.error[i]);
						}
					}
				}
			});
		});
	});
</script>
