var DatatableEmployee = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "30%", targets: [2] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [5, "desc"]
            ],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            serverSide: true,
            language: {
                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_ Per Halaman',
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> ',
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
                url: main_url + "hrm/request/get_leave",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },

            columns: [{
                "data": "employee_id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "type_leave",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "purpose",
                render: function(data, type, row) {
                    if(row.type_leave=="Annual Leave")
                    {
                        var detail =  row.start + ' s/d '+ row.end;

                    }else if(row.type_leave=="Special Leave")
                    {
                        var detail =  row.start + ' s/d '+ row.end;

                    }else if(row.type_leave=="Permission"){

                        var detail = row.end

                    }else if(row.type_leave=="Late Permission")
                    {
                        var detail = row.start + ' - '+ row.end;
                    }

                    if(data!=null)
                    {
                        if(data=="Lainnya")
                        {
                            var datas = '<strong>' + data +' : '+row.note + '</strong>';
                        }
                        else{
                            var datas = '<strong>' + data + '</strong>';
                        }
                    }else{
                        var datas = " - ";
                    }


                    return datas + '<br><br>'+ detail;
                }
            }, {
                "data": "status",
                render: function(data, type, row) {
                    if (data == "Rejected" || data == "Rejected By HRD" || data == "Rejected By Supervisor") {
                        var color = "badge badge-flat border-danger text-danger-600 d-block";
                        var text = data;
                    } else if (data == "Pending") {
                        var color = "badge badge-flat border-warning text-warning-600 d-block";
                        var text = data;
                    } else if (data == "Approved") {
                        var color = "badge badge-flat border-success text-success-600 d-block";
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
                    if (row.user == "other") {
                        if (row.status == "Pending") {
                            var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                            var del = ' <a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                        } else {
                            var edit = "";
                            var del = "";
                        }
                    } else if (row.user == "spv") {
                        if (row.status == "Pending" && row.no_user == row.created_by) {
                            var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                            var del = ' <a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                        } else {
                            var edit = "";
                            var del = "";
                        }
                    } else {
                        if(row.user == "manage")
                        {
                            if (row.status == "Pending") {
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = ' <a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            } else {
                                var edit = "";
                                var del = "";
                            }
                        }
                        else if(row.user == "hrs")
                        {
                            if (row.status == "Pending") {
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>';
                            }else if (row.status=="Rejected"){
                                var edit= "";
                                var del = "";
                            }else {
                                var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                                var del = "";
                            }
                        }else{
                            var edit = "";
                            var del = "";
                        }
                    }
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                        edit + '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>' +
                        del + '</div ></div ></div >'
                }
            }, ]
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});


function Reject_leave(ele) {
  window.location.href =
    main_url +
    "hrm/request/leave/reject/" +
    $(ele).data("id") +
    "/" +
    $(ele).data("type");
}

function Approve_leave(ele) {
  window.location.href =
    main_url +
    "hrm/request/leave/approve/" +
    $(ele).data("id") +
    "/" +
    $(ele).data("type");
}