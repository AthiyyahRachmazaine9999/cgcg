var DatatableUsers = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "25%", targets: [1] },
                { width: "15%", targets: [2, 3] },
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
                url: main_url + "warehouse/get_inventory",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
            columns: [
                {
                    "data": "sku",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "barang",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "qty",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 0)
                }, {
                    "data": "sisa",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 0)
                }, {
                    "data": "jenis",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "created_at",
                    "className": "text-center",
                    render: function (data, type, row) {
                        return data;
                    }
                },]
        });
        $('.m_datatable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            window.location.href = main_url + "warehouse/inventory/" + data.sku;
        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});