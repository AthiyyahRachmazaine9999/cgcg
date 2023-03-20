var FormControls = {
    init: function() {
        ! function() {
            $("#employee_id").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#employee_id").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#div").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#div").on("select2:change", function() {
                    e.element($(this))
                }),
                $(".approval").select2({
                    allowClear: true,
                    placeholder: "*"
                }),
                $("#choose_one").select2({
                    allowClear: true,
                    placeholder: "*"
                }),

                $("#transport").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#transport").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#Rtransport").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#Rtransport").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#Dtime").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#Dtime").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#akomodasi").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#akomodasi").on("select2:change", function() {
                    e.element($(this))
                }),

                $("#province").select2({
                    allowClear: true,
                    placeholder: "Pilih Provinsi"
                }), $("#city").select2({
                    allowClear: true,
                    placeholder: "Pilih Kota"
                });
            $('#choose_one').val('').trigger("change");
            $("#app_manage").val('').trigger("change");
            $("#app_hrd").val('').trigger("change");
            $("#app_finance").val('').trigger("change");

            $(".row_Manage").hide();
            $(".row_HRD").hide();
            $(".row_Finance").hide();
            $(".row_spv").hide();
            $("#choose_one").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".row_Manage").hide();
                    $(".row_HRD").hide();
                    $(".row_Finance").hide();
                    $(".row_spv").hide();

                    $("#app_manage").val('').trigger("change");
                    $("#app_hrd").val('').trigger("change");
                    $("#app_finance").val('').trigger("change");
                } else if (option === 'Supervisor') {
                    $(".row_Manage").hide();
                    $(".row_HRD").hide();
                    $(".row_Finance").hide();
                    $(".row_spv").show();

                    $("#app_manage").val('').trigger("change");
                    $("#app_hrd").val('').trigger("change");
                    $("#app_finance").val('').trigger("change");
                } else if (option === 'Finance') {
                    $(".row_Manage").hide();
                    $(".row_HRD").hide();
                    $(".row_Finance").show();
                    $(".row_spv").hide();

                    $("#app_manage").val('').trigger("change");
                    $("#app_hrd").val('').trigger("change");
                    $("#app_finance").val('').trigger("change");
                } else if (option === 'HRD') {
                    $(".row_Manage").hide();
                    $(".row_HRD").show();
                    $(".row_Finance").hide();
                    $(".row_spv").hide();

                    $("#app_manage").val('').trigger("change");
                    $("#app_hrd").val('').trigger("change");
                    $("#app_finance").val('').trigger("change");
                } else {
                    $(".row_Manage").show();
                    $(".row_HRD").hide();
                    $(".row_Finance").hide();
                    $(".row_spv").hide();

                    $("#app_manage").val('').trigger("change");
                    $("#app_hrd").val('').trigger("change");
                    $("#app_finance").val('').trigger("change");
                }
            });
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
                $('#city').append('<option value="{{$getdata->des_kota}}" selected disabled>Pilih Kota</option>');
                for (i = 0; i < response.length; i++) {
                    $('#city').append('<option value="' + response[i].id + '">' + response[i].kota + '</option>');
                }

            }
        });
    });
}

function Reject_travel(ele) {
    window.location.href = main_url + "hrm/request/travel/reject/" + $(ele).data('id') + "/" + $(ele).data('div') + "/" + $(ele).data('emp');
}

function Approve_travel(ele) {
    window.location.href = main_url + "hrm/request/travel/approve/" + $(ele).data('id') + "/" + $(ele).data('div') + "/" + $(ele).data('emp');
}