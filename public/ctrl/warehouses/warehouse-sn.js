var DatatableUsers = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "5%", targets: [1] },
                { width: "20%", targets: [2, 3] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"]
            ],
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
                url: main_url + "warehouse/listsn/get_data",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                }
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "no_do",
                render: function(data, type, row) {
                    return data
                }
            }, {
                "data": "id_quo",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_cust",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "barang",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "sn",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "created_at",
                "className": "text-center",
                render: function(data, type, row) {
                    return data;
                }
            }, ]
        });

    }
};
jQuery(document).ready(function() {
    DatatableUsers.init();
});