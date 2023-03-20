var DatatableUsers = {
    init: function () {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });
        $(".tanggal_up").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
        $(".qty_sisas").val() == "" ? $(".p_sisa").hide() : $(".p_sisa").show();
        $(".kirim").val('');
        $('.form-check-input-switch').bootstrapSwitch();
        $(".address").select2({
            allowClear: true,
        }),
            $("#m_form").validate({
                rules: {
                    title: {
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
            });

        $("#m_out").validate({
            rules: {
                title: {
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
        });
        $(".id_kurir").select2({
            allowClear: true,
            placeholder: "Cari Kurir",
            language: {
                noResults: function () {
                    return $("<a href='#' data-toggle='modal' data-target='#m_modal' onClick='ShippingForm(this)'>Tambah Baru</a>");

                }
            },
            ajax: {
                url: main_url + 'distribution/find_shipping',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
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
        var tables = $(".m_outbound").DataTable({
            autoWidth: false,
            filter:false,
            paginate:false,
            info:false,
            columnDefs: [
                { width: "15%", targets: [1] },
                { width: "15%", targets: [2,3] },
                { width: "20%", targets: [4] },
            ],
            ordering: true,
        });
    }

};
jQuery(document).ready(function () {
    DatatableUsers.init();
});

function ChangeAlamat(ele) {

    if ($(ele).data("type") == 'new') {
        var judul = "Tambah Alamat";
    } else {
        var judul = "Ganti Alamat";
    }
    var getdata = ajax_data("warehouse/kirim/ganti_alamat", "&idwo=" + $(ele).data("id") + "&type=" + $(ele).data("type") + "&det=" + $(ele).data("detail"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html(judul);
}

function DeleteAlamat(ele) {
    swal({
        title: "Delete " + $(ele).data("name"),
        text: "Are you sure?",
        type: "warning",
        showCancelButton: 0,
        button: "Yes, delete it",
    }).then((isConfirm) => {
        if (isConfirm) {
            var getdata = ajax_data("warehouse/kirim/delete_alamat", "&idwo=" + $(ele).data("id") + "&det=" + $(ele).data("detail"));
            window.location.href = main_url + getdata;
        } else {
            swal("Cancelled", "Tidak jadi dihapus ya", "error");
        }

    })
}

function KirimBarang(ele) {
    var getdata = ajax_data("warehouse/kirim/kirimbarang", "&idwo=" + $(ele).data("id"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Kirim Barang");
}

function DO_cetak(ele) {
    var getdata = ajax_data("warehouse/outbound/DO_cetak", "&no_wh_out=" + $(ele).data("no_wh_out") +
        "&kirim_addr=" + $(ele).data("kirim_addr") + "&id_wh_out=" + $(ele).data("id_wh_out"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Delivery Order");

    $("#type_cetak").select2({
        allowClear: true,
        placeholder: "Pilih"
    });
        $("#add_wh").select2({
        allowClear: true,
        placeholder: "Pilih Alamat"
    });
    $(".update_qty").hide();
    $(".update_tgl").hide();
    $(".ups_addr").hide();
    $(".update_name").hide();
    $(".up_keterangans").hide();
    $("#type_cetak").on("change", function(e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        if (option === '') {
            $(".update_qty").hide();
            $(".read_qty").show();

            $(".update_tgl").hide();
            $(".read_tgl").show();

            $(".ups_addr").hide();
            $(".read_add").show();

            $(".update_name").hide();
            $(".read_name").show();

            $(".up_keterangans").hide();
            $(".keterangans").show();
        } else if (option === 'ups') {
            $(".update_qty").show();
            $(".read_qty").hide();

            $(".update_tgl").show();
            $(".read_tgl").hide();

            $(".ups_addr").show();
            $(".read_add").hide();

            $(".update_name").show();
            $(".read_name").hide();

            $(".up_keterangans").show();
            $(".keterangans").hide();
        } else {
            $(".update_qty").hide();
            $(".read_qty").show();

            $(".update_tgl").hide();
            $(".read_tgl").show();

            $(".ups_addr").hide();
            $(".read_add").show();

            $(".update_name").hide();
            $(".read_name").show();

            $(".up_keterangans").hide();
            $(".keterangans").show();
        }
    });


    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var data = $('.form-submits').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + "warehouse/outbound/update_pengiriman",
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    var dataq = $('.form-submits').serialize();
                    console.log(data);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: main_url + "warehouse/outbound/CetakDO_Update",
                        data: dataq,
                        xhrFields: { responseType: 'blob' },
                        success: function(data, response) {
                            console.log(data);
                            var blob = new Blob([data]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "MEG - DO.pdf";
                            link.click();
                            if (response == "success") {
                                window.location.reload();
                            }
                        }
                    })

                } else {
                    alert("Error")
                }
            },
            error: function(error) {
                console.log(error)
            }
        });
    });

}

function HitungSisa(ele) {

}

$(".saveKirim").click(function(e) {
    e.preventDefault();

    var data = $('.show_outs').serialize();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: main_url + "warehouse/outbound/store",
        method: 'POST',
        data: data,
        success: function(response) {
            console.log(response);
            if (response.success && response.qty_kirim != null) {
                var dataq = $('.show_outs').serialize();
                var sent = response.sent;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: main_url + "warehouse/outbound/kirim_cetakDO",
                    data: dataq + sent,
                    xhrFields: { responseType: 'blob' },
                    success: function(data, response) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "MEG - DO.pdf";
                        link.click();
                        if (response == "success") {
                            window.location.reload();
                        }
                    }
                })

            } else {
                swal(
                    'Oops...',
                    'Please, Complete The Fields!',
                    'error'
                )
            }
        },
        error: function(error) {
            console.log(error)
        }
    });
});


