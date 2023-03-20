function DetailAlert(ele) {
    if ($(ele).data('type') == "pendingrfq") {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail Unfinish RFQ");


        $(".m_datatablepending").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'pendingrfq'
                }
            },

            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function(data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="text-center font-weight-bold">' + row.id_quo + '</span><br><br><span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>' + num
                }
            }, {
                "data": "quo_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<a href="' + main_url + 'sales/quotation/' + data + '" class="text-primary" target="_blank">Detail</a>';
                }
            }, ]
        });
    } else if ($(ele).data('type') == "nego") {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail SO Masih Negosiasi");


        $(".m_datatablepending").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'nego'
                }
            },

            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function(data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="text-center font-weight-bold">' + row.id_quo + '</span><br><br><span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>' + num
                }
            }, {
                "data": "quo_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<a href="' + main_url + 'sales/quotation/' + data + '" class="text-primary" target="_blank">Detail</a>';
                }
            }, ]
        });
    } else if ($(ele).data('type') == "unpaid" && $(ele).data('div') == 3 || $(ele).data('type') == "unpaid" && $(ele).data('div') == 7) {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail SO Unpaid");


        $(".m_datatableunpaid").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'unpaid',
                    'id_user': $(ele).data('id_user')
                }
            },

            columns: [{
                    "data": "id",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "id_quo",
                    "className": "text-center",
                    render: function(data, type, row) {
                        return '<span class="badge badge-pill badge-info">' + data + '</span></b>\
                  </p>\
                  </br>\
                  <p style="text-transform: uppercase"><b>' + row.quo_no + '';
                    }
                }, {
                    "data": "no_invoice",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "tgl_invoice",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "created_by",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "link",
                    "className": "text-center",
                    render: function(data, type, row) {
                        return '<a href="' + main_url + data + '" class="text-primary" target="_blank">Detail</a>';
                    }
                },
            ]
        });
    } else if ($(ele).data('type') == "nodoc") {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail SO");


        $(".m_datatablepending").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'nodoc'
                }
            },

            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function(data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="text-center font-weight-bold">' + row.id_quo + '</span><br><br><span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>' + num
                }
            }, {
                "data": "quo_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<a href="' + main_url + 'sales/quotation/' + data + '" class="text-primary" target="_blank">Detail</a>';
                }
            }, ]
        });
    } else if ($(ele).data('type') == "waitmodal") {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail SO Tanpa Modal");


        $(".m_datatablepending").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'waitmodal'
                }
            },

            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function(data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="text-center font-weight-bold">' + row.id_quo + '</span><br><br><span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>' + num
                }
            }, {
                "data": "quo_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<a href="' + main_url + 'sales/quotation/' + data + '" class="text-primary" target="_blank">Detail</a>';
                }
            }, ]
        });
    } else if ($(ele).data('type') == "waitout") {
        var ajx = ajax_data(
            "detail_info", "&id_user=" + $(ele).data('id_user') + "&div=" + $(ele).data('div') + "&type=" + $(ele).data('type'));

        $("#modalbody").html(ajx),
            $("#modaltitle").html("Detail DO Pending");


        $(".m_datatablepending").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [1, 2, 3, 4, 5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [0, "desc"],
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
                url: main_url + "ajax_detailinfo",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token,
                    'what': 'waitout',
                    'id_user':$(ele).data('id_user')
                }
            },

            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function(data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="text-center font-weight-bold">' + row.id_quo + '</span><br><br><span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>' + num
                }
            }, {
                "data": "quo_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "link",
                "className": "text-center",
                render: function(data, type, row) {
                    return '<a href="' + main_url + data + '" class="text-primary" target="_blank">Detail</a>';
                }
            }, ]
        });
    }else{
        $("#modalbody").html("<span class='text-danger text-center font-weight-bold'>SORRY, DATA CAN'T BE ACCESSED</span>"),
            $("#modaltitle").html("Details");
    }
}