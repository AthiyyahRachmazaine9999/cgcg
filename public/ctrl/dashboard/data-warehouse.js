var DatatableUsers = {
    init: function () {
        $("#quo_type").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "5%", targets: [6,2,4,5] },
                { width: "15%", targets: [1,3] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[ 5, "desc" ]],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_ Per Halaman',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: main_url + "sales/quotation/filter_home",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'what':'warehouse'
                }
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
            columns: [{
                "data": "id",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "no_po",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "id_quo",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "id_vendor",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                render: function (data, type, row) {
                    if (data == 'approve') {
                        var colors = "success";
                    } else {
                        var colors = "danger";
                    }
                    return '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + data.toUpperCase() + '</span>'
                }
            }, {
                "data": "position",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "created_at",
                "className":"text-center",
                render: function (data, type, row) {
                    return data;
                }
            },]
        });
        $('.m_datatable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            window.location.href = main_url + "warehouse/inbound/" + data.no_po;
        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});