var save_method = '';

$('#table').DataTable({
	'ordering': false
});

$('input[type="text"]').on('keypress', function () {
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
	$('.selectpicker').selectpicker('refresh');
	$('.help-text').removeClass('text-red').empty();
}

function sunting(id) {
	reset_form();
	save_method = 'update';

	$('#modal_form').modal('show');
	$('.btn_save').text('Sunting');

	$.ajax({
		url: '../../admin/page/pegawai/get/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			$('#id_pegawai').val(data.id_pegawai);
			$('#nm_pegawai').val(data.nm_pegawai);
			$('#li_jabatan').selectpicker('val', data.id_jabatan);
		}
	});
}

function save_form() {
	var url = '';
	if (save_method == 'add') url = '../../admin/page/pegawai/insert';
	else url = '../../admin/page/pegawai/update';

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'JSON',
		data: $('#form').serialize(),
		success: function (data) {
			$('.help-text').removeClass('text-red').empty();
			if (data.status === true) {
				Swal.fire({
					title: 'Sukses',
					text: 'Nama pegawai telah berhasil tersimpan',
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
					$('#'+data.inputerror[i]+'-feedback').addClass('text-red').text(data.error[i]);
					// $('[name="' + data.inputerror[i] + '"]').next().addClass('invalid-feedback').text(data.error[i]);
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
				url: '../../admin/page/pegawai/delete/' + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					Swal.fire({
						title: 'Berhasil',
						text: 'Nama pegawai telah dihapus',
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
