var save_method = "";

$(document).ready(function () {
	//datatables
	table = $("#table").DataTable({
		processing: true,
		serverSide: true,
		order: [],
		ajax: {
			url: "../../user/page/dokumen-keluar/list",
			type: "POST",
		},
		ordering: false,
	});

	$('input[type="file"]').on("change", function () {
		//get the file name
		var file = $(this).val();
		var fileName = file.replace("C:\\fakepath\\", "");
		//replace the "Choose a file" label
		$(this).next(".custom-file-label").html(fileName);
	});

	/** ubah attribut field berdasarkan jenis dokumen **/
	// $('#jns_dokumen').on('change', function () {
	// 	if ($(this).val() == 3) {
	// 		$('#tujuan_lain').attr('disabled', false);
	// 		$('#li_tujuan').attr('disabled', true);
	// 		$('#li_tujuan-feedback').empty();
	// 		$('#tujuan_lain-feedback').empty();
	// 	} else {
	// 		$('#tujuan_lain').attr('disabled', false);
	// 		$('#li_tujuan').attr('disabled', false);
	// 		$('#li_tujuan-feedback').empty();
	// 		$('#tujuan_lain-feedback').empty();
	// 	}
	// });

	$("#perihal").on("keypress", function () {
		$(this).css("text-transform", "uppercase");
	});

	$("#form").on("change", 'input[type="file"]', function () {
		//get the file name
		var file = $(this).val();
		var fileName = file.replace("C:\\fakepath\\", "");
		//replace the "Choose a file" label
		$(this).next(".custom-file-label").html(fileName);

		var size = $(this)[0].files[0].size / 1024;
		console.log("size file :" + size);

		if ($(this)[0].files[0].type != "application/pdf") {
			Swal.fire({
				title: "Oops!",
				icon: "warning",
				text: "Format file upload tidak valid!",
				allowOutsideClick: false,
			});
			$(this).next(".custom-file-label").html("Choose file");
			$(this).val("");
		} else {
			if (size > (1024 * 20)) {
				Swal.fire({
					title: "Oops!",
					icon: "warning",
					text: "Ukuran file melebihi batas, maksimal 20 MB!",
					allowOutsideClick: false,
				});
				$(this).next(".custom-file-label").html("Choose file");
				$(this).val("");
			}
		}
	});

	// save form
	$("#form").submit(function (evt) {
		evt.preventDefault();

		var url = "";
		if (save_method == "add") url = "../../user/page/dokumen-keluar/insert";
		else url = "../../user/page/dokumen-keluar/update";

		$.ajax({
			url: url,
			type: "POST",
			dataType: "JSON",
			data: new FormData(this),
			processData: false,
			contentType: false,
			cache: false,
			async: false,
			success: function (data) {
				if (data.status === true) {
					// Swal.fire({
					// 	title: data.title,
					// 	text: data.text,
					// 	icon: data.icon,
					// 	timer: 2000,
					// 	showConfirmButton: false,
					// 	allowOutsideClick: false
					// }).then((result) => {
					// 	if (result.dismiss === Swal.DismissReason.timer) {
					// 		location.reload();
					// 	}
					// });

					Swal.fire({
						title: data.title,
						text: data.text,
						icon: data.icon,
						allowOutsideClick: false,
					}).then((result) => {
						// Reload the Page
						location.reload();
					});
				} else {
					$(".help-text").removeClass("text-red").empty();
					for (var i = 0; i < data.inputerror.length; i++) {
						$("#" + data.inputerror[i] + "-feedback")
							.addClass("text-red")
							.text(data.error[i]);
					}
				}
			},
		});
	});
});

function reset_form() {
	$("#form")[0].reset();
	$(".custom-file-label").text("Choose file");
	$(".help-text").removeClass("text-red").empty();

	$("input, select, textarea").attr("disabled", false);
	$(".select2").select2();
	$(".selectpicker").selectpicker("refresh");
}

function show_modal() {
	reset_form();
	save_method = "add";

	$("#modal_form").modal("show");
	$(".btn_save")
		.css({
			display: "block",
			cursor: "pointer",
		})
		.text("Simpan");

	/** disabled attribute field **/
	// $('#tujuan_lain').attr('disabled', true);
	// $('#li_tujuan').attr('disabled', true);
}

