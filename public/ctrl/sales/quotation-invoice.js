
var DatatableUsers = {
    init: function () {

        var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });

        var quo = $("#id").val();
        $("#partial").hide();
        $('.form-check-input-switch').bootstrapSwitch();

        $("#newinvoice").hide();
        $("#NewInvoice").click(function () {
            $("#newinvoice").show();
        });
        
        $("#cancelNew").click(function () {
            $("#newinvoice").hide();
        });
        
        $("#user").select2({
            allowClear: true,
            width: "100%",
            placeholder: "Pilih Nama"
        });
        $("#type").select2({
            allowClear: true,
            width: "100%",
        });

        $("#type").on("change", function (e) {
            e.preventDefault();

            var option = $('option:selected', this).val();
            if (option == 'partial') {
                $("#partial").show();
                $("#textinv").hide();
                $("#tabspartial").show();
            } else {

                $("#tabspartial").hide();
                $("#partial").hide();
                $("#textinv").show();

            }

        });
        $("#part").select2({
            allowClear: true,
            width: "100%",
            placeholder: "Pilih Type Invoice"
        });
        $("#part").on("change", function (e) {
            e.preventDefault();

            var option = $('option:selected', this).val();
            var getdata = ajax_data("sales/download/invoice_tab", "&jenis=" + option + "&quo=" + quo);
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html);
            });
            $('.form-check-input-switch').bootstrapSwitch();
            $("#tabspartial").html(getdata);

        });
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
    }
};

jQuery(document).ready(function () {
    DatatableUsers.init();
});

function EditInvoice(ele) {
    var getdata = ajax_data("sales/download/invoice_edit", "&idinv=" + $(ele).data("id") + "&kondisi=" + $(ele).data("kondisi") + "&type=" + $(ele).data("type"));
    $('#m_modal').modal('show');
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Edit Invoice "+$(ele).data("noinvoice"));
    $(".m_popup").DataTable();
    
    $("#user_edit").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        width: "100%",
        placeholder: "Pilih Nama"
    });

}

function CetakInvoice(ele) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: main_url + "sales/download/invoice_cetak",
        data: {"idinv" : $(ele).data("id") , "kondisi" : $(ele).data("kondisi")},
        xhrFields: { responseType: 'blob' },
        success: function(data, response) {
            console.log(data);
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - "+$(ele).data("noinvoice")+".pdf";
            link.click();
            if (response == "success") {
                window.location.reload();
            }
        }
    });
}

function DeleteInvoice(ele) {
    var getdata = ajax_data("sales/download/invoice_delete", "&idinv=" + $(ele).data("id") + "&kondisi=" + $(ele).data("kondisi"));
    $('#m_modal').modal('show');
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Delete Invoice "+$(ele).data("noinvoice"));
}

function formPI(ele) {
    window.location.href = main_url+ 'sales/download/proforma_invoice/' + $(ele).data("id")
}
