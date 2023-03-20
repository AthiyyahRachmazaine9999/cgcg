var FormControls = {
    init: function() {
        ! function() {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
            document.querySelectorAll('.form-check-input-switchery').value == 1 ? 1 : 0;
            elems.forEach(function(html) {
                var switchery = new Switchery(html);
            });
            $("#typess").select2({
                allowClear: true,
                placeholder: "Pilih Tipe Pembayaran"
            });
            $("#methods").select2({
                allowClear: true,
                placeholder: "Pilih Bentuk Pembayaran"
            });

            $("#nama_bankk").select2({
                allowClear: true,
                placeholder: "Pilih Pembayaran"
            });

            if ($("#methods").val() == 'cash') {
                $(".row_methods").hide();
            } else if ($("#methods").val() == 'transfer') {
                $(".row_methods").show();
            } else {
                $(".row_methods").hide();
            }
            $("#methods").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".row_methods").hide();
                    $("#bank_name").val('');
                    $("#acc_name").val('');
                    $("#no_acc").val('');
                } else if (option === 'cash') {
                    $(".row_methods").hide();
                    $("#bank_name").val('');
                    $("#acc_name").val('');
                    $("#no_acc").val('');
                } else if (option === 'transfer') {
                    $(".row_methods").show();
                }
            });
            $(".dates").datepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                }),
                $(".parsial").hide();
            var a = $('#total_include').val();
            $("#types").on("change", function(e) {
                e.preventDefault();
                var option = $('option:selected', this).val();
                if (option === '') {
                    $(".parsial").hide();
                    $(".full").show();
                } else if (option === 'parsial') {
                    $(".parsial").show();
                    $(".full").hide();
                } else if (option === 'full') {
                    $(".parsial").hide();
                    $(".full").show();
                    document.getElementById('full_payment').value = $('#total_include').val();
                } else {
                    $(".parsial").hide();
                    $(".full").show();
                }
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

// $(".rowss").hide();

function next_payment(ele) {
    var n_equ = $(".payments").length;
    n_equ++;
    var equ = ajax_data("finance/invoice/next_payment", "&n_equ=" + n_equ +
        "&id_inv=" + $(ele).data('id_inv'));
    $(".nexts").before(equ);

}

function remove_payment(ele) {
    var n_equ = $(".payments").length;
    var equ = $(ele).data('equ');
    console.log(n_equ);
    var get = $(ele).data('type');
    if (get == "removes") {
        var getdata = ajax_data("finance/invoice/removes", "&id=" + $(ele).data("id") + "&id_quo_inv=" + $(ele).data("id_quo_inv") +
            "&id_quo=" + $(ele).data("id_quo") + "&equ=" + $(ele).data("equ") + "&pay=" + $(ele).data("pay"));
        $(".row_parsial_" + equ).remove();
        console.log(getdata);
        if (getdata.success) {
            $(".minus").val(getdata.sum);
        }
    } else {
        $(".next_payment_" + ele).remove();
    }
}

var total_amount = function(a) {
    var sum = 0;
    var t = a == 'change' ? $("#total_amount_inc").val().replace(/,/g, "") : $("#total_include").val().replace(/,/g, "");
    console.log(t);
    $(".amounts").each(function() {
        var am = $(".amounts").val();
        var num = $(this).val();
        console.log(num);
        if (num != 0 || num != '') {
            sum += parseFloat(num);
            console.log(sum);
        }
    });
    var sisa = parseFloat(t) - parseFloat(sum);
    $(".minus").val(sisa);
}

$(".amounts").keyup(function() {
    total_amount();
})


function totalss() {
    var t = $("#total_amount_inc").val();
    if (t != null || t != '') {
        var a = "change";
        total_amount(a);
    }
}


$("#checkss").on("click", function() {
    var active = $('#checkss').prop("checked") ? 1 : 0;
    console.log(active);
});

function Edit_detailPayment(ele) {
    var type = $(ele).data("type");
    if (type == "edit_upper") {
        var getdata = ajax_data('finance/invoice/get_editnpwp', '&id_inv=' + $(ele).data('id') + '&no_invoice=' + $(ele).data('no_inv'));
        $(".uppers").html(getdata);
    } else if (type == "edit_pembayaran") {
        var getdata = ajax_data('finance/invoice/get_editpayment', '&id=' + $(ele).data('id') + "&count=" +
            $(ele).data('count') + "&id_pay=" + $(ele).data('id_pay'));
        $(".edit_dtl").html(getdata);
    } else if (type == "edit_potongan") {
        var getdata = ajax_data('finance/invoice/get_editpotongan', '&id=' + $(ele).data('id'));
        $(".edit_potongans").html(getdata);
    } else if (type == "tambah_potongan") {
        var getdata = ajax_data('finance/invoice/plus_editpotongan', '&id=' + $(ele).data('id'));
        $(".edit_potongans").html(getdata);
    } else if (type == "finish_payment") {
        window.location.href = main_url + 'finance/invoice/finish_payment/' + $(ele).data('id');
    } else if (type == "tambah_pembayaran") {
        var getdata = ajax_data('finance/invoice/get_tambahpayment', '&id=' + $(ele).data('id') + "&count=" +
            $(ele).data('count'));
        $(".edit_dtl").html(getdata);
    } else if (type == "detail_pembayaran") {
        var ajx = ajax_data('finance/invoice/get_detailpayment', '&id=' + $(ele).data('id') + "&count=" +
            $(ele).data('count') + "&id_pay=" + $(ele).data('id_pay'));
        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail Payment");
    } else if (type == "hapus_pembayaran") {
        var getdata = ajax_data('finance/invoice/get_hapuspayment', '&id=' + $(ele).data('id') + "&count=" +
            $(ele).data('count') + "&id_pay=" + $(ele).data('id_pay'));
        window.location.reload();
    }

}

function tambah_tbl(ele) {
    var type = $(ele).data("type");
    if (type == "tambah detail") {
        var table = document.getElementById('tables_payment');
        var n_equ = table.rows.length;
        console.log(n_equ);
        n_equ++;
        var getdata = ajax_data('finance/invoice/add_rows', '&id_inv=' + $(ele).data('id_inv') + "&n_equ=" + n_equ);
        $(".row_plus").before(getdata);
    } else if (type == "hapus_editdb_detail") {
        var getdata = ajax_data('finance/invoice/remove_rows_payment', '&id_inv=' + $(ele).data('id_inv') + "&n_equ=" + $(ele).data('n_equ') +
            "&id=" + $(ele).data('id_dtl'));
        $("#rows_mid_" + $(ele).data("n_equ")).remove();
    } else {
        $(".rows_" + ele).remove();
    }
}

function addpotongan(ele) {
    var types = $(ele).data("type");

    if (types == "add_forms") {
        var table = document.getElementById('table_last');
        var n_equ = table.rows.length;
        n_equ++;
        var getdata = ajax_data('finance/invoice/add_forms', '&id=' + $(ele).data('id_inv') + "&n_equ=" + n_equ);
        $(".add_forms_edit").before(getdata);
    } else if (types == "hapus_datas") {
        var getdata = ajax_data('finance/invoice/removes_potongan', '&id=' + $(ele).data("id"));
        $(".rows_last_" + $(ele).data("id")).remove();
    } else if (types == "delete_forms") {
        $(".rows_" + $(ele).data('count')).remove();
    }
}

function PrintInvoicing(ele) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: main_url + "finance/invoice/cetak_invoicing",
        data: {
            id: $(ele).data("id"),
            '_token': token,
            no_inv: $(ele).data('no_inv')
        },
        xhrFields: { responseType: 'blob' },
        success: function(data) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - " + $(ele).data("no_inv") + ".pdf";
            link.click();
        }
    })
}