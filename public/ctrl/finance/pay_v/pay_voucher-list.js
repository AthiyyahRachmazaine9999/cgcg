var DatatableVoucher = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [5, 1] },
                { width: "30%", targets: [1, 2, 3] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            className: 'select-checkbox',
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
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
                url: main_url + "finance/get_payment",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },
            columns: [{
                "data": "id",
                render: function(data, type, row) {
                    return '<input type="checkbox" name="check" class"Check" data-id="' + data + '" id="myCheck" class="center">';
                }
            }, {
                "data": "tujuan",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "no_payment",
                render: function(data, type, row) {
                    if (data == null || data=='') {
                        var a = '-';
                    } else {
                        var a = data;
                    }
                    return '<p class="text-center">' + a + '</p>';
                }
            }, {
                "data": "id_vendor",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "no_invoice",
                "className": "text-center",
                render: function(data, type, row) {
                    if (data == null) {
                        var data = row.performa_invoice == null ? '--' : row.performa_invoice;
                    } else {
                        var data = data;
                    }

                    if (row.no_efaktur == null) {
                        var now = "-";
                    } else {
                        var now = row.no_efaktur;
                    }
                    return '<span class="badge badge-flat border-info text-info-600 d-block">' + data + '</span>' +
                        '<br>' + now;
                }
            }, {
                "data": "status",
                render: function(data, type, row) {
                    if (data == "Reject" || data == "Rejected By HRD" || data == "Rejected By Supervisor") {
                        var color = "badge badge-flat border-danger text-danger-600 d-block";
                        var text = data;
                    } else if (data == "Pending") {
                        var color = "badge badge-flat border-warning text-warning-600 d-block";
                        var text = "PENDING";
                    } else if (data == "Approved" || data == "Approved By HRD" || data == "Submitted By Finance") {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    } else {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    }
                    return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                }
            }, {
                "data": "created_at",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className": "text-center",
                render: function(data, type, row) {
                    if (row.status == "Pending") {
                        var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                        var del = ' <a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                    } else {
                        var edit = "";
                        var del = "";
                    }
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                        edit + '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>' +
                        del + '</div ></div ></div >'
                }
            }, ]
        });
        $('#PrintPayment').on('click', function() {
            var ele = [];
            $("input:checkbox[name=check]:checked").each(function() {
                var click = ele.push($(this).data('id'));
                if (ele.length > 2) {
                    window.location.href = main_url + "finance/download/payment_check/" + "more2";
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: main_url + "finance/download/payment_check_double/" + ele,
                        method: 'POST',
                        data: ele,
                        success: function(response) {
                            if (response.info) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: "POST",
                                    url: main_url + "finance/download/download_double/" + ele,
                                    data: ele,
                                    xhrFields: { responseType: 'blob' },
                                    success: function(data, response) {
                                        var blob = new Blob([data]);
                                        var link = document.createElement('a');
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = "Payment Voucher.pdf";
                                        link.click();
                                        if (response == "success") {
                                            window.location.reload();
                                        }
                                    }

                                })
                            }
                        }
                    });
                }
            });
        })
    }
};
jQuery(document).ready(function() {
    DatatableVoucher.init();
});

function batas() {}