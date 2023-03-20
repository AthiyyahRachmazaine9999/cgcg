var DatatableEmployee = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".m_datatable").DataTable({
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
                url: main_url + "setting/get_config",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "config_name",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "config_value",
                render: function (data, type, row) {
                    return data;
                }
            
            }, {
                "data": "id",
                "className":"text-center",
                render: function (data, type, row) {
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="'+current_url+''+data+'/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i> Edit</a>\
                    <a href="'+current_url+''+data+'/delete" class="dropdown-item"><i class="fas fa-trash-alt text-danger"></i> Delete</a>\
                    </div ></div ></div >'
                }
            },]
        });
    }
};
jQuery(document).ready(function () {
    DatatableEmployee.init();
});