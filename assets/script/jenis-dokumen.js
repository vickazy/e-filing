var save_method = '';

$('#table').DataTable({
	'ordering': false
});

$('input[type="text"], textarea').on('keypress', function () {
	$(this).removeClass('is-invalid');
	$(this).next().removeClass('invalid-feedback').empty();
});

function reset_form() {
	$('#form')[0].reset();
	$('.form-control').removeClass('is-invalid');
	$('.text-help').removeClass('invalid-feedback').empty();
}

function show_modal() {
	reset_form();
	save_method = 'add';

	$('#modal_form').modal('show');
	$('.btn_save').text('Simpan');
}

function sunting(id) {
	reset_form();
	save_method = 'update';

	$('#modal_form').modal('show');
	$('.btn_save').text('Sunting');

	$.ajax({
		url: '../../admin/page/jenis-dokumen/get/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			console.log(data);
			$('#id_dokumen').val(data.id_jns_dokumen);
			$('#jns_dokumen').val(data.jns_dokumen);
			$('#ket_dokumen').val(data.keterangan);
		}
	});
}

function save_form() {
	var url = '';
	if (save_method == 'add') url = '../../admin/page/jenis-dokumen/insert';
	else url = '../../admin/page/jenis-dokumen/update';

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'JSON',
		data: $('#form').serialize(),
		success: function (data) {
			if (data.status) {
				Swal.fire({
					title: 'Berhasil',
					text: 'Jenis dokumen berhasil tersimpan',
					icon: 'success',
					timer: 2000,
					showConfirmButton: false,
					allowOutsideClick: false
				}).then((result) => {
					if (result.dismiss === Swal.DismissReason.timer) {
						$('#modal_form').modal('hide');
						location.reload();
					}
				});
			} else {
				for (var i = 0; i < data.inputerror.length; i++) {
					$('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
					$('[name="' + data.inputerror[i] + '"]').next().addClass('invalid-feedback').text(data.error[i]);
				}
			}
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
				url: '../../admin/page/jenis-dokumen/delete/' + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					Swal.fire({
						title: 'Berhasil',
						text: 'Jenis dokumen telah dihapus',
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