function sunting(id) {
	reset_form();
	save_method = "update";

	$("#modal_form").modal("show");
	$(".btn_save")
		.css({
			display: "block",
			cursor: "pointer",
		})
		.text("Sunting");

	$.ajax({
		url: "../../user/page/dokumen-keluar/get/" + id,
		type: "GET",
		dataType: "JSON",
		success: function (data) {
			$("#id_dok").val(data.id_dokumen);
			$("#jns_dokumen").val(data.jns_dokumen);

			/** tampilkan data unit tujuan berdasarkan jenis dokumen
			if (data.jns_dokumen != 3) {
				$('#tujuan_lain').attr('disabled', true);
				$('#li_tujuan').val(data.unit_tujuan).change();
			} else {
				$('#li_tujuan').attr('disabled', true);
				$('#tujuan_lain').val(data.unit_tujuan);
			}
			**/

			var tujuan = data.unit_tujuan;
			for (let i = 0; i < tujuan.length; i++) {
				if (tujuan[i].search("-") != -1) {
					$("#li_tujuan").val(tujuan).change();
				} else {
					$("#tujuan_lain").val(tujuan[0]);
				}
			}

			$("#perihal").val(data.perihal);
			$("#pembuat").val(data.pembuat);
			$("#lampiran").val(data.lampiran);
			$("#kategori").val(data.kategori);
			$("#sts_dokumen").val(data.sts_dokumen);
			$("#tgl_dokumen").val(data.tgl_dokumen);
			$(".custom-file-label").text(data.file_dokumen);
			$("#catatan").val(data.catatan);

			$(".selectpicker").selectpicker("refresh");
		},
	});
}

function view(id) {
	reset_form();

	$("#modal_form").modal("show");
	$(".btn_save").css({
		display: "none",
		cursor: "none",
	});

	$.ajax({
		url: "../../user/page/dokumen-keluar/get/" + id,
		type: "GET",
		dataType: "JSON",
		success: function (data) {
			$("#id_dok").val(data.id_dokumen).attr("disabled", true);
			$("#jns_dokumen").val(data.jns_dokumen).attr("disabled", true);

			/** tampilkan data unit tujuan berdasarkan jenis dokumen
			if (data.jns_dokumen != 3) {
				$('#tujuan_lain').attr('disabled', true);
				$('#li_tujuan').val(data.unit_tujuan).change().attr('disabled', true);
			} else {
				$('#li_tujuan').attr('disabled', true);
				$('#tujuan_lain').val(data.unit_tujuan).attr('disabled', true);
			}
			**/

			var tujuan = data.unit_tujuan;
			for (let i = 0; i < tujuan.length; i++) {
				if (tujuan[i].search("-") != -1) {
					$("#tujuan_lain").attr("disabled", true);
					$("#li_tujuan").val(tujuan).change().attr("disabled", true);
				} else {
					$("#li_tujuan").attr("disabled", true);
					$("#tujuan_lain").val(tujuan[i]).attr("disabled", true);
				}
			}

			$("#perihal").val(data.perihal).attr("disabled", true);
			$("#pembuat").val(data.pembuat).attr("disabled", true);
			$("#lampiran").val(data.lampiran).attr("disabled", true);
			$("#kategori").val(data.kategori).attr("disabled", true);
			$("#sts_dokumen").val(data.sts_dokumen).attr("disabled", true);
			$("#tgl_dokumen").val(data.tgl_dokumen).attr("disabled", true);
			$("#file").attr("disabled", true);
			$(".custom-file-label").text(data.file_dokumen);
			$("#catatan").val(data.catatan).attr("disabled", true);

			$(".selectpicker").selectpicker("refresh");
		},
	});
}

function hapus(id) {
	Swal.fire({
		title: "Apakah anda yakin?",
		text: "Data yang dihapus tidak bisa dikembalikan kembali!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#d33",
		cancelButtonColor: "#3085d6",
		confirmButtonText: "Hapus",
		cancelButtonText: "Tidak",
		allowOutsideClick: false,
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: "../../user/page/dokumen-keluar/delete/" + id,
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					Swal.fire({
						title: "Sukses",
						text: "Dokumen telah berhasil dihapus",
						icon: "success",
						timer: 2000,
						showConfirmButton: false,
						allowOutsideClick: false,
					}).then((result) => {
						if (result.dismiss === Swal.DismissReason.timer) {
							location.reload();
						}
					});
				},
			});
		}
	});
}
