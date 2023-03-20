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
                url: main_url + "finance/pettycash/pettycash_list",
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
                "data": "group",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "year",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "created_at",
                render: function(data, type, row) {
                    return data;
                }
            }, ],
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            console.log(data.group);
            window.location.href = current_url + '' + data.group + '/show';
        });

    }
};
jQuery(document).ready(function() {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DatatableEmployee.init();
});


//////////////////////////////////////

var DataTableDetail = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".tbl_dtl").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "20%", targets: [5, 2, 4] },
                { width: "15%", targets: [0, 3] },
                { width: "30%", targets: [1] },
                {
                    "visible": false,
                    "className": 'bolded',
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
                url: main_url + "finance/pettycash/ajax_detail",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'what': $('#month').val(),
                }
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },

            columns: [{
                "data": "date",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "description",
                render: function(data, type, row) {
                    var codes = '<span class="font-italic text-indigo font-weight-bold">' + row.code + '</span>';

                    if (row.receipt != null) {
                        var file = '<br><br><br><a href="' + main_url + row.receipt + '" target="_blank" class="btn btn-outline-primary">SHOW</a>';
                    } else {
                        var file = "";
                    }
                    return codes + '<br><br><br><span class="text-right">' + data + '</span>' + file;
                }
            }, {
                "data": "pic",
                render: function(data, type, row) {
                    if (row.other_pic != null) {
                        var oth = '<br><br><span class="font-italic"> Note: ' + row.other_pic + '</span>'
                    } else {
                        var oth = "";
                    }

                    return data + oth;
                }
            }, {
                "data": "nominal",
                "className": "text-right",
                render: function(data, type, row) {
                    var datas = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    if (row.method != null) {
                        var text = row.method == "debit" ? "Debit" : "Credit";
                        var color = row.method == "debit" ? "border-primary text-primary" : "border-success text-success";
                        var method = '<br><br><br><span class="badge badge-flat ' + color + '-600 d-block">' + text + '</span>';
                    } else {
                        var method = '';
                    }
                    return datas + method;
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
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="' + main_url + 'finance/pettycash/' + data + '/edit_detail" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>\
                    <a href="' + main_url + 'finance/pettycash/' + data + '/delete_detail" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete</a>\
                    </div ></div ></div >'
                }
            }, ]
        });
    }
};
jQuery(document).ready(function() {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DataTableDetail.init();
});



var DataTableDokumen = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".tbl_dokumen").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "15%", targets: [0, 1, 2, 3, 4] },
                {
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
                url: main_url + "finance/pettycash/ajax_dokumen",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'what': $("#id").val(),
                }
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },

            columns: [{
                "data": "created_by",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "nama_dok",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "file",
                render: function(data, type, row) {
                    if (data != null) {
                        var file = '<a href="' + main_url + data + '" target="_blank" class="btn btn-outline-primary">SHOW</a>';
                    } else {
                        var file = '<button target="_blank" class="btn btn-outline-primary" disabled>SHOW</button>';
                    }
                    return file;

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
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="' + main_url + 'finance/pettycash/dokumen/' + data + '/edit_dokumen" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>\
                    <a href="' + main_url + 'finance/pettycash/dokumen/' + data + '/delete_dokumen" class="dropdown-item"><i class="fas fa-trash text-danger"></i> Delete</a>\
                    </div ></div ></div>'
                }
            }, ]
        });
    }
};
jQuery(document).ready(function() {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DataTableDokumen.init();
});