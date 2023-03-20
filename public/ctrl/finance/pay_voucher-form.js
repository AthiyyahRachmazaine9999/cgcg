var FormControls = {
    init: function() {
        ! function() {
            $("#payment").select2({
                allowClear: true,
                placeholder: "*"
            }), $("#payment").on("select2:change", function() {
                e.element($(this))
            });
            $("#type_voucher").select2({
                allowClear: true,
                placeholder: "Pilih Keperluan Form"
            }), $("#type_voucher").on("select2:change", function() {
                e.element($(this))
            });
            $("#status_pay").select2({
                allowClear: true,
                placeholder: "Pilih Status Pembayaran"
            }), $("#status_pay").on("select2:change", function() {
                e.element($(this))
            });
            $("#cust").select2({
                allowClear: true,
                placeholder: "Pilih Customer"
            }), $("#cust").on("select2:change", function() {
                e.element($(this))
            });
            $("#vnd").select2({
                allowClear: true,
                placeholder: "Pilih Vendor"
            }), $("#vnd").on("select2:change", function() {
                e.element($(this))
            });
            $("#sec_vnd").select2({
                allowClear: true,
                placeholder: "Pilih Vendor"
            }), $("#sec_vnd").on("select2:change", function() {
                e.element($(this))
            });

            $("#no_so").select2({
                allowClear: true,
                placeholder: "Pilih Sales Order"
            }), $("#no_so").on("select2:change", function() {
                e.element($(this))
            });

            if ($("#type_voucher").val() == "lainnya") {
                $(".row_vendors").hide();
                $(".row_salesorder").show();
                $(".row_ongkir").show();
                $(".autovendor").show();
            } else if ($("#type_voucher").val() == "blank") {
                $(".row_vendors").show();
                $(".row_salesorder").hide();
                $(".row_ongkir").hide();
                $(".autovendor").hide();
            } else {
                $(".row_vendors").hide();
                $(".row_ongkir").hide();
            }

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
            $("#type_voucher").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option == "lainnya") {
                    $(".row_vendors").hide();
                    $(".row_salesorder").show();
                    $(".autovendor").show();
                    $(".row_ongkir").show();
                    $(".change_hide").val('');
                    $(".change_hide").val('').trigger("change");
                } else if (option == "blank") {
                    $(".row_vendors").show();
                    $(".row_salesorder").hide();
                    $(".row_ongkir").hide();
                    $(".autovendor").hide();
                    $(".change_hide").val('').trigger("change");
                }
            });

            $("#no_so").select2({
                placeholder: "Pilih Sales Order",
                allowClear: true,
                language: {
                    noResults: function() {
                        return $("<a href='#'>Select Sales Order</a>");
                    }
                },
                ajax: {
                    url: main_url + 'sales/find_so',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                if (item.quo_no == null) {
                                    var num = "RFQ";
                                } else {
                                    var num = item.quo_no;
                                }
                                return {
                                    text: "[ SO0000" + item.id + " - " + num + "] " + item.quo_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#purchase_order").select2({
                    allowClear: true,
                    placeholder: "*"
                }),
                $("#purchase_order").on("select2:change", function() {
                    e.element($(this))
                }),
                $("#province").select2({
                    allowClear: true,
                    placeholder: "Pilih Provinsi"
                }),
                $("#city").select2({
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

            $("#sec_vnd").select2({
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
            });


            $("#cust").select2({
                placeholder: "Pilih Customer",
                allowClear: true,
                language: {
                    noResults: function() {
                        return $("<a href='#'>Search Customer</a>");

                    }
                },
                ajax: {
                    url: main_url + 'sales/find_customer',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.company,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
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

$("#no_so").on("change", function(e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    $("#vnd").prop("disabled", false);
    $('#vnd option').remove();
    if (option === '') {
        $("#vnd").prop("disabled", true);
    } else {
        getValue(option);
    }
});

function getValue(option) {
    $(function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + 'finance/payment_voucher/get_value',
            type: "POST",
            dataType: "json",
            data: { id: option },
            cache: false,
            success: function(response) {
                var html = '';
                var i;
                $('.vendors').append('<option value="" selected>Pilih Vendor</option>');
                for (i = 0; i < response.data.vendor.length; i++) {
                    $('.vendors').append('<option value="' + response.data.vendor[i].id + '">' + response.data.vendor[i].vendor_name + '</option>');
                }
                $("#due_dates").val(response.data.ongkir);
                $("#ongkir").val(response.data.ongkir);
                $("#quo_no").val(response.data.quo_no);
                $("#due_dates").val(response.data.type);
                if ($("#type_voucher").val() == "lainnya") {
                    $("#cust").val(response.data.cust).trigger("change");
                }
            }
        });
    });
}

function VendorForm() {
    $("#id_vendor").select2("close");
    var prospectRes = ajax_data("finance/payment_voucher/new_vendor")

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

function PrintPayment(ele) {
    var getdata = ajax_data("finance/download/pdf_payment/" + $(ele).data("id_pay"));

}


function EditPayments(ele) {
    var getdata = ajax_data("finance/payment_voucher/edit_Payment", "&id_pay=" + $(ele).data("id_pay") + "&id=" + $(ele).data("id"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Edit Payment");
    $("#tgl_payment").datepicker({
        autoclose: !0,
        format: "yyyy/mm/dd"
    });
    $("#nama_bankk").select2({
        allowClear: true,
        placeholder: "Pilih Nama Bank"
    });
    $("#status_pay").select2({
        allowClear: true,
        placeholder: "Pilih Tipe"
    });
}


function HapusPayment(ele) {
    var getdata = ajax_data("finance/payment_voucher/hapus_Payment", "&id_pay=" + $(ele).data("id_pay") + "&id=" + $(ele).data("id"));

}


function Approve_payment(ele) {
    window.location.href = main_url + "finance/payment_voucher/approve_payment/" + $(ele).data("id") + "/" + $(ele).data("id_dtl") + "/" + $(ele).data("usr");
    // console.log(getdata);
}

function Reject_payment(ele) {
    window.location.href = main_url + "finance/payment_voucher/reject_payment/" + $(ele).data("id") + "/" + $(ele).data("id_dtl") + "/" + $(ele).data("usr");
    // console.log(getdata);
}

function forward_appr(ele) {
    // var getdata = ajax_data("finance/download/pdf_payment/" + $(ele).data("id_pay"));
    var ajx = ajax_data("finance/payment_voucher/send_todirector", "&id_pay=" + $(ele).data("id"));
    window.location.reload();
}

function DownloadPrecalc(ele) {
    window.location.href = main_url+ 'sales/download/document/' + $(ele).data("id")+"/"+$(ele).data("type");
}

