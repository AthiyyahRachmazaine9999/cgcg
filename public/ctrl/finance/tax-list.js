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
                'targets':[0],
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
                url: main_url + "finance/listTax",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'type'  : "ppn",
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
                "data": "year",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "no_faktur",
                render: function(data, type, row) {
                    return data;
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
                     <a href="' + current_url + 'ppn/' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt text-primary"></i>Edit</a>\
                     <a href="' + current_url + 'ppn/' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i>Detail</a>\
                     <a href="' + current_url + '' + data + '/delete" class="dropdown-item"><i class="fas fa-trash text-danger"></i>Delete</a>' + '</div ></div ></div >'
                }
            }, ],
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});

