var FormControls = {
    init: function () {
        ! function () {
            $("#id_product").select2({
                allowClear: true,
                placeholder: "Cari Product",
            }).on("change", function (e) {

                $('#row_product').show();
                var getproso = $("#id_product").val();
                var getdata = ajax_data("sales/getdetailproduct_so", "&id_pro=" + getproso);
                console.log(getdata.det_quo_qty_beli, getdata.det_quo_harga_final);
                $('#p_qty').val(getdata.det_quo_qty_beli);
                $('#p_price').val(getdata.det_quo_harga_final).prop('readonly', true);

            });
        }()
    }
};

jQuery(document).ready(function () {
    FormControls.init();
});

function ChangePPN(ele) {
    if($(ele).data("isppn")=="yes"){
        var text = "Hilangkan PPN ?";
    }else{
        var text = "Aktifkan PPN ?"
    }
    swal({
        title: text,
        text: "Are you sure?",
        type: "warning",
        showCancelButton: !0,
        button: "Yes, proses",
    }).then((result) => {
        var getdata = ajax_data("purchasing/order/changeisppn", "&id=" + $(ele).data("id"));
        window.location.href = main_url + getdata;
    })
}
function AddNote(ele) {
    var getdata = ajax_data("purchasing/order/addnote", "&idpo=" + $(ele).data("id"));
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Additional Note");
}
function KirimEmail(ele) {
    var getdata = ajax_data("purchasing/quotation/kirimpo", "&idpo=" + $(ele).data("id") + "&type=" + $(ele).data("type"));
    var valuetext = ajax_data("purchasing/quotation/defaulttext", "&idpo=" + $(ele).data("id")+"&type=" + $(ele).data("type"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Kirim Email PO");

    $(".mail-list").select2({
        placeholder: "Masukan CC Mail",
        tags: true,
        tokenSeparators: [',', ' ']
    })

    $("#body").html(valuetext);

    $('.summernote').summernote({
        placeholder: 'Isi Dengan Product Request Anda',
        code: valuetext
    });
}

function PrintFinalPO(ele) {
    // var getdata = ajax_data("purchasing/download/finalpo", "&nopo=" + $(ele).data("id"));
    // var datas = $("#m_form").serializeArray();
    // datas.push({ name: 'other', value: 'yes' });
    // console.log(datas);
    $.ajax({
        type: "POST",
        url: main_url + "purchasing/download/finalpo",
        data: {
            nopo:$(ele).data("id"),
            '_token': token
        },
        xhrFields: { responseType: 'blob' },
        success: function (data) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - "+$(ele).data("id")+".pdf";
            link.click();
        }
    })
}


function EditPays(ele) {
    var getdata = ajax_data("purchasing/order/Editpay_vendor", "&id=" + $(ele).data("id") + "&id_po=" + $(ele).data("id_po") + "&id_quo=" + $(ele).data("id_quo") + +"&id_pay=" + $(ele).data("id_pay"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Edit Page");
    $("#edit_tanggal").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "yyyy-mm-dd"
    });
    $("#nama_bankk").select2({
        allowClear: true,
        placeholder: "Pilih Nama Bank"
    });

}


function HapusPays(ele) {
    swal({
        title: "Delete Alamat",
        text: "Are you sure?",
        type: "warning",
        showCancelButton: !0,
        button: "Yes, delete it",
    }).then((result) => {
        var getdata = ajax_data("purchasing/order/Hapuspay_vendor", "&id=" + $(ele).data("id") + "&id_po=" + $(ele).data("id_po") + "&id_quo=" + $(ele).data("id_quo"));
        window.location.href = main_url + getdata;
    })
}


function paymentFinance(ele) {
    var getdata = ajax_data("purchasing/order/pay_vendor", "&id=" + $(ele).data("id") + "&id_pay=" + $(ele).data("id_pay"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Payment");
    $("#tanggal").datepicker({
        todayHighlight: !0,
        autoclose: !0,
        format: "yyyy-mm-dd"
    });
    $("#status_pay").select2({
        allowClear: true,
        placeholder: "Pilih Status Pembayaran"
    });
    $("#nama_bankk").select2({
        allowClear: true,
        placeholder: "Pilih Nama Bank"
    });
}



function ChangeAlamat(ele) {
    var getdata = ajax_data("purchasing/order/ganti_alamat", "&idpo=" + $(ele).data("id") + "&type=" + $(ele).data("type"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Ganti Alamat");
}

function DeleteAlamat(ele){
	swal({
		title: "Delete Alamat",
		text: "Are you sure?",
		type: "warning",
		showCancelButton: !0,
		button: "Yes, delete it",
	}).then((result) => {
        var getdata = ajax_data("purchasing/order/delete_alamat", "&nopo=" + $(ele).data("id"));
        window.location.href = main_url + getdata;
	})
}

function ShowApprovalPO(ele) {
    var getdata = ajax_data("purchasing/order/approval", "&idpo=" + $(ele).data("id") + "&type=" + $(ele).data("type"));
    var head = $(ele).data("type") + " PO";
    $("#modalbody").html(getdata),
        $("#modaltitle").html(head.toUpperCase());
}

function changedate(ele) {
    $('#dates').show();
    $('#cdate').hide();
}

function save_date(ele) {
    var dates   = $('#end_date').val();
    console.log(dates);
    var getdata = ajax_data("purchasing/order/save_date", "&idpo=" + $(ele).data("id")+"&date=" + dates);

    $("#new_date").html("Costum PO Date : "+getdata);
    $('#dates').hide();
    $('#cdate').show();

}
var n_equ = $(".form-item").length;
function add_product(ele) {
    $("#btntambah").hide();
    $('#row_product').hide();
    var equ = ajax_data("purchasing/po/product_clone",
     "&n_equ=" + n_equ + "&id_quo=" + $(ele).data("id_quo") + "&id_po=" + $(ele).data("id_po")
     );
    $("#separate_equ").before(equ);
    $("#id_so").select2({
        allowClear: true,
        placeholder: "Cari SO",
        ajax: {
            // url: main_url + 'sales/find_customer',
            url: main_url + 'sales/find_so',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: "SO0000" + item.id + " - " + item.quo_name,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    }).on("change", function (e) {
        $('#row_product').show();
        var getso = $("#id_so").val();
        if (getso == '') {
            $('#p_qty').val('');
            $('#p_price').val('');
        }
        var getdata = ajax_data("sales/getproduct_so", "&id_quo=" + getso);
        $('#row_product').html(getdata);
        $("#id_product").select2({
            allowClear: true,
            placeholder: "Cari Product",
        }).on("change", function (e) {

            $('#row_product').show();
            var getproso = $("#id_product").val();
            var getdata = ajax_data("sales/getdetailproduct_so", "&id_pro=" + getproso);
            console.log(getdata.det_quo_qty_beli, getdata.det_quo_harga_final);
            $('#p_qty').val(getdata.det_quo_qty_beli).prop('readonly', true);
            $('#p_price').val(getdata.det_quo_harga_final).prop('readonly', true);

        });

    });
    n_equ++;

}
function save_new(params) {
    var qty = $('#p_qty').val();
    var price = $('#p_price').val();
    if (qty == '' || price == '') {
        swal({
            title: "Oops",
            text: "Silahkan isi dulu kelengkapan data barang di sales order",
            icon: "warning"
        });
    } else {
        swal({
            title: "Are you sure?",
            text: "Save this data!",
            icon: "warning",
            button: "Yes, save it!"
        }).then((result) => {
            if (result) {

                var datas = $(".formedit").find("select, textarea, input").serializeArray();
                datas.push({ name: 'other', value: 'yes' });
                console.log(datas);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: main_url + "purchasing/po/product_newclone",
                    data: datas,
                    success: function (res) {
                        window.location.href = main_url + res;
                    },
                    error: function (res) {
                        alert("error");
                    }
                })
                return false;
            }
        });
    }

}
function remove_equ(id) {
    $("#row_item_" + id).remove();
    $("#btntambah").show();
}
var n_att = $(".form-item").length;
function add_attach(ele) {
    var equ = ajax_data("purchasing/po/attachment_mail",
        "&n_equ=" + n_att + "&idpo=" + $(ele).data("idpo")
    );
    $("#divimage").before(equ);
}
function remove_attach(id) {
    $("#form_item_" + id).remove();
}
function Payv_form(ele) {
    if ($(ele).data('type') == "show") {
        var show = ajax_data("purchasing/order/show_payment", "&id=" + $(ele).data("id"));
        $("#modalbody").html(show);
        $("#modaltitle").html('')
    } else {
        var getdata = ajax_data("purchasing/order/create_payment", "&po_number=" + $(ele).data("id_po") + "&type=" + $(ele).data("type")+ "&id_pay=" + $(ele).data("id_pay"));
        $("#pay_voucher").html(getdata);
        $(".date").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "yyyy-mm-dd"
            }), $("#city").select2({
                allowClear: true,
                placeholder: "Pilih Kota"
            }), $("#country").select2({
                allowClear: true,
                placeholder: "Pilih Kecamatan"
            }),
            $(".vnd").select2({
                allowClear: true,
                placeholder: "Cari Vendor",
                language: {
                    noResults: function() {
                        return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='VendorForm(this)'>Tambah Baru</a>");

                    }
                },
                ajax: {
                    url: main_url + 'sales/find_vendor',
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

        $('#dari_tgl, #sampai_tgl').on('change', function() {
            if (($("#dari_tgl").val() != "") && ($("#sampai_tgl").val() != "")) {
                var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                var firstDate = new Date($("#dari_tgl").val());
                var secondDate = new Date($("#sampai_tgl").val());
                var diffDays = Math.round(Math.round((secondDate.getTime() - firstDate.getTime()) / (oneDay))+1);
                $("#top").val(diffDays + " HARI");
            }
        });

        $(".payments").select2({
            allowClear: true,
            placeholder: "*"
        });
        $(".row_do1").hide();
        $(".payments").val() == '' ? $(".row_do").hide() && $("#no_do").val('') : $(".row_do").show();
        $(".payments").val() == 'cbd' ? $(".row_do").hide() : $(".row_do").show();
        $(".payments").val() == 'cbd' ? $(".exc_cbd").hide() : $(".exc_cbd").show();
        $(".payments").val() == 'cbd' ? $(".doc_cbds").show() : $(".doc_cbds").hide();
        $(".payments").val() == '' || $(".payments").val() != 'cbd' ? $(".row_p_invoices").hide() : $(".row_p_invoices").show();
        $(".payments").on("change", function(e) {
            e.preventDefault();
            var option = $('option:selected', this).val();
            if (option === '') {
                $(".row_do").hide();
                $(".row_do1").hide();

                $(".row_invoices").show();
                $(".row_p_invoices").hide();
                $(".exc_cbd").show();
                $(".doc_cbds").hide();
            } else if (option === 'top') {
                $(".row_do").show();
                $(".row_do1").show();

                $(".row_invoices").show();
                $(".row_p_invoices").hide();
                $(".exc_cbd").show();
                $(".doc_cbds").hide();
            } else if (option === 'cbd') {
                $(".row_do").hide();
                $(".row_do1").hide();

                $(".row_invoices").hide();
                $(".row_p_invoices").show();
                $(".exc_cbd").hide();
                $(".doc_cbds").show();
                $(".num_edit").val('');
                $(".doc_cbds").show();
            } else {
                $(".row_do").show();
                $(".row_do1").show();

                $(".row_invoices").show();
                $(".row_p_invoices").hide();
                $(".exc_cbd").show();
                $(".num_edit").val('');
                $(".doc_cbds").hide();
            }
        });

    }
}

function download_payv(ele) {
    var getdata = ajax_data("finance/download/pdf_payment/" + $(ele).data("id_pay"));
}

function VendorForm() {
    $(".vnd").select2("close");
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

function save_vendor(ele) {
    var form = $("#m_form");
    concole.log(form);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: main_url + 'finance/payment_voucher/saveVendor',
        type: "POST",
        dataType: "json",
        data: $("#add_form").serialize(),
        success: function(d) {
            console.log(d);
        }

    })
}


function All_history(ele) {
        var ajx = ajax_data("purchasing/order/history", "&id=" + $(ele).data("id"));
        $("#modalbody").html(ajx),
            $("#modaltitle").html("All History");
}