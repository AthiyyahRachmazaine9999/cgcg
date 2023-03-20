var FormControls = {
    init: function () {
        ! function () {
            $("#jenis_po").select2({
                allowClear: true,
                placeholder: "Jenis Purchase Request",
            }),
            $("#id_customer").select2({
                allowClear: true,
                placeholder: "Pilih Perusahaan Cabang",
                language: {
                    noResults: function () {
                        return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='CustomerForm(this)'>Tambah Baru</a>");

                    }
                },
                ajax: {
                    url: main_url + 'sales/find_cabang',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama_perusahaan+ ' ' + item.cabang_name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            }), $("#id_vendor").select2({
                allowClear: true,
                placeholder: "Cari Vendor",
                language: {
                    noResults: function () {
                        return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='VendorForm(this)'>Tambah Baru</a>");

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
            }),$("#id_product").select2({
                allowClear: true,
                placeholder: "Cari Product",
                language: {
                    noResults: function () {
                        return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='BarangOther(this)'>Barang Baru</a>");

                    }
                },
                ajax: {
                    url: main_url + 'product/get_product',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: "[" + item.sku + "] " + item.name,
                                    id: item.product_id,
                                   
                                }
                            })
                        };
                    },
                    cache: true
                }
            }).on("change", function(e) {
                var skus = $("#id_product option:last-child").val();
                checkHarga(skus);

            }), $("#quo_type").select2({
                allowClear: true,
                placeholder: "Pilih Type"
            }), $("#id_sales").select2({
                allowClear: true,
                placeholder: "Pilih Sales"
            }), $("#quo_order_at").datepicker({
                todayHighlight: !0, autoclose: !0, format: "yyyy-mm-dd"
            }),
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
        }(), $("#m_form").validate({
            rules: {
                province: {
                    required: !0
                }, city: {
                    required: !0
                },
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
                    title: "Are you sure?",
                    text: "Save this data!",
                    icon: "warning",
                    button: "Yes, save it!"
                }).then((result) => {
                    if (result) {
                        e.submit();
                    }
                })
            }
        })
    }
};

jQuery(document).ready(function () {
    FormControls.init();
    $('#save_btn').attr('disabled', 'disabled');
    if ($("#quo_type").val() === '1') {
        $("#row_other").hide();
        $("#row_nominal").hide();
    } else if ($("#quo_type").val() === '') {
        $("#row_other").hide();
        $("#row_nominal").hide();
    } else {
        $("#row_nominal").show();
        $("#row_other").show();

    }

    if ($("#isedit").val() === 'yes') {
        $('#save_btn').removeAttr('disabled');
    }
});

$("#quo_type").on("change", function (e) {
    e.preventDefault();
    var option = $('option:selected', this).val();
    if (option === '1') {
        $("#row_other").hide();
        $("#quo_no").val('');
        $("#row_nominal").hide('');
        $("#quo_order_at").val('');
    }
    else if (option === '') {
        $("#row_other").hide();
        $("#quo_no").val('');
        $("#row_nominal").hide('');
        $("#quo_order_at").val('');
    }
    else {
        $("#row_nominal").show();
        $("#quo_no").val('');
        $("#quo_order_at").val('');
        $("#row_other").show();
    }
});

$("#add-row").click(function () {
    var data  = $('#id_product').select2('data');
    var price = $("#p_price").val();
    var qty   = $("#p_qty").val();
    var sub   = $("#p_sub").val();
    console.log(data);

    var sku    = data[0].id;
    var barang = data[0].text;
    

    if (sku == null) {
        alert("Silahkan Pilih atau tambahkan product request");
    } else if (qty == "") {
        alert("Jumlah Product belum diisi");
    } else {
        if(sku=='new'){
            var newsku = barang;
        }else{var newsku = sku;}

        var markup = "<tr><td class='text-center'><input type='checkbox' name='record'></td>\
      <td><input type='hidden' name='sku[]' value='"+ sku + "'><input type='hidden' name='newsku[]' value='"+ newsku + "'>" + barang + "</td>\
      <td class='text-center'><input type='hidden' name='price[]' value='"+ price + "'>" + price + "</td>\
      <td class='text-center'><input type='hidden' name='qty[]' value='"+ qty + "'>" + qty + "</td>\
      <td class='text-center'><input type='hidden' name='sub[]' value='"+ sub + "'>" + sub + "</td></tr>";

        $("table tbody").append(markup);
        document.getElementById("p_price").value = "";
        document.getElementById("p_qty").value = "";
        document.getElementById("p_sub").value = "";
        document.getElementById("id_product").value = "";
    }
    $('#save_btn').removeAttr('disabled');
});
$("#delete-row").click(function () {
    $("table tbody").find('input[name="record"]').each(function () {
        if ($(this).is(":checked")) {
            $(this).parents("tr").remove();
        }
    });
});

function checkHarga(ele) {
    var getdata = ajax_data("product/get_detail", "&sku=" + ele);
    $('#p_price').val(getdata.price)
}

function HitungSub() {
    var harga = $("#p_price").val();
    var qty = $("#p_qty").val();
    var sub = parseInt(harga * qty);
    $("#p_sub").val(sub);
}

function BarangOther(ele) {

    $("#id_product").select2("close");
    var newtemplate = ajax_data("product/request_other")
    $("#modalbody").html(newtemplate),
        $("#modaltitle").html("Request Barang")

    $('.summernote').summernote({
        placeholder: 'Isi Dengan Product Request Anda'
    });
    $("#btnSubmit").click(function () {
        $('#m_modal').modal('toggle');
        var html = $("#detail_barang").summernote("code");
        // $('#hiddenpro').val(html);
        var $newOption = $("<option selected='selected'></option>").val("new").text(html)

        $("#id_product").append($newOption).trigger('change');
    });

}

function CustomerForm(ele) {
     $("#id_customer").select2("close");
    var prospectRes = ajax_data("sales/new_cabang")

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("Tambah Data Cabang")

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
}

 function VendorForm(){
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

}