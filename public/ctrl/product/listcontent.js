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
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [7, "desc"]
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
                url: main_url + "product/content/get_listcontent",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "pro_manufacture",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_categories",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_name",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_berlaku_sampai",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_price",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 2)
            }, {
                "data": "pro_active",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_status",
                "className": "text-center",
                render: function(data, type, row) {
                    if (data == "Reject") {
                        var color = "badge badge-flat border-danger text-danger-600 d-block";
                        var text = "REJECT";
                    } else if (data == "Pending") {
                        var color = "badge badge-flat border-warning text-warning-600 d-block";
                        var text = "PENDING";
                    } else if (data == "Approved") {
                        var color = "badge badge-flat border-primary text-primary-600 d-block";
                        var text = "APPROVED";
                    } else {
                        var color = "badge badge-flat border-success text-success-600 d-block";
                        var text = "WAITING";
                    }
                    return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                }
            }, {
                "data": "pro_id",
                "className": "text-center",
                render: function(data, type, row) {
                    if (row.pro_status == "Pending") {
                        var apply = '<a href="' + current_url + '' + data + '/apply" class="dropdown-item" name="Apply" id="Apply"><i class="fas fa-eye"></i>Apply For Approval</a>';
                        var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                    } else if (row.pro_status == "Reject") {
                        var edit = '<a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>';
                        var apply = "";
                    } else {
                        var apply = "";
                        var edit = "";
                    }
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">' +
                        edit + '<a href="' + current_url + '' + data + '/show" class="dropdown-item" name="Apply" id="Apply"><i class="fas fa-inbox"></i>Details</a>\
                    </div ></div ></div >'
                }
            }, ]
        });
    }
};

jQuery(document).ready(function() {
    DatatableUsers.init();
});

function export_formats() {
    window.location.href = main_url + 'product/content/listcontent/get_docformat';
}

function upload_images() {
    var getdata = ajax_data('product/content/listcontent/import_zip');
}