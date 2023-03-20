function EditProduct(ele) {
    var getdata = ajax_data("sales/quotation/product_edit", "&quo=" + $(ele).data("id") + "&myid=" + $(ele).data("access"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Edit Harga");
    $(".stock").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "--Pilih Status--",
        width: "100%"
    });
    $(".bayar").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
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

    // $('#cvendor').click(function (e) {
    //     $('#cvendor').hide();
    //     $(".vendor").show();
    // });

    $(".vendor").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
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

var n_equ = $(".form-item").length;
function clone_product() {
    $('.modal').css('overflow-y', 'auto');
    console.log(n_equ);
    var equ = ajax_data("sales/quotation/product_clone", "&n_equ=" + n_equ);
    $("#separate_equ").before(equ);
    $(".id_product").select2({
        allowClear: true,
        placeholder: "Cari Product",
        language: {
            noResults: function () {
                return $("<a href='#' data-toggle='modal' data-target='#m_modal2' onClick='BarangOther(n_equ-1)'>Barang Baru</a>");

            }
        },
        ajax: {
            // url: main_url + 'sales/find_customer',
            url: main_url + 'product/get_product',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: "[" + item.sku + "] " + item.name,
                            id: item.sku
                        }
                    })
                };
            },
            cache: true
        }
    });

    n_equ++;

}
function ChangeProduct(ele) {
    $('.modal').css('overflow-y', 'auto');
    var equ = ajax_data("sales/quotation/product_clone_change",
        "&id_quo=" + $(ele).data("id_quo") + "&idpro=" + $(ele).data("idpro"));
    $("#change_equ_" + $(ele).data("idpro")).html(equ);
    console.log($(ele).data("idpro"));
    $(".id_product").select2({
        allowClear: true,
        placeholder: "Cari Product",
        language: {
            noResults: function () {
                return $("<a href='#' data-toggle='modal' data-target='#m_modal2' onClick='BarangOther(n_equ-1)'>Barang Baru</a>");

            }
        },
        ajax: {
            // url: main_url + 'sales/find_customer',
            url: main_url + 'product/get_product',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: "[" + item.sku + "] " + item.name,
                            id: item.sku
                        }
                    })
                };
            },
            cache: true
        }
    });


}

function save_equ(params) {
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
                url: main_url + "sales/quotation/save_changeproduct",
                data: datas,
                success: function (res) {
                    // $('#m_modal').modal('toggle');
                    // location.reload();
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
function DeleteProduct(ele) {
    swal({
        title: "Delete Product?",
        text: "Delete this product!",
        icon: "error",
        button: "Yes, delete it!"
    }).then((result) => {
        if (result) {
            var getdata = ajax_data("sales/quotation/delete_changeproduct",
                "&id_quo=" + $(ele).data("id_quo") + "&idpro=" + $(ele).data("idpro")
                + "&id=" + $(ele).data("id")

            );

            window.location.href = main_url + "sales/quotation/" + $(ele).data("id_quo");
            return false;
        }
    });
}
function remove_equ(id) {
    $("#row_item_" + id).remove();
}
function BarangOther(ele) {

    $('.modal').css('overflow-y', 'auto');
    console.log(ele);
    $(".id_product").select2("close");
    var newtemplate = ajax_data("product/request_other")
    $("#modalbody2").html(newtemplate),
        $("#modaltitle2").html("Request Barang")

    $('.summernote').summernote({
        placeholder: 'Isi Dengan Product Request Anda'
    });
    $("#btnSubmit").click(function () {
        $('#m_modal2').modal('toggle');
        var html = $("#detail_barang").summernote("code");
        $('#newsku_' + ele).val(html);
        var $newOption = $("<option selected='selected'></option>").val("new").text(html)
        console.log("id_product_" + ele);
        $("#id_product_" + ele).append($newOption).trigger('change');
    });

}
function BarangModalOther(ele) {

    $('.modal').css('overflow-y', 'auto');
    console.log(ele);
    $(".id_product").select2("close");
    var newtemplate = ajax_data("product/request_other")
    $("#modalbody2").html(newtemplate),
        $("#modaltitle2").html("Request Barang")

    $('.summernote').summernote({
        placeholder: 'Isi Dengan Product Request Anda'
    });
    $("#btnSubmit").click(function () {
        $('#m_modal2').modal('toggle');
        var html = $("#detail_barang").summernote("code");
        $('#newsku_' + ele).val(html);
        var $newOption = $("<option selected='selected'></option>").val("new").text(html)
        console.log("id_product_" + ele);
        $("#id_product_" + ele).append($newOption).trigger('change');
    });

}
function ShowProduct(ele) {
    var getdata = ajax_data("sales/quotation/product_detail", "&idquo=" + ele);
    console.log(ele);
    if (getdata == "Redirect") {
        window.location.href = main_url + "product/new_content/" + ele;
        // console.log(ele);
    } else {
        $('#m_modal').modal('show');
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Detail Product");
        $(".m_popup").DataTable();
    }
}


function VendorForm(ele) {
    var prospectRes = ajax_data("sales/new_vendor")

    $("#modalbody2").html(prospectRes),
        $("#modaltitle2").html("Tambah Data Vendor")

    $("#province").select2({
        allowClear: true,
        dropdownParent: $("#m_modal2"),
        placeholder: "Pilih Provinsi"
    }), $("#city").select2({
        allowClear: true,
        dropdownParent: $("#m_modal2"),
        placeholder: "Pilih Kota"
    }), $("#country").select2({
        allowClear: true,
        dropdownParent: $("#m_modal2"),
        placeholder: "Pilih Kecamatan"
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
        }
        else {
            getKota(option);
        }
    });
    $("#city").on("change", function (e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        $("#country").prop("disabled", false);
        $('#country option').remove();
        if (option === '') {
            $("#country").prop("disabled", true);
        }
        else {
            getCamat(option);
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
                data: { id: option },
                cache: false,
                success: function (response) {
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
        $(function () {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + 'api/location/get_kecamatan',
                type: "POST",
                dataType: "json",
                data: { id: option },
                cache: false,
                success: function (response) {
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

    $("#m_form").validate({

        submitHandler: function (e) {

            swal({
                title: "Are you sure?",
                text: "Save this data!",
                icon: "warning",
                button: "Yes, save it!"
            }).then((result) => {
                if (result) {

                    var datas = $("#m_form").serializeArray();
                    datas.push({ name: 'other', value: 'yes' });
                    console.log(datas);
                    $.ajax({
                        type: "POST",
                        url: main_url + "sales/vendor_store",
                        // data: $("#m_form_presales").serialize(),
                        data: datas,
                        success: function (res) {
                            $('#m_modal2').modal('toggle');
                        },
                        error: function (res) {
                            alert("error");
                        }
                    })
                    return false;
                }
            })
        }
    })
}