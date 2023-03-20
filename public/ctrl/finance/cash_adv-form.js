var FormControls = {
    init: function() {
        ! function() {
            $("#employee_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#employee_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#division_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#division_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#jabatan").select2({ allowClear: true, placeholder: "Pilih Posisi" }),
                $("#jabatan").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#province").select2({
                    allowClear: true,
                    placeholder: "Pilih Provinsi"
                }), $("#city").select2({
                    allowClear: true,
                    placeholder: "Pilih Kota"
                });
            $("#cash").select2({
                allowClear: true,
                placeholder: "Pilih Pembayaran"
            });
            $("#type_cash").select2({
                allowClear: true,
                placeholder: "Pilih Tipe Cash Advance"
            });

            $(".date").datepicker({
                    autoclose: !0,
                    format: "dd-MM-yyyy"
                }),
                $(".tanggal_pekerjaan").datepicker({
                    autoclose: !0,
                    format: "dd-MM-yyyy"
                }),
                $(".date1").datepicker({
                    autoclose: !0,
                    format: "dd-MM-yyyy"
                }),
                $(".transfer1").hide();
            $("#cash").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".transfer").hide();
                    $(".transfer1").hide();
                } else if (option === 'Transfer') {
                    $(".transfer").show();
                    $(".transfer1").show();
                } else if (option === 'Cash') {
                    $(".transfer").hide();
                    $(".transfer1").hide();
                } else {
                    $(".transfer").hide();
                }
            });

            $(".type_cash").val() == "dinas" ? $(".row_tujuanluar").show() : $(".row_tujuanLuar").hide();
            $(".type_cash").val() == "dinas" ? $(".row_tujuan").hide() : $(".row_tujuan").show();
            $(".type_cash").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".row_tujuanLuar").hide();
                    $(".row_tujuan").show();
                } else if (option === 'blank') {
                    $(".row_tujuan").show();
                    $(".row_tujuanLuar").hide();
                } else if (option === 'dinas') {
                    $(".row_tujuan").hide();
                    $(".row_tujuanLuar").show();
                } else {
                    $(".row_tujuanLuar").hide();
                }
            });

            $(".form-actions").click(function() {
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
jQuery(document).ready(function() {
    FormControls.init();
});

function add_btn(ele) {
    if ($(ele).data('type') == "delete") {
        var a = $(ele).data('equ');
        $(".form_" + $(ele).data('equ')).remove();
    } else {
        var n_equ = $(".kegiatans").length;
        n_equ++;
        var getdata = ajax_data('finance/cash_advance/add_kegiatan', "&n_equ=" + n_equ +
            "&type=" + $(ele).data('type'));
        $(".tambah1").before(getdata);
    }
}


function add_btn_blank(ele) {
    if ($(ele).data('type') == "delete_blank") {
        var a = $(ele).data('equ');
        $(".formBlank_" + $(ele).data('equ')).remove();
    } else {
        var n_equ = $(".blank_details").length;
        n_equ++;
        var getdata = ajax_data('finance/cash_advance/addBlank_kegiatan', "&n_equ=" + n_equ +
            "&type=" + $(ele).data('type'));
        $(".tambah_blank").before(getdata);
    }
}


function addKegiatan(ele) {
    var equ = $(ele).data('equ');
    var type = $(ele).data('type');
    if (type == "delete") {
        $(".form_" + equ).remove();
    } else {
        var n_equ = $(".kegiatans").length;
        n_equ++;
        var equ = ajax_data("finance/cash_advance/add_kegiatan",
            "&n_equ=" + n_equ);
        $("#tambah_act").before(equ);
    }
}

function addKegiatanBlank(ele) {
    var equ = $(ele).data('equ');
    var type = $(ele).data('type');
    if (type == "delete") {
        $(".formBlank_" + equ).remove();
    } else {
        var n_equ = $(".blank_details").length;
        n_equ++;
        var equ = ajax_data("finance/cash_advance/addBlank_kegiatan",
            "&n_equ=" + n_equ);
        $("#tambahBlank_act").before(equ);
    }
}



function PrintCashAdv(ele) {
    var getdata = ajax_data("finance/download/pdf_CashAdv/" + $(ele).data("id_cash"));
}


function removeAsset(ele) {
    var n_equ = $(".form-item1").length;
    var equ = $("#removes").data('equ');
    if ($(ele).data("type") == "remove_asset_edit1") {
        var getdata = ajax_data("finance/cash_advance/remove", "&id=" + $(ele).data("id"));
        $(".row_" + $(ele).data("id")).remove();
    } else {
        $(".row_" + ele).remove();
    }
}

function removeAssetBlank(ele) {
    var n_equ = $(".form-itemBlank").length;
    var equ = $(ele).data('equ');
    console.log(equ);
    if ($(ele).data("type") == "remove_asset_edit1") {
        var getdata = ajax_data("finance/cash_advance/remove", "&id=" + $(ele).data("id"));
        $(".rowBlank_" + equ).remove();
    } else {
        $(".rowBlank_" + ele).remove();
    }
}


$(document).ready(function() {
    $('#berangkat, #pulang').on('change', function() {
        if (($("#berangkat").val() != "") && ($("#pulang").val() != "")) {
            var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            var firstDate = new Date($("#berangkat").val());
            var secondDate = new Date($("#pulang").val());
            var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
            if (diffDays == 0) {
                $("#est_waktu").val("1 HARI");
            } else {
                $("#est_waktu").val(diffDays + " hari");
            }
        }
    });
});

$("#province").on("change", function(e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    var fedit = $('#form_edit').val();
    $("#city").prop("disabled", false);
    $('#city option').remove();
    $('#country option').remove();
    if (option === '') {
        $("#city").prop("disabled", true);
        $("#country").prop("disabled", true);
    } else if (fedit === "Edit") {
        getKotaEdit(option);
    } else {
        getKota(option);
    }
});

function getKota(option) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'api/location/get_city',
            type: "POST",
            dataType: "json",
            data: { id: option },
            cache: false,
            success: function(response) {
                var html = '';
                var i;
                $('#city').append('<option value="" selected disabled>Pilih Kota</option>');
                for (i = 0; i < response.length; i++) {
                    $('#city').append('<option value="' + response[i].id + '">' + response[i].kota + '</option>');
                }

            }
        });
    });
}

