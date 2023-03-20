var FormControls = {
    init: function() {
        ! function() {
            $("#spv_id").select2({
                    allowClear: true,
                    placeholder: "Supervisor*"
                }), $("#spv_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#division_id").select2({
                    allowClear: true,
                    placeholder: "Role*"
                }), $("#division_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#genders").select2({
                    allowClear: true,
                    placeholder: "Gender*"
                }),
                $("#statuses").select2({
                    allowClear: true,
                    placeholder: "Pilih Status*"
                }),                
                $(".division_id").select2({
                    allowClear: true,
                    placeholder: "Role*"
                }), $(".division_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#division_name").select2({
                    allowClear: true,
                    placeholder: "Division*"
                }), $("#division_name").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#emp_status").select2({
                    allowClear: true,
                    placeholder: "Status Karyawan*"
                }), $("#emp_status").on("select2:change", function() {
                    e.element($(this))
                }),
                $(".row_resign").hide();
            $(".emp_status").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".row_resign").hide();
                } else if (option === 'Active') {
                    $(".row_resign").hide();
                    $(".row_resign2").hide();
                } else if (option === 'In Active') {
                    $(".row_resign").show();
                    $(".row_resign2").show();
                } else {
                    $(".transfer").hide();
                }
            });
            $(".emp_date").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                }),
                $("#cabang_id").select2({
                    allowClear: true,
                    placeholder: "Cabang*"
                }), $("#cabang_id").on("select2:change", function() {
                    e.element($(this))
                }),

                $(".form-actions").click(function() {
                    if (this.value == "Y") {
                        $(".m-form__btn").removeClass("d-none")
                    } else {
                        $(".m-form__btn").addClass("d-none")
                    }
                });
        }(), $("#m_form").validate({
            rules: {
                title: {
                    required: !0
                }
            },
        })
    }
};
jQuery(document).ready(function() {
    FormControls.init();
});

$(document).ready(function() {
    if (($("#tgl_join").val() != "") && ($("#now_dates").val() != "")) {
        var firstDate = new Date($("#tgl_join").val());
        var secondDate = new Date($("#now_dates").val());
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
        var months = Math.round(diffDays / 31);
        var years = Math.floor(months / 12);

        if (months == 0 && years == 0) {
            $("#count_days").html(diffDays + " Hari");
        } else if (years == 0 && months != 0) {
            $("#count_days").html(months + " Bulan");
        } else if (years != 0) {
            $("#count_days").html(years + " Tahun" + " " + months + " Bulan");
        } else {
            $("#count_days").html(diffDays + " Hari" + " " + months + " Bulan" + " " + years + " Tahun");
        }
    }
});


function addAsset() {
    var n_equ = $(".form-item").length;
    console.log(n_equ);
    n_equ++;
    var equ = ajax_data("hrm/employee/add_asset", "&n_equ=" + n_equ);
    var as = $("#assets_office").html();
    $("#isian_baru").before(equ);
}


function removeAsset(ele) {
    var n_equ = $(".form-item").length;

    console.log(n_equ);
    if ($(ele).data("type") == "remove_asset_edit1") {
        var getdata = ajax_data("hrm/employee/del_asset", "&id=" + $(ele).data("id"));
        $(".row_" + n_equ).remove();
    } else {
        $(".row_" + ele).remove();
    }
    // console.log(n_equ);
}

function add_dokumen() {
    var n_equ = $(".forms_doks").length;
    console.log(n_equ);
    n_equ++;
    var equ = ajax_data("hrm/employee/add_dokumen", "&n_equ=" + n_equ);
    $("#point_dokumen").before(equ);
}


function hapus_dokumen(ele) {
    $(".adds_" + ele).remove();
}
