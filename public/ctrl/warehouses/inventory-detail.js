var DatatableUsers = {
    init: function () {
        var tables = $(".m_outbound").DataTable({
            autoWidth: false,
            filter: false,
            paginate: false,
            info: false,
            columnDefs: [
                { width: "15%", targets: [1] },
                { width: "15%", targets: [2, 3] },
                { width: "20%", targets: [4] },
            ],
            ordering: true,
        });
    },
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});

function DO_Cetak(ele) {
    console.log($(ele).data("id"));
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: main_url + "warehouse/inventory/cetak_pinjam",
        data: "id=" + $(ele).data("id"),
        xhrFields: { responseType: 'blob' },
        success: function(data, response) {
            var blob = new Blob([data]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "MEG - DO/PJM.pdf";
            link.click();
            window.location.reload();
        }
    })
}

function DO_Edit(ele) {
    var getdata = ajax_data(
        "warehouse/inventory/editpinjam_stock",
        "&id=" + $(ele).data("id")
    );
    $("#modalbody").html(getdata);
    $("#modaltitle").html("Pinjam Stock");
    $("#id_customer").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "Cari Customer",
        language: {
            noResults: function () {
                return $(
                    "<a href='#' data-toggle='modal' data-target='#m_modal' onClick='CustomerForm(this)'>Tambah Baru</a>"
                );
            },
        },
        ajax: {
            url: main_url + "sales/find_customer",
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.company,
                            id: item.id,
                        };
                    }),
                };
            },
            cache: true,
        },
    });
}

function Pinjam_Stock(ele) {
    var getdata = ajax_data(
        "warehouse/inventory/addpinjam_stock",
        "&sku=" + $(ele).data("sku") + "&price=" + $(ele).data("price")
    );
    $("#modalbody").html(getdata);
    $("#modaltitle").html("Pinjam Stock");
    $("#id_customer").select2({
        allowClear: true,
        dropdownParent: $("#m_modal"),
        placeholder: "Cari Customer",
        language: {
            noResults: function () {
                return $(
                    "<a href='#' data-toggle='modal' data-target='#m_modal' onClick='CustomerForm(this)'>Tambah Baru</a>"
                );
            },
        },
        ajax: {
            url: main_url + "sales/find_customer",
            dataType: "json",
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.company,
                            id: item.id,
                        };
                    }),
                };
            },
            cache: true,
        },
    });
}
