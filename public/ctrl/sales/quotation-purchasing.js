function ConfirmBeli1(ele) {
swal(
  'Oops...',
  'Isi Data Dengan Lengkap Terlebih Dahulu',
  'error'
    )
}

function ConfirmBeli(ele) {

    var getdata = ajax_data("sales/quotation/ajukandraftbeli", "&quo=" + $(ele).data("id") + "&idpro=" + $(ele).data("idpro"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Draft Pengajuan PO");

    $(".if_type").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "--Pilih Type PO--",
        width: "100%"
    });
    HitungNew(ele);
    $("#m_form").validate({
        rules: {
            p_qty: {
                required: !0
            }
        }
        , errorPlacement: function (error, element) {
            if (element.parents("div").hasClass("m-radio-inline")) {
                error.appendTo(element.parent().parent());
            }
            else {
                error.insertAfter(element);
            }
        }
        , invalidHandler: function (e, r) {
            var i = $("#m_form_1_msg");
            i.removeClass("m--hide").show()
        }
        , submitHandler: function (e) {
            swal({
                title: "Ajukan Harga ini",
                text: "Harga Sudah Final ya ?",
                icon: "warning",
                button: "Yes, ajukan!"
            }).then((result) => {
                if (result) {
                    e.submit();
                }
            })
        }
    })
}
function HitungNew(ele,etu,eni) {

    var harga = $("#p_harga_real_" + ele).val();
    var qty = $("#p_qty_" + ele).val();
    var sub = parseInt(harga * qty);
    $("#p_harga_show_" + ele).html(sub.toLocaleString());
    $("#p_harga_hidden_" + ele).val(sub);

    var sum = 0;
    $('.totalprice').each(function () {
        sum += parseFloat(this.value);
    });
    $("#subtotal").html(sum.toLocaleString());
    $("#total").val(sum);

    var vat = parseFloat(sum * eni / 100);
    var total = parseFloat(sum + vat);

    $("#vat").html(vat.toLocaleString());
    $("#include").html(total.toLocaleString());

}
function PrintDraftPO(ele) {
    var datas = $("#m_form").serializeArray();
    var sonum = $("#sonum").val();
    console.log(sonum);
    datas.push({ name: 'other', value: 'yes' });
    $.ajax({
        type: "POST",
        url: main_url + "purchasing/download/draftpo",
        data: datas,
        xhrFields: { responseType: 'blob' },
        success: function (data) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - DRAFT PO FOR"+sonum+".pdf";
            link.click();
        }
    })
}

function ChangeVendor(ele) {

    var getdata = ajax_data("purchasing/po/gantivendor", "&nomerpo=" + $(ele).data("nomerpo"));
    $("#editform").html(getdata);
    $("#editbutton").hide();
    $(".stock").select2({
        allowClear: true,
        placeholder: "--Pilih Status--",
        width: "100%"
    });
    $(".bayar").select2({
        allowClear: true,
        placeholder: "--Pilih Type Bayar--",
        width: "100%"
    });

    $("#normalif").show();
    $("#persenif").hide();

    if ($("#iftype").val() === 'percen') {
        $("#persenif").show();
        $("#normalif").hide();

    } else {

        $("#normalif").show();
        $("#persenif").hide();
    }

    $("#iftype").on("change", function (e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        if (option === 'percen') {
            $("#persenif").show();
            $("#normalif").hide();
        } else {
            $("#normalif").show();
            $("#persenif").hide();
        }
    });

    $(".vendor").select2({
        allowClear: true,
        placeholder: "--Pilih Vendor--",
        width: "100%",
        language: {
            noResults: function () {
                return $("<a href='#' data-toggle='modal' data-target='#m_modal2' onClick='VendorForm(this)'>Tambah Baru</a>");

            }
        },
        ajax: {
            url: main_url + 'sales/find_vendor',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
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
}
function CancelPO(ele) {

    var getdata = ajax_data("purchasing/po/cancel", "&nomerpo=" + $(ele).data("nomerpo"));
    $("#modalbody").html(getdata),$("#modaltitle").html("Cancel"+$(ele).data("nomerpo"));
}
