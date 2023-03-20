var FormControls = {
    init: function () {
        !function () {
            $("#employee_id").select2({allowClear: true, placeholder: "Nama Karyawan"}),
            $("#employee_id").on("select2:change", function () {
                e.element($(this))
            }),
            $("#jabatan").select2({allowClear: true, placeholder: "Pilih Posisi"}),
            $("#jabatan").on("select2:change", function () {
                e.element($(this))
            }),
			$("#province").select2({
                allowClear: true,
                placeholder: "Pilih Provinsi"
            }),$("#city").select2({
                allowClear: true,
                placeholder: "Pilih Kota"
           });
			$("#cash").select2({
                allowClear: true,
                placeholder: "Pilih Pembayaran"
           });
            $(".date").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "yyyy-mm-dd"
            }),
            $(".date1").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "yyyy-mm-dd"
            }),
            $("#division_id").select2({allowClear: true, placeholder: "Plih Divisi"}),
            $("#division_id").on("select2:change", function () {
                e.element($(this))
            }),
            $("#type1").on("change", function (e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === 'Annual Leave') {
                    $("#row_note").hide();
                } else if (option === 'Special Leave') {
                    $("#row_note").show();
                } else {
                    $("#row_note").show();
                }
            });
            $(".transfer1").hide();
			$("#cash").on("change", function (e) {
				e.preventDefault();
				var option = $('option:selected', this).val();
				if(option === ''){
                    $(".transfer").hide();
                    $(".transfer1").hide();
                }
                else if (option === 'Transfer') {
					$(".transfer").show();
                    $(".transfer1").show();
				}
				else if (option === 'Cash') {
					$(".transfer").hide();
                    $(".transfer1").hide();
				}
				else {
					$(".transfer").hide();
				}
			});
            
            $(".form-actions").click(function () {
                if (this.value == "Y") {
                    $(".m-form__btn").removeClass("d-none")
                } else {
                    $(".m-form__btn").addClass("d-none")
                }
            });
        }(),
        $("#m_form").validate({
            rules: {
                title: {
                    required: !0
                }
            }
        });
    }
};
jQuery(document).ready(function () {
    FormControls.init();
});

$(document).ready(function() {
    var max_fields = 10;                      
    var wrapper    = $(".tambah1");
    // console.log(wrapper);
    var add_button = $("#add_btn");           
    var x          = $(".tambah1").length;     
    $(add_button).click(function(e){
    e.preventDefault();
    if(x < max_fields){ 
    x++; //text box increment
    $(wrapper).append('<div id="AddKegiatan" class="tambah"><legend class="text-uppercase font-size-sm font-weight-bold">Detail Kegiatan / Pekerjaan</legend>\
                        <div class = "form-group row"><label class = "col-lg-3 col-form-label">Tanggal</label>\
                        <div class = "col-lg-7">\
                                <input\
                                    type        = "date"\
                                    id          = "date"\
                                    name        = "tgl_pekerjaan[]"\
                                    class       = "form-control date"\
                                    placeholder = "Tanggal Kegiatan / Pekerjaan">\
                                </div>\
                            </div>\
                        <div   class = "form-group row after-add-more">\
                        <label class = "col-lg-3 col-form-label">Nama Pekerjaan</label>\
                        <div   class = "col-lg-5">\
                                <input\
                                    type        = "text"\
                                    name        = "nama_pekerjaan[]"\
                                    class       = "form-control "\
                                    placeholder = "Nama Kegiatan / Pekerjaan"\
                                    ></div>\
                            </div>\
                            <div   class = "form-group row">\
                            <label class = "col-lg-3 col-form-label">Deskripsi</label>\
                            <div   class = "col-lg-7">\
                                    <input\
                                        type        = "text"\
                                        name        = "deskripsi[]"\
                                        class       = "form-control"\
                                        placeholder = "Deskripsi Kegiatan / Pekerjaan"\
                                        >\
                                    </div>\
                                </div>\
                                <div   class = "form-group row">\
                                <label class = "col-lg-3 col-form-label">Estimasi Biaya</label>\
                                <div   class = "col-lg-7">\
                                        <input\
                                            type        = "number"\
                                            name        = "est_biaya[]"\
                                            class       = "form-control"\
                                            step        = "any"\
                                            placeholder = "Estimasi Biaya Kegiatan / Pekerjaan"\
                                            >\
                                        </div>\
                                    </div>\
                        <button type  = "button" class = "btn btn-danger remove" id = "remove">\
                        <b><i   class = "fas fa-trash"></i></b></button></div>');
}
});
$(document).on("click","#remove", function(e){ //user click on remove text
    e.preventDefault(); 
    x--;
    $(this).parents(".tambah").remove();
})
});

function PrintCashAdv(ele){
    var getdata = ajax_data("finance/download/pdf_CashAdv/"+ $(ele).data("id_cash"));
	// console.log(getdata);
}

$(document).ready(function() {
    $('#berangkat, #pulang').on('change', function () {
        if ( ($("#berangkat").val() != "") && ($("#pulang").val() != "")) {
            var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
            var firstDate = new Date($("#berangkat").val());
            var secondDate = new Date($("#pulang").val());
            var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
            $("#est_waktu").val(diffDays + " hari") ;
        }
    });
});


$("#province").on("change", function (e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    $("#city").prop("disabled", false);
    $('#city option').remove();
    $('#country option').remove();
    if (option === '') {
        $("#city").prop("disabled", true);
        $("#country").prop("disabled", true);
    } else {
        getKota(option);
    }
});

function getKota(option) {
    $(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'api/location/get_city',
            type: "POST",
            dataType: "json",
            data: {
                id: option
            },
            cache: false,
            success: function (response) {
                var html = '';
                var i;
                $('#city').append('<option value="" selected disabled>Pilih Kota</option>');
                for (i = 0; i < response.length; i++) {
                    $('#city').append(
                        '<option value="' + response[i].id + '">' + response[i].kota + '</option>'
                    );
                }

            }
        });
    });
}


function hitungSelisihHari(tgl1, tgl2){
	var tgl1 = $("#berangkat").val();
	var tgl2 = $("#pulang").val();
	console.log(tgl1);
    // varibel miliday sebagai pembagi untuk menghasilkan hari
    var miliday = 24 * 60 * 60 * 1000;
    //buat object Date
    var tanggal1 = new Date(tgl1);
    var tanggal2 = new Date(tgl2);
    // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    var tglPertama = Date.parse(tanggal1);
    var tglKedua = Date.parse(tanggal2);
    var selisih = (tglKedua - tglPertama) / miliday;
    return selisih;
    }

function hitung_selisih(){
    //ambil tanggal berangkat dan kembali
    var berangkat = document.getElementsById("berangkat");
    var kembali = document.getElementsById("pulang");
    // bangun string untuk tanggal "tahun bulan tanggal"
    var tgl_berangkat = tgl_kembali ="";
    for(var i = 0; i < berangkat.length; i++){
        tgl_berangkat += berangkat[i].value+" ";
        tgl_kembali += kembali[i].value+" ";
        }
    var selisih = hitungSelisihHari(tgl_berangkat,tgl_kembali);
    //isikan hasil pada input dengan id = hasil
    document.getElementById("est_waktu").value = selisih;
    }