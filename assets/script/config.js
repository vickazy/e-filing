var save_method = '';

$(document).ready(function () {
	$('tbody').on('click', 'input[type="checkbox"]', function () {
		const id = $(this).data('id');

		$.ajax({
			url: '../../admin/page/config/upd_stat',
			type: "POST",
			data: {
				id: id
			},
			success: function () {
				location.reload();
			}
		});
	});
});

function reset_form() {
	$(' #form')[0].reset();
	$('.help-text').removeClass('text-red').empty();

	$('.selectpicker').selectpicker('refresh');
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
		url: '../../admin/page/config/get/' + id,
		type: 'GET',
		dataType: 'JSON',
		success: function (data) {
			$('#id_config').val(data.no);
			$('#thn_dok').val(data.thn_dokumen);
			$('#unit_group').val(data.nm_group);

			$('.selectpicker').selectpicker('refresh');
		}
	});
}

function save_form() {
	var url = '';
	if (save_method == 'add') url = '../../admin/page/config/insert';
	else url = '../../admin/page/config/update';
	$.ajax({
		url: url,
		type: 'POST',
		dataType: 'JSON',
		data: $('#form').serialize(),
		success: function (data) {
			if (data.status === true) {
				Swal.fire({
					title: 'Success',
					text: 'Data berhasil tersimpan',
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
					$('#' + data.inputerror[i] + '-feedback').addClass('text-red').text(data.error[i]);
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
				url: '../../admin/page/config/delete/' + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					if (data.status === true) {
						Swal.fire({
							title: 'Success',
							text: 'Data telah berhasil dihapus',
							icon: 'success',
							timer: 2000,
							showConfirmButton: false,
							allowOutsideClick: false
						}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.reload();
							}
						});
					} else {
						Swal.fire({
							title: 'Oops!',
							text: 'Data tidak dapat dihapus!',
							icon: 'error'
						});
					}
				}
			});
		}
	})
}
