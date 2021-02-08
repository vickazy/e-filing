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
}

function sunting(id) {
	reset_form();
	save_method = 'update';

	$('#modal_form').modal('show');
	$('.btn_save').text('Sunting');

	$.ajax({
		url: '../../admin/page/jabatan/get/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			console.log(data);
			$('#id_jabatan').val(data.id_jabatan);
			$('#nm_jabatan').val(data.nm_jabatan);
		}
	});
}

function save_form() {
	var url = '';
	if (save_method == 'add') url = '../../admin/page/jabatan/insert';
	else url = '../../admin/page/jabatan/update';

	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'JSON',
		data: $('#form').serialize(),
		success: function (data) {
			if (data.status === true) {
				if (data.icon == 'warning') {
					Swal.fire({
						title: data.title,
						text: data.text,
						icon: data.icon
					});
				} else {
					Swal.fire({
						title: data.title,
						text: data.text,
						icon: data.icon,
						timer: 2000,
						showConfirmButton: false,
						allowOutsideClick: false
					}).then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							$('#modal_form').modal('hide');
							location.reload();
						}
					});
				}
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
				url: '../../admin/page/jabatan/delete/' + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					Swal.fire({
						title: 'Berhasil',
						text: 'Nama jabatan telah dihapus',
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