function getKotaEdit(option) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'api/location/get_city',
            type: "POST",
            dataType: "json",
            data: { id: option },
            cache: false,
            success: function(response) {
                var html = '';
                var i;
                $('#city').append('<option value="{{$cash->des_kota}}" selected>Pilih Kota</option>');
                for (i = 0; i < response.length; i++) {
                    $('#city').append('<option value="' + response[i].id + '">' + response[i].kota + '</option>');
                }
            }
        });
    });
}

$("#divisi").val('');
$("#posisi").val('');
$("#emp").on("change", function(e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    var type = $('#payment option:selected').val();
    if (option === '') {
        $("#divisi").prop("disabled", true);
    } else {
        getValue(option);
    }
});

function getValue(option, type) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'finance/cash_advance/get_value',
            type: "POST",
            dataType: "json",
            data: { id: option },
            success: function(response) {
                $("#emp").val(response.id).trigger('change');
                $("#divisi").val(response.division_name);
                $("#divisi_edit").val(response.division_name);
                $("#divisi2").val(response.division_id);
                $("#div_hide").val(response.division_id);
                $("#posisi").val(response.position);
                $("#posisi_edit").val(response.position);
            }
        });
    });
}

function Ajukan_app(ele) {
    window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/ajukan';
    $('#ajukan_sub').removeAttr("disabled");
}

if ($('#app_spv').val() == "Done") {
    $('#appr_sub').prop("disabled", true);
} else if ($('#app_hrd').val() == "Done") {
    $('#appr_sub').prop("disabled", true);
} else if ($('#app_finance').val() == "Done") {
    $('#appr_sub').prop("disabled", true);
}

function Appr_app(ele) {
    if ($(ele).data('type') == "approval") {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/approve';
    } else {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/reject';
    }
}

function hr_appr(ele) {
    if ($(ele).data('type') == "approval") {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + "/" + $(ele).data('type') + '/approve_hrd';
    } else {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + "/" + $(ele).data('type') + '/approve_hrd';
    }
}



function Manage_approve(ele) {
    if ($(ele).data('type') == "approve") {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + "/" + $(ele).data('usr_id') + '/manage_approve';
        $('#manage_app').removeAttr("disabled");
    } else {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + "/" + $(ele).data('usr_id') + '/manage_reject';
        $('#rem_sub').removeAttr("disabled");
    }
}

function Finance_approve(ele) {
    if ($(ele).data('type') == "approve") {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/finance_app';
        $('#finance_app').removeAttr("disabled");
    } else {
        window.location.href = main_url + 'finance/cash_advance/' + $(ele).data('id') + '/finance_btl';
        $('#finance_app').removeAttr("disabled");
    }
}

if ($("#hitungs").val() != null) {
    var t = $("#totalss").val();
    var sum = 0;
    $(".amount").each(function() {
        var num = $(this).val().replace(',', '');
        if (num != 0 || num != '') {
            sum += parseFloat(num);
        }
    });

    var sisa = parseInt(sum) - parseInt(t);
    $("#sisas").val(sisa);
    $("#totals").val(sum);

}
$(".amount").keyup(function() {
    total_amount();
})

var t = $("#totalss").val();
var total_amount = function() {
    var sum = 0;
    var ss = 0;
    $(".amount").each(function() {
        var num = $(this).val().replace(',', '');
        if (num != 0 || num != '') {
            sum += parseFloat(num);

        }
    });

    var sisa = parseInt(sum) - parseInt(t);
    $("#sisas").val(sisa);
    $("#totals").val(sum);
}



function Hitungs(ele) {
    var harga = $("#set_nominal_" + ele).val();
    var qty = $("#set_qty" + ele).val();
    var sub = parseInt(harga * qty);
    $(".hasil_qty_" + ele).val(sub);
    console.log(ele);
}


function detail_set(ele) {
    if ($(ele).data('type') == "shows_detail") {
        var getdata = ajax_data('finance/cash_advance/show_detail',
            "&id_dtl=" + $(ele).data('id_dtl'))
        $(".this_place").html(getdata);

    } else {
        var getdata = ajax_data('finance/cash_advance/edit_detail',
            "&id_dtl=" + $(ele).data('id_dtl'))
        $(".this_place").html(getdata);
    }
}


function hapus_details(ele) {
    console.log(ele);
    if ($(ele).data('type') == "hapus_detail_settlement") {
        var hapus_data = ajax_data('finance/cash_advance/delete_detailsets', "&id_set=" + $(ele).data('id_set'));
        $(".foreach_details_" + $(ele).data("id_set")).remove();
    } else {
        var n_equ = $(".detail_pertama").length;
        $(".next_detail_" + ele).remove();
    }
}

function tambah_details(ele) {
    var n_equ = $(".detail_pertama").length;
    n_equ++;
    var equ = ajax_data("finance/cash_advance/add_detailset", "&n_equ=" + n_equ +
        "&id_dtl=" + $(ele).data('id_dtl') + "&type=" + $(ele).data('type'));
    $(".tambah_details").before(equ);
}