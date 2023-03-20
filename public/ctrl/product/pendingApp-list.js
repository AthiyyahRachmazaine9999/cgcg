var DatatableUsers = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
	    var table    = $(".m_datatable").DataTable({
            autoWidth : false,
            columnDefs: [
                { width: "20%", targets: [1] },
                { width: "5%", targets: [6,7] },
                {
                    "visible"   : false,
                    "searchable": false
                },
            ],
            order     : [[7, "desc"]],
            dom       : '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
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
                url: main_url + "product/get_waiting",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },

        columns: [{
               
                "data": "pro_manufacture",
                render: function (data, type, row) {
                    return data;
                }

            }, {
                "data": "pro_name",
                render: function (data, type, row) {
                    return data;
                }
            
            }, {
                "data": "pro_type",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_price",
                render     : $.fn.dataTable.render.number(',', '.', 2)
            }, {
                "data": "pro_active",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "pro_berlaku_sampai",
                render: function (data, type, row) {
                    return data;
                }   
            }, {
                "data": "created_by",
                "className":"text-center",
                render: function (data, type, row) {
                return data;
                }
            }, {
                "data": "pro_id",
                "className":"text-center",
                render: function (data, type, row) {
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="'+current_url+''+data+'/show" class="dropdown-item"><i class="fas fa-eye"></i> Details</a>\
                    </div ></div ></div>'
                }
            },]

        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});




