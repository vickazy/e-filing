$(document).ready(function () {
	var method = '';
	$('#tipe_dokumen').on('change', function () {
		if ($(this).val() == 'Dokumen Masuk') method = '../../user/cari_dokumen/get_list_dok_masuk';
		else method = '../../user/cari_dokumen/get_list_dok_keluar';
	});

	$('.btn_show').click(function () {
		if ($('#tipe_dokumen').val() != '') {
			$('#tipe_dokumen-feedback').removeClass('text-red').empty();

			$.ajax({
				url: method,
				type: 'post',
				data: $('#form').serialize(),
				dataType: 'json',
				success: function (respon) {
					if ($('#tipe_dokumen').val() == 'Dokumen Masuk') {
						$('.show_dok_masuk').css('display', 'block');
						$('.show_dok_keluar').css('display', 'none');

						var table = $('#tbl_dok_masuk').DataTable({
							'retrieve': true,
							'data': respon.data,
							'columns': [
								{ 'data': 'no' },
								{ 'data': 'kategori' },
								{ 'data': 'detail' },
								{ 'data': 'status' },
								{ 'data': 'tgl_terima' }
							],
							'ordering': false
						});

						table.clear().draw();
   					table.rows.add(respon.data); // Add new data
   					table.columns.adjust().draw(); // Redraw the DataTable
					} else {
						$('.show_dok_masuk').css('display', 'none');
						$('.show_dok_keluar').css('display', 'block');

						var table = $('#tbl_dok_keluar').DataTable({
							'retrieve': true,
							'data': respon.data,
							'columns': [
								{ 'data': 'no' },
								{ 'data': 'kategori' },
								{ 'data': 'detail' },
								{ 'data': 'unit_tujuan' },
								{ 'data': 'tgl_dokumen' },
								{ 'data': 'status' },
							],
							'ordering': false
						});

						table.clear().draw();
   					table.rows.add(respon.data); // Add new data
   					table.columns.adjust().draw(); // Redraw the DataTable
					}
				}
			});
		} else {
			$('#tipe_dokumen-feedback').addClass('text-red').text('Bagian ini harus diisi');
		}
	});
});