$(".firstKirim").click(function(e) {
    e.preventDefault();

    var data = $('.show_outs').serialize();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: main_url + "warehouse/outbound/view_do",
        method: 'POST',
        data: data,
        success: function(data, response) {
            console.log(response);
            $(".tbl_penerima").html(data);

            $(".saveKirim").click(function(e) {
                e.preventDefault();

                var data = $('.show_outs').serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: main_url + "warehouse/outbound/store",
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(response);
                        if (response.success && response.qty_kirim != null) {
                            var dataq = $('.show_outs').serialize();
                            var sent = response.sent;
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: main_url + "warehouse/outbound/kirim_cetakDO",
                                data: dataq + sent,
                                xhrFields: { responseType: 'blob' },
                                success: function(data, response) {
                                    var blob = new Blob([data]);
                                    var link = document.createElement('a');
                                    link.href = window.URL.createObjectURL(blob);
                                    link.download = "MEG - DO.pdf";
                                    link.click();
                                    if (response == "success") {
                                        window.location.reload();
                                    }
                                }
                            })

                        } else {
                            swal(
                                'Oops...',
                                'Please, Complete The Fields!',
                                'error'
                            )
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            });
        },
        error: function(error) {
            console.log(error)
        }
    });
});




function CetakDO(ele) {
    // var getdata = ajax_data("warehouse/kirim/cetak", "&alamat=" + $(ele).data("alamat") + "&type=" + $(ele).data("type") + "&id=" + $(ele).data("id"));
    var dataq = "&alamat=" + $(ele).data("alamat") + "&type=" + $(ele).data("type") + "&id=" + $(ele).data("id");
    
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: main_url + "warehouse/kirim/cetak",
            data: dataq,
            xhrFields: { responseType: 'blob' },
            success: function (data) {
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "MEG - DO.pdf";
                link.click();
            }
        })
}

function ShippingForm(ele) {
    $(".id_kurir").select2("close");
    var prospectRes = ajax_data("distribution/new_shipping")

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("Tambah Data Forwarder")

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
                        url: main_url + "distribution/shipping_store",
                        data: datas,
                        success: function (res) {
                            $('#m_modal').modal('toggle');
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

function saveresi(ele) {
    var type = $(ele).data("type");
    var adrs = $(ele).data("alamat");

    if(type=='main'){
        var noresi = $("#resi_cust").val();
        var idkurir = $("#kurir_cust").val();
    }else{
        var noresi = $("#resi_"+adrs).val();
        var idkurir = $("#kurir_"+adrs).val();
    }
    console.log(noresi+","+idkurir);

    var getdata = ajax_data("warehouse/kirim/saveresi", 
    "&alamat=" + adrs + "&type=" + type + "&idwo=" + $(ele).data("idwo") + "&resi=" + noresi + "&kurir=" + idkurir
    );

    swal({
        title: "Success",
        text: getdata,
        icon: "success",
    });

}

function finishkirim(ele) {
    var type = $(ele).data("type");
    var adrs = $(ele).data("alamat");
    
    var getdata = ajax_data("warehouse/kirim/finish", 
    "&alamat=" + adrs + "&type=" + type + "&idwo=" + $(ele).data("idwo")
    );

    $("#modalbody").html(getdata),
    $("#modaltitle").html("Pengiriman selesai");
}

function detailDO_click(ele) {
    if ($(ele).data("type") == "closes") {
        $('#m_modal').modal('toggle');
    } else {
        var getdata = ajax_data("warehouse/outbound/showDO_details", "&no_wh_out=" + $(ele).data("no_wh_out") + "&num=" + $(ele).data("no"));
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Details");
    }
}

function Pinjam_Stock(ele) {
    if ($(ele).data("type") == "tambah") {
        var getdata = ajax_data("warehouse/inventory/addpinjam_stock", "&sku=" + $(ele).data("sku") + "&price=" + $(ele).data("price"));
        $('#modalbody').html(getdata);
        $('#modaltitle').html("Pinjam Stock");
    } else {
        var getdata = ajax_data("warehouse/outbound/showDO_details", "&no_wh_out=" + $(ele).data("no_wh_out") + "&num=" + $(ele).data("no"));
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Details");
    }
}