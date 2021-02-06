$(document).ready(function () {
	$('#tbl_dok_masuk, #tbl_dok_keluar').DataTable({
		'ordering': false,
		'searching': false
	});

	$('.btn_show').click(function () {
		$.ajax({
			url: '../../user/cari_dokumen/get_list',
			type: 'POST',
			dataType: 'JSON',
			data: $('#form').serialize(),
			success: function (respon) {
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
