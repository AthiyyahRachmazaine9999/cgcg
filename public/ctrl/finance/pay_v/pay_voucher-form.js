var FormControls = {
    init: function() {
        ! function() {
            $("#payment").select2({
                allowClear: true,
                placeholder: "*"
            }), $("#payment").on("select2:change", function() {
                e.element($(this))
            });
            //payments
            $("#payment").val() == '' ? $(".row_do").hide() && $("#no_do").val('') : $(".row_do").show();
            $("#payment").val() == 'cbd' ? $(".row_do").hide() : $(".row_do").show();
            $(".payments").val() == 'cbd' ? $(".exc_cbd").hide() : $(".exc_cbd").show();
            $(".payments").val() == '' || $(".payments").val() != 'cbd' ? $(".row_p_invoices").hide() : $(".row_p_invoices").show();
            $(".payments").val() == 'cbd' ? $(".doc_cbds").show() : $(".doc_cbds").hide();
            $("#payment").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".row_do").hide();

                    $(".row_invoices").show();
                    $(".row_p_invoices").hide();
                    $(".exc_cbd").show();
                    $(".doc_cbds").hide();
                } else if (option === 'top') {
                    $(".row_do").show();

                    $(".row_invoices").show();
                    $(".row_p_invoices").hide();
                    $(".exc_cbd").show();
                    $(".doc_cbds").hide();
                } else if (option === 'cbd') {
                    $(".row_do").hide();

                    $(".row_invoices").hide();
                    $(".row_p_invoices").show();
                    $(".exc_cbd").hide();
                    $(".doc_cbds").show();
                } else {
                    $(".row_do").show();

                    $(".row_invoices").show();
                    $(".row_p_invoices").hide();
                    $(".exc_cbd").show();
                    $(".doc_cbds").hide();
                }
            });
            $("#purchase_order").select2({
                    allowClear: true,
                    placeholder: "*"
                }), $("#purchase_order").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#vnd").select2({
                    placeholder: "Pilih Vendor",
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='VendorForm(this)'>Tambah Baru</a>");

                        }
                    },
                    ajax: {
                        url: main_url + 'finance/payment_voucher/find_vendor',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.vendor_name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                }), $("#province").select2({
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
                $("#type1").on("change", function(e) {
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

$(document).ready(function() {
    $('#dari_tgl, #sampai_tgl').on('change', function() {
        if (($("#dari_tgl").val() != "") && ($("#sampai_tgl").val() != "")) {
            var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
            var firstDate = new Date($("#dari_tgl").val());
            var secondDate = new Date($("#sampai_tgl").val());
            var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay)));
            $("#top").val(diffDays + " HARI");
        }
    });
});

$("#purchase_order").on("change", function(e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    var type = $('#payment option:selected').val();
    $("#sales_order").prop("disabled", false);
    $("#invoice_no").prop("disabled", false);
    $("#vendor_id").prop("disabled", false);
    if (option === '') {
        $("#purchase_order").prop("disabled", true);
        $("#sales_order").prop("disabled", true);
        $("#invoice_no").prop("disabled", true);
        $("#vendor_id").prop("disabled", true);
    } else {
        getValue(option, type);
    }
});


function getValue(option, type) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'finance/payment_voucher/get_value',
            type: "POST",
            dataType: "json",
            data: { id: option, type: type },
            cache: false,
            success: function(response) {
                console.log(response);
                var html = '';
                var i;
                $("#sales_order").val(response.data.id_quo);
                $("#nominal").val(Math.round(response.data.po_so.price));
                $("#Quo_Id").val(response.data.po_so.id_quo);
                $("#vnd").val(response.data.po_so.id_vendor).trigger('change');
            }
        });
    });
}

function VendorForm() {
    $("#id_vendor").select2("close");
    var prospectRes = ajax_data("sales/new_vendor")

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("Tambah Data Vendor")

    $("#province").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "Pilih Provinsi"
    }), $("#city").select2({
        allowClear: true,
        placeholder: "Pilih Kota"
    }), $("#country").select2({
        allowClear: true,
        placeholder: "Pilih Kecamatan"
    });
    $("#province").on("change", function(e) {
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
    $("#city").on("change", function(e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        $("#country").prop("disabled", false);
        $('#country option').remove();
        if (option === '') {
            $("#country").prop("disabled", true);
        } else {
            getCamat(option);
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

    function getCamat(option) {
        $(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + 'api/location/get_kecamatan',
                type: "POST",
                dataType: "json",
                data: { id: option },
                cache: false,
                success: function(response) {
                    var html = '';
                    var i;
                    $('#country').append('<option value="">Pilih Kecamatan</option>');
                    for (i = 0; i < response.length; i++) {
                        $('#country').append('<option value="' + response[i].id + '">' + response[i].nama + '</option>');
                    }

                }
            });
        });
    }

}



function Approve_payment(ele) {
    window.location.href = main_url + "finance/payment_voucher/approve_payment/" + $(ele).data("id") + "/" + $(ele).data("id_dtl") + "/" + $(ele).data("usr");
    // console.log(getdata);
}

function Reject_payment(ele) {
    window.location.href = main_url + "finance/payment_voucher/reject_payment/" + $(ele).data("id") + "/" + $(ele).data("id_dtl") + "/" + $(ele).data("usr");
    // console.log(getdata);
}