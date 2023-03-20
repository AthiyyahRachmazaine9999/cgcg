var DatatableUsers = {
    init: function () {
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            paging: false,
            info: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [6]
            }],
            ordering: true,
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
            },
            columnDefs: [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],

            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
        });
        var tables = $(".other_datatable").DataTable({
            autoWidth: true,
            paging: false,
            info: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [6]
            }],
            ordering: true,
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
            },
            columnDefs: [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],

            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
        });
        // $('.m_datatable tbody').on('click', 'tr', function () {
        //     var data    = table.row(this).data();
        //     var idp     = data[0];
        //     ShowProduct(idp);
        // });
    }
};

jQuery(document).ready(function () {
    $('body').toggleClass('sidebar-xs').removeClass('sidebar-mobile-main');
    DatatableUsers.init();
});


function EditInvoicesSales(ele) {
    var prospectRes = ajax_data("sales/editinvoices", "&idquo=" + $(ele).data('idquo'));
    $(".table_inv").html(prospectRes)
}


function ConfirmPayments(ele) {
    window.location.href = main_url + "finance/invoice/edit_invoice/" + $(ele).data('id_inv');
}



function EditStatus(ele) {
    var getdata = ajax_data("sales/quotation/status_edit", "&quo=" + $(ele).data("id"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Edit Status");

    $(".status").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "--Pilih List--",
        width: "100%",
        language: {
            noResults: function () {
                return $("<a href='#' data-toggle='modal' data-target='#m_modal2' onClick='StatusForm(this)'>Tambah Baru</a>");

            }
        },
    });
}

function ShowApproval(ele) {
    var getdata = ajax_data("sales/quotation/approval", "&quo=" + $(ele).data("id") + "&type=" + $(ele).data("type"));
    var head = $(ele).data("type") + " Order";
    $("#modalbody").html(getdata),
        $("#modaltitle").html(head.toUpperCase());
}



function Export(ele) {
    var type = $(ele).data("type");
    if(type=='invoice'){
        window.location.href = main_url+ 'sales/download/invoice_costum/' + $(ele).data("id")+"/"+$(ele).data("dbs");
    }else if(type=='so'){
        var getdata = ajax_data("sales/download/document/additional_note", "&quo=" + $(ele).data("id") + "&type=" + $(ele).data("type"));
        $("#modalbody").html(getdata),
            $("#modaltitle").html("Additional Note");

    $(".btn-saves").click(function(e) {
        e.preventDefault();
        var data = $('.form_noteSO').serialize();
        var title= $("#titles").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: main_url + "sales/download/document/save_note",
            method: 'POST',
            data: data,
            xhrFields: { responseType: 'blob' },
            success: function(data, response) {
                console.log(data);
                var blob = new Blob([data]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'MEG - SO' + title +'.pdf';
                link.click();
                window.location.reload();
            },
            error: function(error) {
                console.log(error)
            }
        });
    });
    }else{
    window.location.href = main_url+ 'sales/download/document/' + $(ele).data("id")+"/"+$(ele).data("type");
    }
}




function ExportMigrate(ele) {
    var type = $(ele).data("type");
    if(type=='invoice'){
        window.location.href = main_url+ 'sales/downloadmigrate/invoice_costum/' + $(ele).data("id")+"/"+$(ele).data("dbs");
    }else{
    window.location.href = main_url+ 'sales/downloadmigrate/document/' + $(ele).data("id")+"/"+$(ele).data("type");
    }
}
var n_equ = $("#table_cust").length;

function AddressComToWH(ele) {
    var getdata = ajax_data('sales/address_add', "&type=" + $(ele).data("type") + "&id=" + $(ele).data("id") +
        "&idquo=" + $(ele).data("id_quo") + "&n_equ=" + n_equ);
    $("#letaksini").html(getdata);
}

