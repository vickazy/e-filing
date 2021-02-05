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
										<label class="col-sm-2 col-form-label">Tipe Dokumen <sup class="text-red">*</sup></label>
										<div class="col-sm-2">
											<select class="form-control selectpicker" name="tipe_dokumen" id="tipe_dokumen">
												<option value="">-- Please Select --</option>
												<option value="Dokumen Masuk">Dokumen Masuk</option>
												<option value="Dokumen Keluar">Dokumen Keluar</option>
											</select>
											<small class="help-text" id="tipe_dokumen-feedback"></small>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Jenis Dokumen</label>
										<div class="col-sm-2">
											<select class="form-control selectpicker" name="jns_dokumen" id="jns_dokumen">
												<option value="">-- Please Select --</option>
												<?php foreach ($jns_dokumen as $li) : ?>
													<option value="<?= $li['id_jns_dokumen'] ?>"><?= $li['jns_dokumen']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Nomor Dokumen</label>
										<div class="col-sm-2">
											<input type="text" class="form-control" name="no_dokumen" id="no_dokumen">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Unit Tujuan / Dari</label>
										<div class="col-sm-6">
											<input class="form-control" name="dari" id="dari" list="li_unit">
											<datalist id="li_unit">
												<?php foreach ($dari as $li) : ?>
													<option value="<?= $li['kd_unit'] . ' - ' . $li['nm_unit'] ?>">
													<?php endforeach; ?>
											</datalist>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Perihal</label>
										<div class="col-sm-6">
											<textarea name="perihal" id="perihal" rows="3" class="form-control"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<div class="offset-2 col-sm-10">
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
						<div class="card show_dok_masuk" style="display: none;">
							<div class="card-body">
								<table class="table table-bordered table-hover" id="tbl_dok_masuk" style="width: 100%;">
									<thead>
										<tr>
											<th class="text-center" style="width: 25px;">#</th>
											<th>Jenis Dokumen</th>
											<th>Detail Dokumen</th>
											<th style="width: 15%;">Status Dokumen</th>
											<th style="width: 10%">Tgl. Diterima</th>
										</tr>
									</thead>
									<tbody id="dok_masuk"></tbody>
								</table>
							</div>
						</div>
						<div class="card show_dok_keluar" style="display: none;">
							<div class="card-body">
								<table class="table table-bordered table-hover" id="tbl_dok_keluar" style="width: 100%;">
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
									<tbody id="dok_keluar"></tbody>
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
	$(document).ready(function() {
		$('#tbl_dok_masuk, #tbl_dok_keluar').DataTable({
			'ordering': false,
			'searching': false
		});

		$('.btn_show').click(function() {
			$.ajax({
				url: '<?= site_url('user/cari_dokumen/get_list') ?>',
				type: 'POST',
				dataType: 'JSON',
				data: $('#form').serialize(),
				success: function(respon) {
					$('tbody#dok_masuk').empty();
					$('.show_dok_masuk').css('display', 'none');
					$('.show_dok_keluar').css('display', 'none');

					var html = '';
					if (respon.status === true) {
						$('.help-text').removeClass('text-red').empty();

						if ($('#tipe_dokumen').val() == 'Dokumen Masuk') {
							$('.show_dok_masuk').css('display', 'block');
							$('.show_dok_keluar').css('display', 'none');

							if (respon.data.length > 0) {
								for (let i = 0; i < respon.data.length; i++) {
									html += '<tr>';
									html += '<td class="text-center">' + (i + 1) + '</td>';
									html += '<td>';
									html += respon.data[i].jns_dokumen + '<br>';
									if (respon.data[i].jns_kategori != 'Umum') {
										html += '<span class="badge badge-danger"><i class="fa fa-info-circle"></i> ' + respon.data[i].jns_kategori + '</span>';
									}
									html += '<td>';
									html += '<b>' + respon.data[i].perihal + '</b><br>';
									html += '<span>Dari: ' + respon.data[i].dari + '<hr>No. ' + respon.data[i].jns_dokumen + ': ' + respon.data[i].no_dokumen + '</span>';
									html += '</td>';
									html += '<td>';
									if (respon.data[i].tgl_disposisi != null) {
										html += '<p class="text-success my-0"><i class="fa fa-share"></i> Didisposisikan</p>';
										html += '<span>Pada ' + tgl_indo(respon.data[i].tgl_disposisi) + '</span>';
									} else {
										html += '<p class="text-info"><i class="fa fa-envelope-open"></i> Diterima</p>';
									}
									html += '</td>';
									html += '<td>' + tgl_indo(respon.data[i].tgl_diterima) + '</td>';
									html += '</tr>';
								}
							} else {
								html += '<tr><td class="text-center" colspan="5">Tidak ditemukan data yang cocok</td></tr>';
							}
							$('tbody#dok_masuk').html(html);
						} else {
							$('.show_dok_masuk').css('display', 'none');
							$('.show_dok_keluar').css('display', 'block');

							$('tbody#dok_keluar').html(html);
						}

					} else {
						for (var i = 0; i < respon.inputerror.length; i++) {
							$('#' + respon.inputerror[i] + '-feedback').addClass('text-red').text(respon.error[i]);
						}
					}
				}
			});
		});
	});

	function tgl_indo(tgl) {
		bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];

		date = tgl.split('-');

		return date[2] + ' ' + bln[parseInt(date[1]) - 1] + ' ' + date[0];
	}
</script>
