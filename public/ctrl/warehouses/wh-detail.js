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

function All_history(ele) {
    var getdata = ajax_data("warehouse/warehouse_inbound/history_inbound", "&id=" + $(ele).data("id"));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("All History");
}


function add_note_inbound(ele) {
    var getdata = ajax_data("warehouse/warehouse_inbound/add_note", "&id=" + $(ele).data("id"));
    $("#modalbody2").html(getdata),
        $("#modaltitle2").html("Add Notes");
}





function ChangeAlamat(ele) {

    if ($(ele).data("type") == 'new') {
        var judul = "Tambah Alamat";
    } else {
        var judul = "Ganti Alamat";
    }
    var getdata = ajax_data("warehouse/kirim_outbound/ganti_alamat", "&idwo=" + $(ele).data("id") + "&type=" + $(ele).data("type") + "&det=" + $(ele).data("detail"));
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
            var getdata = ajax_data("warehouse/kirim_outbound/delete_alamat", "&idwo=" + $(ele).data("id") + "&det=" + $(ele).data("detail"));
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
    var getdata = ajax_data("warehouse/warehouse_outbound/DO_cetak", "&no_wh_out=" + $(ele).data("no_wh_out") +
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
    $(".tanggal_up").datepicker({
        autoclose: !0,
        format: "yyyy-mm-dd"
    });


    $("#type_cetak").on("change", function(e) {
        e.preventDefault();
        var option = $('option:selected', this).val();
        if (option === 'ups') {
            var no_do = $("#nodos").val();
            var id_wh = $("#id_outbounds").val();
            var getdata = ajax_data('warehouse/warehouse_outbound/editbarang_pengiriman', '&id_wh_out=' + id_wh + "&no_wh_out=" + no_do);
            $('.form_update').html(getdata);
                $(".tanggal_up").datepicker({
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                });
                $("#add_wh").select2({
                    allowClear: true,
                    placeholder: "Pilih Alamat"
                }); 
        } else if(option==="noup") {
            var no_do = $("#nodos").val();
            var id_wh = $("#id_outbounds").val();
            var ajx   = ajax_data('warehouse/warehouse_outbound/DO_cetak', '&id_wh_out=' + id_wh + "&no_wh_out=" + no_do + "&type=" + "no_up");
            $('.form_update').html(ajx);
        }
    });



    $(".btn-submit").click(function(e) {
        e.preventDefault();

        var data = $('.form-submits').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + "warehouse/warehouse_outbound/update_pengiriman",
            method: 'POST',
            data: data,
            xhrFields: { responseType: 'blob' },
            success: function(data, response) {
                console.log(data);
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "MEG - DO.pdf";
                link.click();
                window.location.reload();
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

    var data = $('.check_pengiriman').serialize();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: main_url + "warehouse/warehouse_outbound/store",
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
                        window.location.reload();
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
        url: main_url + "warehouse/warehouse_outbound/view_do",
        method: 'POST',
        data: data,
        success: function(data, response) {
            $("#modalbody").html(data),
                $("#modaltitle").html("DELIVERY CHECK")

            $(".alamats").select2({
                allowClear: true,
                placeholder: "Pilih Alamat"
            });
            $(".type_do").select2({
                allowClear: true,
                placeholder: "Pilih Type"
            });
            $(".tgl_kirim").datepicker({
                todayHighlight: !0,
                autoclose: !0,
                format: "dd-mm-yyyy"
            });

            $(".saveKirim").click(function(e) {
                e.preventDefault();

                var data = $('.check_pengiriman').serialize();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: main_url + "warehouse/warehouse_outbound/store",
                    method: 'POST',
                    data: data,
                    xhrFields: { responseType: 'blob' },
                    success: function(data, response) {
                        var blob = new Blob([data]);
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = "MEG - DO.pdf";
                        link.click();
                        window.location.reload();
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            });


            $(".rekapKirim").click(function(e) {
                e.preventDefault();

                var rekap = $('.inirekap_pengiriman').serialize();
                console.log(rekap);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: main_url + "warehouse/warehouse_outbound/store_rekap",
                    method: 'POST',
                    data: rekap,
                    xhrFields: { responseType: 'blob' },
                    success: function(data, response) {
                        var blob2 = new Blob([data]);
                        var linked = document.createElement('a');
                        linked.href = window.URL.createObjectURL(blob2);
                        linked.download = "MEG - DO Rekap.pdf";
                        linked.click();
                        // window.location.reload();
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
    var dataq = "&alamat=" + $(ele).data("alamat") + "&type=" + $(ele).data("type") + "&id=" + $(ele).data("id");

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: main_url + "warehouse/kirim/cetak",
        data: dataq,
        xhrFields: { responseType: 'blob' },
        success: function(data) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - DO.pdf";
            link.click();
            window.location.reload();
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

    $("#m_form").validate({

        submitHandler: function(e) {

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
                        success: function(res) {
                            $('#m_modal').modal('toggle');
                        },
                        error: function(res) {
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

    if (type == 'main') {
        var noresi = $("#resi_cust").val();
        var idkurir = $("#kurir_cust").val();
    } else {
        var noresi = $("#resi_" + adrs).val();
        var idkurir = $("#kurir_" + adrs).val();
    }
    console.log(noresi + "," + idkurir);

    var getdata = ajax_data("warehouse/warehouse_outbound/kirim/saveresi",
        "&alamat=" + adrs + "&type=" + type + "&idwo=" + $(ele).data("idwo") + "&resi=" + noresi + "&kurir=" + idkurir
    );

    swal({
        title: "Success",
        text: getdata,
        icon: "success",
    });

}



function ResiUpload(ele)
{
    var type = $(ele).data("type");
    var adrs = $(ele).data("alamat");
    
    var getdata = ajax_data("warehouse/warehouse_outbound/kirim/upload_resi", 
    "&alamat=" + adrs + "&type=" + type + "&idwo=" + $(ele).data("idwo") + "&id_resi=" + $(ele).data("id")
    );

    $("#modalbody").html(getdata),
    $("#modaltitle").html("File Resi");
}


function finishkirim(ele) {
    var type = $(ele).data("type");
    var adrs = $(ele).data("alamat");

    var getdata = ajax_data("warehouse/warehouse_outbound/kirim/finish",
        "&alamat=" + adrs + "&type=" + type + "&idwo=" + $(ele).data("idwo")
    );
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Pengiriman selesai");
}

function detailDO_click(ele) {
    if ($(ele).data("type") == "closes") {
        $('#m_modal').modal('toggle');
    } else {
        var getdata = ajax_data("warehouse/warehouse_outbound/showDO_details", "&no_wh_out=" + $(ele).data("no_wh_out") + "&num=" + $(ele).data("no") + "&id_split="+$(ele).data('id_split'));
        $("#modalbody").html(getdata),
        $("#modaltitle").html("Details - "+$(ele).data("no_wh_out"));
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


function Download_Excel(ele) {
    if ($(ele).data('type') == "format") {
        window.location.href = main_url +
            "warehouse/warehouse_outbound/download_excel/" +
            $(ele).data('type') + "/" + $(ele).data('id_quo') + "/" + $(ele).data("id_outbound") +  "/" + $(ele).data("id_split");
    } else {
        var ajax = ajax_data('warehouse/warehouse_outbound/upload_sn', "&type=" + $(ele).data("type") + "&id_quo=" + $(ele).data("id_quo") 
        + "&id_out=" + $(ele).data('id_outbound') + "&id_split=" + $(ele).data('id_split'));
        $("#modalbody2").html(ajax),
            $("#modaltitle2").html('Upload SN');
    }
}

function DownloadExcel_inbound(ele) {
    if ($(ele).data('type') == "format") {
        window.location.href = main_url +
            "warehouse/warehouse_outbound/download_excel_inbound/" +
            $(ele).data('type') + "/" + $(ele).data('id_quo') + "/" + $(ele).data('id');
    } else {
        var ajax = ajax_data('warehouse/warehouse_outbound/upload_sn_inbound', "&type=" + $(ele).data("type") 
        + "&id_quo=" + $(ele).data("id_quo") 
        + "&id=" + $(ele).data('id') 
        + "&id_po=" + $(ele).data('id_po'));
        $("#modalbody2").html(ajax),
            $("#modaltitle2").html('Upload SN');
    }
}


function add_barang(ele)
{
 if($(ele).data("type")=="hapus_row")
 {
    $(".row_tbl_" + $(ele).data("equ")).remove();
 }else if($(ele).data('type') == "hapus_row_data")
 {
    var no_do = $("#nodos").val();
    var id_wh = $("#id_outbounds").val();
    var quo   = $("#quoss").val();

    var getdata = ajax_data('warehouse/warehouse_outbound/remove_row', "&id=" + $(ele).data("id") + "&no_do=" + no_do + "&id_wh=" + id_wh + "&id_quo=" + quo);
    $("#tbl_row_" + $(ele).data("id")).remove();
    // console.log($(ele).data("equ"));
 }else{
    var no_do = $("#nodos").val();
    var id_wh = $("#id_outbounds").val();
    var quo   = $("#quoss").val();

    var table = document.getElementById('tables_product');
    var n_equ = table.rows.length;
    n_equ++;
    var ajx = ajax_data('warehouse/warehouse_outbound/add_row', '&id_wh_out=' + id_wh 
    + "&quo=" + quo + "&n_equ=" + n_equ);

    $(".row_tabel").before(ajx);
    $(".sel_pro").select2({
        allowClear: true,
        placeholder: "Pilih Barang"
    });
  }
}



function noDO_delete(ele)
{
    swal({
      title     : "Menghapus No.DO "+$(ele).data("no_wh_out")+" ?",
      text      : "Setelah dihapus, data ini tidak dapat di kembalikan.",
      icon      : "warning",
      buttons   : true,
      dangerMode: true,
    })
    .then((willDelete) => {
     if (willDelete) {
            swal({
                text: "Alasan Kenapa No. DO dihapus ? (*Wajib Diisi):",
                content: "input",
                buttons : true,
            })
            .then(message => {
                if (message != "" && message != null) {
                    var getdata = ajax_data("warehouse/warehouse_outbound/DO_delete", "&no_wh_out=" + $(ele).data("no_wh_out") +
                        "&kirim_addr=" + $(ele).data("kirim_addr") + "&id_wh_out=" + $(ele).data("id_wh_out")
                        + "&value=" + message);
                    swal("Berhasil ! No. DO sudah dihapus.", {
                        icon: "success",
                    }).then((isConfirm)=>{
                        window.location.reload();
                    });
                }else{
                    swal("Oops !", "Tidak jadi di hapus ya", "error");
                }
            });            

      } else {
        swal("Oke.. Data ini masih aman tersimpan.", {
            icon: "info",
        });
      }
    });
    
}

function DO_Balikan(ele) {
    var getdata = ajax_data("warehouse/document/upload", '&id_wh_out=' + $(ele).data("id_wh_out") + '&no_do=' + $(ele).data("no_wh_out"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Upload DO balikan "+$(ele).data("no_wh_out"));

        myDropzone = new Dropzone('div#imageUpload', {
            autoProcessQueue: false,
            uploadMultiple: false,
            maxFiles: 1,
            acceptedFiles: ".pdf",
            maxFilesize: 8, // MB
            addRemoveLinks: true,
            dictDefaultMessage: "Drag Your File Here",
            dictRemoveFile: 'Remove',
            dictFileTooBig: 'File is bigger than 8MB',
            clickable: true,
            url: main_url+'warehouse/document/doupload',
            init: function (file) {
            var myDropzone = this;
            $("#UploaderBtn").click(function (e) {
                e.preventDefault();
                if ( $("form[name='demoform']").serialize() ) {
                    myDropzone.processQueue();
                }
                return false;
            });
    
            this.on('sending', function (file, xhr, formData) {
                var data =$("form[name='demoform']").serializeArray();
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });

            this.on("complete", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $('#m_modal').modal('toggle');
                    swal( 'Success!','File Uploded Successfully!','success');
                }
              });
    
        },
    });
}
