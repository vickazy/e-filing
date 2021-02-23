	var save_method = '';

	$(document).ready(function () {
		//datatables
		table = $('#table').DataTable({
			'processing': true,
			'serverSide': true,
			'order': [],
			'ajax': {
				'url': '../../user/page/dokumen-masuk/list',
				'type': 'POST'
			},
			'ordering': false
		});

		$('input[type="file"]').on('change', function () {
			//get the file name
			var file = $(this).val();
			var fileName = file.replace('C:\\fakepath\\', '');
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		});

		$('#no_dok, #perihal').on('keypress', function () {
			$(this).css('text-transform', 'uppercase');
		});

		$('#form').on('change', 'input[type="file"]', function () {
			//get the file name
			var file = $(this).val();
			var fileName = file.replace('C:\\fakepath\\', '');
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);

			var size = $(this)[0].files[0].size / 1024;
			console.log(size);

			if ($(this)[0].files[0].type != 'application/pdf') {
				Swal.fire({
					title: 'Oops!',
					icon: 'warning',
					text: 'Format file upload tidak valid!'
				});
				$(this).next('.custom-file-label').html('Choose file');
				$(this).val('');
			}
			// else {
			// 	if (size > (1024 * 20)) {
			// 		Swal.fire({
			// 			title: 'Oops!',
			// 			icon: 'warning',
			// 			text: 'Ukuran file melebihi batas, maksimal 20 MB!'
			// 		});
			// 		$(this).next('.custom-file-label').html('Choose file');
			// 		$(this).val('');
			// 	}
			// }
		});

		// save form
		$('#form').submit(function (evt) {
			evt.preventDefault();

			var url = '';
			if (save_method == 'add') url = '../../user/page/dokumen-masuk/insert';
			else url = '../../user/page/dokumen-masuk/update';

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'JSON',
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function (data) {
					if (data.status === true) {
						Swal.fire({
							title: data.title,
							text: data.text,
							icon: data.icon,
							timer: 2000,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					} else {
						$('.help-text').removeClass('text-red').empty();
						for (var i = 0; i < data.inputerror.length; i++) {
							$('#' + data.inputerror[i] + '-feedback').addClass('text-red').text(data.error[i]);
						}
					}
				}
			});
		});
	});

	function reset_form() {
		$('#form')[0].reset();
		$('.custom-file-label').text('Choose file');
		$('.help-text').removeClass('text-red').empty();

		$('input, select, textarea').attr('disabled', false);
		$('.select2').select2();
		$('.selectpicker').selectpicker('refresh');
	}

	function show_modal() {
		reset_form();
		save_method = 'add';

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'block',
			'cursor': 'pointer'
		}).text('Simpan');
	}

	function sunting(id) {
		reset_form();
		save_method = 'update';

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'block',
			'cursor': 'pointer'
		}).text('Sunting');

		$.ajax({
			url: '../../user/page/dokumen-masuk/get/' + id,
			type: 'GET',
			dataType: 'JSON',
			success: function (data) {
				$('#id_dok').val(data.id_dokumen);
				$('#no_dok').val(data.no_dokumen);
				$('#jns_dokumen').val(data.jns_dokumen);
				$('#disposisi').val(data.disposisi).change();
				$('#perihal').val(data.perihal);
				$('#dari').val(data.dari);
				$('#lampiran').val(data.lampiran);
				$('#kategori').val(data.kategori);
				$('#tgl_dokumen').val(data.tgl_dokumen);
				$('#tgl_diterima').val(data.tgl_diterima);
				$('#tgl_disposisi').val(data.tgl_disposisi);
				$('.custom-file-label').text(data.file_dokumen);
				$('#catatan').val(data.catatan);

				$('.selectpicker').selectpicker('refresh');
			}
		});
	}

	function view(id) {
		reset_form();

		$('#modal_form').modal('show');
		$('.btn_save').css({
			'display': 'none',
			'cursor': 'none'
		});

		$.ajax({
			url: '../../user/page/dokumen-masuk/get/' + id,
			type: 'GET',
			dataType: 'JSON',
			success: function (data) {
				$('#id_dok').val(data.id_dokumen).attr('disabled', true);
				$('#no_dok').val(data.no_dokumen).attr('disabled', true);
				$('#jns_dokumen').val(data.jns_dokumen).attr('disabled', true);
				$('#disposisi').val(data.disposisi).change().attr('disabled', true);
				$('#perihal').val(data.perihal).attr('disabled', true);
				$('#dari').val(data.dari).attr('disabled', true);
				$('#lampiran').val(data.lampiran).attr('disabled', true);
				$('#kategori').val(data.kategori).attr('disabled', true);
				$('#tgl_dokumen').val(data.tgl_dokumen).attr('disabled', true);
				$('#tgl_diterima').val(data.tgl_diterima).attr('disabled', true);
				$('#tgl_disposisi').val(data.tgl_disposisi).attr('disabled', true);
				$('#file').attr('disabled', true);
				$('.custom-file-label').text(data.file_dokumen);
				$('#catatan').val(data.catatan).attr('disabled', true);

				$('.selectpicker').selectpicker('refresh');
			}
		});
	}

	function hapus(id) {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data yang dihapus tidak bisa dikembalikan kembali!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Hapus',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: '../../user/page/dokumen-masuk/delete/' + id,
					type: "GET",
					dataType: "JSON",
					success: function (data) {
						Swal.fire({
							title: 'Sukses',
							text: 'Dokumen telah berhasil dihapus',
							icon: 'success',
							timer: 2000,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					}
				});
			}
		})
	}
