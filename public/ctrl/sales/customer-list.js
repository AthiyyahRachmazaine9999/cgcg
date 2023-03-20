var DatatableUsers = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                width: 100,
                targets: [ 4 ]
            }],
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
                url: main_url + "sales/get_customer",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "company",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "email",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "address",
                render: function (data, type, row) {
                    return data;
                }
         },{
                "data": "created_at",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "id",
                "className":"text-center",
                render: function (data, type, row) {
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="'+current_url+''+data+'" class="dropdown-item"><i class="fas fa-book-open"></i> Show</a>\
                    <a href="'+current_url+''+data+'/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>\
                    </div ></div ></div >'
                }
            },]
        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});