var save_method = '';

$(document).ready(function () {
	//datatables
	table = $('#table').DataTable({
		'processing': true,
		'serverSide': true,
		'order': [],
		'ajax': {
			'url': '../../admin/page/unit-tujuan/list',
			'type': 'POST'
		},
		'ordering': false
	});
});

$('input[type="text"], textarea').on('keypress', function () {
	$(this).removeClass('is-invalid');
	$(this).next().removeClass('invalid-feedback').empty();
	$(this).css('text-transform', 'uppercase');
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
		url: '../../admin/page/unit-tujuan/get/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			$('#no').val(data.no);
			$('#kd_unit').val(data.kd_unit);
			$('#nm_unit').val(data.nm_unit);
		}
	});
}

function save_form() {
	var url = '';
	if (save_method == 'add') url = '../../admin/page/unit-tujuan/insert';
	else url = '../../admin/page/unit-tujuan/update';

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
				url: '../../admin/page/unit-tujuan/delete/' + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					Swal.fire({
						title: 'Berhasil',
						text: 'Unit tujuan telah dihapus',
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