function AddressPic(ele) {
    var getdata = ajax_data('sales/address_add', "&type=" +
        $(ele).data("type") + "&id=" + $(ele).data("id") + "&idpic=" + $(ele).data("idpic") +
        "&idquo=" + $(ele).data("id_quo") + "&n_equ=" + n_equ);
    $("#nextedit").html(getdata);
}

function removeBtn(ele) {
    window.location.href = main_url + 'sales/remove_addwo/' + $(ele).data("id") + '/' + $(ele).data("id_quo") +
        '/' + $(ele).data("type");
}

function removefield(ele) {
    var type = $(ele).data("type");
    if (type == "tambah") {
        $("#tambah").remove();
    } else if (type == "edit") {
        $("#edit_" + type).remove();
    } else if (type == "tambah_pic") {
        $("#tambah_pic").remove();
    } else {
        $("#edit_pic").remove();
    }
}

function Edit_invoice(ele) {
    var getdata = ajax_data(
        "sales/quotation/edit_invoice",
        "&no_invoice=" + $(ele).data("no_invoice") + "&id_invoice=" + $(ele).data("id") +
        "&id_quo=" + $(ele).data("id_quo") + "&type=" + $(ele).data("type")
    );
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Update Invoice");
}

function KirimEmail(ele) {
    var getdata = ajax_data("sales/special/kirimsq", "&idsq=" + $(ele).data("id"));
    var valuetext = ajax_data("special/defaulttext", "&idsq=" + $(ele).data("id"));

    $("#modalbody").html(getdata),
        $("#modaltitle").html("Kirim Email Sales Quotation");

    $(".mail-list").select2({
        placeholder: "Masukan CC Mail",
        tags: true,
        tokenSeparators: [',', ' ']
    })

    $("#body").html(valuetext);

    $('.summernote').summernote({
        placeholder: 'Isi dengan kalimat anda sendiri, sales quotation (pdf) akan otomatis terkirim saat email ini dikirim',
        code: valuetext
    });
}

function SiapKirim(ele) {
    swal({
        title: "Sudah siap kirim?",
        text: "Setelah di generate, nomer do tidak bisa di cancel, silahkan hub administrator jika butuh bantuan",
        icon: "warning",
        button: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: false,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            var getdata = ajax_data(
                "sales/quotation/siapkirim",
                "&quo=" + $(ele).data("id")
            );
            if (getdata == "oke") {
                swal("Nice, nomer do berhasil di generate", {
                    icon: "success",
                });
                window.location.href = href;
            } else {
                swal("Ups, ada yang salah, error:"+getdata, {
                    icon: "warning",
                });
            }
        } else {
            swal("Siap kirim dibatalkan");
        }
    });
}

function UseStock(ele) {
    swal({
        title: "Pakai Stock ?",
        text: "Penggunaan stock akan merubah vendor yg direkomendasikan",
        icon: "warning",
        button: {
            cancel: {
                text: "Cancel",
                value: null,
                visible: false,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "OK",
                value: true,
                visible: true,
                className: "",
                closeModal: true,
            },
        },
    }).then((willDelete) => {
        if (willDelete) {
            var getdata = ajax_data(
                "sales/quotation/pakaistock",
                "&quo=" + $(ele).data("id") + "&sku=" + $(ele).data("sku")
            );
            if (getdata == "oke") {
                swal("Nice, penggunaan stock di confirm", {
                    icon: "success",
                });
                window.location.href = main_url + 'sales/quotation/'+$(ele).data("id");
            } else {
                swal("Ups, ada yang salah, error:"+getdata, {
                    icon: "warning",
                });
            }
        } else {
            swal("Set Stock dibatalkan");
        }
    });
    
}
function ShowDObalikan(ele) {
    var getdata = ajax_data(
        "sales/quotation/show_dobalikan",
        "&id=" + $(ele).data("id")
    );
    $("#modalbody").html(getdata),
        $("#modaltitle").html("List DO Balikan");
}


