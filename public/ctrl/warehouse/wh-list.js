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
                { width: "5%", targets: [1] },
                { width: "20%", targets: [2, 3] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[0, "desc"]],
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
                url: main_url + "warehouse/get_warehouse",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'type': "in"
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
                "data": "po_number",
                render: function (data, type, row) {
                    return data
                }
            }, {
                "data": "id_quo",
                render: function (data, type, row) {
                    if(data==0){
                        data="Stock";
                    }else{
                        data=data;
                    }
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
                "className": "text-center",
                render: function (data, type, row) {
                    return data;
                }
            },]
        });
        $('.m_datatable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            window.location.href = main_url + "warehouse/inbound/" + data.po_number;
        });

        // outbound

        var tables = $(".m_outbound").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [0, 1] },
                { width: "25%", targets: [2] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[0, "desc"]],
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
                url: main_url + "warehouse/get_outbound",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'type': "in"
                }
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
            columns: [
                {
                    "data": "id",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "no_wo",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "id_quo",
                    render: function (data, type, row) {
                        if (data == 0) {
                            data = "Stock";
                        } else {
                            data = data;
                        }
                        return data;
                    }
                }, {
                    "data": "id_cust",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "barang",
                    "className": "text-center",
                    render: function (data, type, row) {
                        return data;
                    }
                }, {
                    "data": "status",
                    "className": "text-center",
                    render: function (data, type, row) {
                        if (data == 'delivery') {
                            var colors = "primary";
                        } else {
                            var colors = "danger";
                        }
                        return '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + data.toUpperCase() + '</span>'
                    }
                }, {
                    "data": "created_at",
                    render: function (data, type, row) {
                        return data;
                    }
                },]
        });
        $('.m_outbound tbody').on('click', 'tr', function () {
            var data = tables.row(this).data();
            window.location.href = main_url + "warehouse/outbound/" + data.id;
        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});