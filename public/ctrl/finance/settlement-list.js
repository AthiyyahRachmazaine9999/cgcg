var DatatableCash = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "30%", targets: [0] },
                { width: "15%", targets: [2, 3, 4] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [3, "desc"],
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
                url: main_url + "finance/get_settlement",

                // totalsmenustotalsmenus


                // totalsmenustotalsmenus
                
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },


            


            columns: [{
                    "data": "employee_id",
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong>' + '<br><br><em>' + row.dtls + '<em>';
                    }
                }, {
                    "data": "no_set",
                    render: function(data, type, row) {
                        if (row.no_ref == null) {
                            var now = "";
                        } else {
                            var now = row.no_ref;
                        }
                        return '<span class="badge badge-flat border-info text-info-600 d-block">' + data + '</span>' +
                            '<br>' + '<span class="badge border-info text-primary d-block">' + now + '</span>';
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
                        } else if (data == "Approved" && row.app == null || data == "Completed") {
                            var color = "badge badge-flat border-primary text-primary-600 d-block";
                            var text = data;
                        } else if (data == "Approved" && row.app != null) {
                            var color = "badge badge-flat border-primary text-primary-600 d-block";
                            var text = data + ' by Management';
                        } else if (data == "Rejected") {
                            var color = "badge badge-flat border-danger text-danger-600 d-block";
                            var text = data;
                        } else {
                            var color = "badge badge-flat border-primary text-primary-600 d-block";
                            var text = data;
                        }
                        return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                    }
                },
                {
                    "data": "created_at",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "id",
                    "className": "text-center",
                    render: function(data, type, row) {
                        if (row.user == "other") {
                            if (row.status == "Pending") {
                                var ajukan = '<a href="' + current_url + '' + data + '/ajukan" class="dropdown-item"><i class="fas fa-check-double text-primary"></i> Ajukan</a>';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            } else if (row.status == "Completed" || row.status == "Approved" || row.status == "Need Approval") {
                                var ajukan = "";
                                var edit = '';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = "";
                            } else {
                                var ajukan = "";
                                var edit = '';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = "";
                            }
                        } else if (row.user == "Management") {
                            if (row.status == "Pending" && row.by == "no_edit") {
                                var ajukan = "";
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var edit = '';
                                var del = '';
                            } else if (row.status == "Pending" && row.by == "edit") {
                                var ajukan = "";
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            } else if (row.status == "Completed" || row.status == "Approved" || row.status == "Need Approval") {
                                var ajukan = '';
                                var edit = '';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = "";
                            } else {
                                var ajukan = "";
                                var edit = '';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = "";
                            }
                        } else if (row.user == "Finance") {
                            if (row.status == "Pending") {
                                var ajukan = '<a href="' + current_url + '' + data + '/ajukan" class="dropdown-item"><i class="fas fa-check-double text-primary"></i> Ajukan</a>';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            } else if (row.status == "Rejected") {
                                var ajukan = "";
                                var edit = '';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = "";
                            } else {
                                var ajukan = "";
                                var edit = '<a href="' + current_url + '' + data + '/edit_finance" class="dropdown-item"><i class="fas fa-pencil-alt text-primary"></i> Edit</a>';
                                var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                                var del = '<a href="' + current_url + '' + data + '/reject_finance" class="dropdown-item"><i class="fas fa-calendar-times text-warning"></i> Reject</a>';
                            }
                        } else {
                            var ajukan = "";
                            var edit = '';
                            var detail = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>';
                            var del = "";
                        }
                        return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    ' + edit + detail + del + ajukan + '</div ></div ></div >'
                    }
                },
            ]

            
            

        });
    }
};
jQuery(document).ready(function() {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");    
    DatatableCash.init();
});

function set_Complete(ele) {
    var getdata = ajax_data('finance/settlement/complete_sets', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail")
}