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
							* <b>Memo</b> / <b>Nota</b> untuk unit tujuan <i>internal</i>. <br>
							* <b>Surat</b> untuk unit tujuan <i>eksternal</i>. <br>
							* Dokumen yang sudah dibuat tidak bisa dihapus kembali. <br>
							* File yang sudah di upload tidak bisa dihapus kembali. <br>
							* Untuk <b>Unit Tujuan</b> <i>internal</i> atau <b>Nama Pegawai</b> yang belum terdaftar harap hubungin bagian Administrator.
						</blockquote>
					</div>
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<button type="button" class="btn btn-xs btn-primary" onclick="show_modal()">
									<i class="fa fa-plus"></i> Dokumen Keluar
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
											<th class="text-center" style="width: 10%;">Opsi</th>
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
				<h5 class="modal-title">Form Dokumen Keluar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form" enctype="multipart/form-data" autocomplete="off">
					<input type="hidden" class="form-control" name="id_dok" id="id_dok">
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Jenis Dokumen <sup class="text-red">*</sup></label>
						<div class="col-sm-4">
							<select class="form-control selectpicker" name="jns_dokumen" id="jns_dokumen">
								<option selected disabled>-- Please Select --</option>
								<?php foreach ($jns_dokumen as $li) : ?>
									<option value="<?= $li['id_jns_dokumen'] ?>"><?= $li['jns_dokumen']; ?></option>
								<?php endforeach; ?>
							</select>
							<small class="help-text" id="jns_dokumen-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Tgl. Dokumen <sup class="text-red">*</sup></label>
						<div class="col-sm-3">
							<div class="input-group date" id="datepicker">
								<input type="text" class="form-control" name="tgl_dokumen" id="tgl_dokumen">
								<div class="input-group-append">
									<span class="input-group-text">
										<i class="fa fa-fw fa-calendar-alt"></i>
									</span>
								</div>
							</div>
							<small class="help-text" id="tgl_dokumen-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Unit Tujuan <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<select class="form-control select2" name="li_tujuan[]" id="li_tujuan" multiple="multiple" style="width: 100%;">
								<option disabled>-- Please Select --</option>
								<?php foreach ($tujuan as $li) : ?>
									<option value="<?= $li['kd_unit'] . ' - ' . $li['nm_unit'] ?>"><?= $li['kd_unit'] . ' - ' . $li['nm_unit'] ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="offset-2 col-sm-10">
							<small class="help-text" id="li_tujuan-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-2 col-sm-6">
							<input type="text" class="form-control" name="tujuan_lain" id="tujuan_lain" placeholder="Unit tujuan lain">
							<small class="help-text" id="tujuan_lain-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Perihal <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<textarea class="form-control" name="perihal" id="perihal"></textarea>
							<small class="help-text" id="perihal-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Dibuat Oleh <sup class="text-red">*</sup></label>
						<div class="col-sm-6">
							<select class="form-control selectpicker" name="pembuat" id="pembuat" data-live-search="true">
								<option selected disabled>-- Please Select --</option>
								<?php foreach ($pembuat as $li) : ?>
									<option value="<?= $li['id_pegawai'] ?>"><?= $li['nm_pegawai']; ?></option>
								<?php endforeach; ?>
							</select>
							<small class="help-text" id="pembuat-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Lampiran</label>
						<div class="col-sm-1">
							<input type="text" class="form-control" name="lampiran" id="lampiran" onkeypress="return CheckNumeric()">
						</div>
						<label class="col-sm-auto col-form-label">Lembar <span class="ml-3 text-muted">(kosongkan bila tidak ada lampiran)</span></label>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Kategori <sup class="text-red">*</sup></label>
						<div class="col-sm-3">
							<select class="form-control selectpicker" name="kategori" id="kategori">
								<option selected disabled>-- Please Select --</option>
								<?php foreach ($kategori as $li) : ?>
									<option value="<?= $li['id_kategori'] ?>"><?= $li['jns_kategori']; ?></option>
								<?php endforeach; ?>
							</select>
							<small class="help-text" id="kategori-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Status Dokumen <sup class="text-red">*</sup></label>
						<div class="col-sm-3">
							<select class="form-control selectpicker" name="sts_dokumen" id="sts_dokumen">
								<option selected disabled>-- Please Select --</option>
								<option value="Booking">Booking</option>
								<option value="Sent">Sent</option>
								<option value="Pending">Pending</option>
								<option value="Cancel">Cancel</option>
							</select>
							<small class="help-text" id="sts_dokumen-feedback"></small>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">File Upload</label>
						<div class="col-sm-6">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="file" id="file">
								<label class="custom-file-label" for="file">Choose file</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Catatan</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="catatan" id="catatan"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-2 col-sm-6">
							<button type="submit" class="btn btn-primary btn_save"></button>
							<!-- <span class="btn btn-primary btn_save" onclick="save_form()"></span> -->
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

<script src="<?= base_url('assets/script/dokumen-keluar.js') ?>"></script>
