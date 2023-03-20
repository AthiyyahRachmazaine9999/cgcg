var DatatableCash = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            orderCellsTop: true,
            columnDefs: [
                // { width: "50%", targets: [0, 1] },
                { width: "50%", targets: [2, 1] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [4, "desc"]
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
                url: main_url + "finance/get_cash_adv",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "no_cashadv",
                render: function(data, type, row) {
                    if (data == null) {
                        var color = "badge badge-flat border-warning text-danger-600 d-block";
                        var text = "Number is delayed";
                    } else {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    }
                    return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                }
            }, {
                "data": "emp_id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "tujuan",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "nominal",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 0)
            }, {
                "data": "status",
                "className": "text-center",
                render: function(data, type, row) {
                    if (data == "Pending" || data == "Need Approval") {
                        var color = "badge badge-flat border-warning text-warning-600 d-block";
                        var text = data;
                    } else if (data == "Rejected") {
                        var color = "badge badge-flat border-danger text-danger-600 d-block";
                        var text = data;
                    } else if (data == "Approved" && row.app_hr != null) {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data + " by Management";
                    } else if (data == "Approved" && row.app_hr == null) {
                        var color = "badge badge-flat border-info text-info-600 d-block";
                        var text = data;
                    } else {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    }

                    if (row.ref == "none") {
                        var now = "";
                    } else {
                        var now = row.ref;
                    }
                    return '<span class="' + color + 'd-block text-center">' + text + '</span>' +
                        '<br><br>' + '<span class="badge border-info text-info d-block">' + now + '</span>';
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
                    if (row.user == "other") {
                        if (row.status == "Pending") {
                            var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            var ajukan = '<a href="' + current_url + '' + data + '/ajukan" class="dropdown-item"><i class="fas fa-check-double text-primary"></i> Ajukan</a>';
                            var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                        } else {
                            var edit = '';
                            var del = "";
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        }
                    } else if (row.user == "management") {
                        if (row.status == "Pending" && row.by == "edit") {
                            var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                            var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        } else if (row.status == "Approved") {
                            // var ajukan = '<a onClick="get_Complete(this)" data-id ="' + data + '" class="dropdown-item" data-toggle="modal" data-target="#m_modal"><i class="fas fa-check-double text-primary" ></i> Complete</a>';
                            var ajukan = ""
                            var edit = '';
                            var del = "";
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                        } else {
                            var edit = '';
                            var del = "";
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        }
                    } else if (row.user == "finance") {
                        if (row.status == "Pending" && row.by == "edit") {
                            var edit = '<a href="' + current_url + '' + data + '/edit_finance" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                            var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        } else if (row.status == "Completed" || row.status == "Rejected") {
                            var edit = '';
                            var del = "";
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        } else {
                            // var ajukan = '<a onClick="get_Complete(this)" data-id ="' + data + '" class="dropdown-item" data-toggle="modal" data-target="#m_modal"><i class="fas fa-check-double text-primary" ></i> Complete</a>';
                            var edit = '<a href="' + current_url + '' + data + '/edit_finance" class="dropdown-item"><i class="fas fa-pencil-alt text-primary"></i> Edit</a>';
                            var del = '<a href="' + current_url + '' + data + '/reject_finance" class="dropdown-item"><i class="fas fa-calendar-times text-warning"></i> Reject</a>';
                            var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var ajukan = '';
                        }
                    } else {
                        var edit = '';
                        var del = "";
                        var det = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                        var ajukan = '';
                    }
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                        edit + det + del + ajukan + '</div ></div ></div >'
                }
            }, ]
        });
    }
};
jQuery(document).ready(function() {
    DatatableCash.init();
});

function get_Complete(ele) {
    var getdata = ajax_data('finance/cash_advance/complete_cash', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail");
                $("#trf").datepicker({
                    autoclose: !0,
                    format: "yyyy-mm-dd"
                });

}