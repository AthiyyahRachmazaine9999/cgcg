function new_uploads(ele) {
    var n_equ = $(".uploads_files").length;
    console.log(n_equ);
    var getdata = ajax_data('upload/file/get_upload', "&n_equ=" + n_equ);
    $(".tambah_uploads").before(getdata);
}

function remove_uploads(ele) {
    console.log(ele);
    $(".files_" + ele).remove();
    // var getdata = ajax_data('upload/file/get_upload', "&n_equ=" + n_equ);
    // $(".row_parsial_" + equ).remove();
}


function Add_files(ele)
{
    var getdata = ajax_data('finance/pengajuan_pettycash/add_files', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Additional File")
}



var DatatableEmployee = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [{
                "targets": [0],
                "visible": false,
                "searchable": false
            }, ],
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
                url: main_url + "finance/ajax_list",
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
                "data": "month",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                "className": "text-center",
                render: function(data, type, row) {
                    if (data == "Rejected") {
                        var color = "badge badge-flat border-danger text-danger-600 d-block";
                        var text = data;
                    } else if (data == "Approved") {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    } else if (data == "Approval Completed") {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = data;
                    } else {
                        var color = "badge badge-flat border-warning text-warning-600 d-block";
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
                    if (row.user == "finance" || row.user == 'manage') {
                        if (row.status == "Pending" || row.status == 'Need Approval') {
                            var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt text-primary"></i>Edit</a>';
                            var show = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i>Detail</a>';
                            var del = '<a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash text-danger"></i>Delete</a>';
                        } else {
                            var edit = '';
                            var show = '<a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i>Detail</a>';
                            var del = '';
                        }
                    } else {
                        var edit = '';
                        var show = '<a href="' + main_url + 'finance/pengajuan_pettycash/' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i>Detail</a>';
                        var del = '';
                    }
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    ' + edit + show + del + '</div ></div ></div >'
                }
            }, ],
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});