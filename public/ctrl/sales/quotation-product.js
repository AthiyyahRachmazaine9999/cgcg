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

function EditNewProduct(ele) {
    var getdata = ajax_data("sales/quotation/product_edit", "&quo=" + $(ele).data("id") + "&myid=" + $(ele).data("access"));

    $("#editform").html(getdata);
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
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        }
    });

    $('.datatable-columns').dataTable({
        columnDefs: [
            { 'max-width': '10%', 'targets': 0 }
        ]
    });


}

var n_equ = $(".form-item").length;
function clone_product(ele) {
    $('.modal').css('overflow-y', 'auto');
    console.log(n_equ);
    var equ = ajax_data("sales/quotation/product_clone", "&n_equ=" + n_equ + "&id_quo=" + $(ele).data("id_quo"));
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
    var elev = "<a href='#' data-toggle='modal' data-target='#m_modal2' onClick='BarangModalOther(" + $(ele).data("idpro") + ")'>Barang Ganti Baru</a>";

    $(".id_product").select2({
        allowClear: true,
        placeholder: "Cari Product",
        language: {
            noResults: function () {
                return $(elev);
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
    var qtyes = $(".formedit").find("select, textarea, input").serializeArray()[3]['value'];
    var hargas = $(".formedit").find("select, textarea, input").serializeArray()[4]['value'];
    if (qtyes == '' || hargas == '') {
        swal({
            title: "Oops",
            text: "Semua harus diisi kecuali catatan optional",
            icon: "warning",
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
}
function save_new(params) {
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
                url: main_url + "sales/quotation/save_addproduct",
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
        placeholder: 'Isi Dengan Product Revisi Request Anda'
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

function ShowVendor(ele) {
    if (ele == '' || ele == null) {
        swal(
            'Oops...',
            'Belum ada rekomendasi vendor',
            'error'
        )
    } else {
        var getdata = ajax_data("sales/vendor_detail", "&id_vendor=" + ele);
        $('#m_modal').modal('show');
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Detail Vendor");

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

function splitPO(ele) {
    $("#p_qty_" + $(ele).data("idpro")).attr("readonly", false);
    var equ = ajax_data("sales/quotation/split_po",
        "&id_quo=" + $(ele).data("id_quo") + "&idpro=" + $(ele).data("idpro") + "&n_equ=" + n_equ);
    $("#split_equ_" + $(ele).data("idpro")).html(equ);


    $(".stock_new").select2({
        allowClear: true,
        placeholder: "--Pilih Status--",
        width: "100%"
    });
    $("#vendor_new").select2({
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
    n_equ++;

}

function remove_split(id) {
    $("#row_item_" + id).remove();
}

function save_split(params) {
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
                url: main_url + "sales/quotation/exec_split_po",
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

function DeleteSplit(ele) {
    swal({
        title: "Are you sure?",
        text: "Delete this split!",
        icon: "warning",
        button: "Yes, delete it!"
    }).then((result) => {
        if (result) {

            $("#p_qty_" + $(ele).data("idpro"));
            var equ = ajax_data("sales/quotation/delete_split_po",
                "&id_quo=" + $(ele).data("id_quo") + "&idpro=" + $(ele).data("idpro"));

            window.location.href = main_url + equ;
        }
    });
}