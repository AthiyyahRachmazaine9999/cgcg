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
            columnDefs: [
                { width: "15%", targets: [1] },
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
                url: main_url + "upload/file/get_list",
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
                "data": "created_by",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "created_at",
                render: function(data, type, row) {
                    return data;
                }
            }, ]
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            // console.log(data.id);
            window.location.href = current_url + data.id + "/detail";
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});



function delete_file(ele)
{
    var ajx = ajax_data('upload/file/delete_file', '&id=' + $(ele).data('id'));
    window.location.reload();
}


function edit_file(ele)
{
    window.location.href = main_url + 'upload/file/'+$(ele).data('id')+'/edit_file';
}