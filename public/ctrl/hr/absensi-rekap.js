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
                { width: "30%", targets: [2, 3, 4, 5] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [5, "asc"]
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
                url: main_url + "hrm/rekapabsen/get_rekapabsensi",
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
                    "data": "emp_name",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "emp_email",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "emp_phone",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "emp_nip",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "position",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "emp_status",
                    render: function(data, type, row) {
                        if (data == 'Active') {
                            var colors = "primary";
                            var isi = "Active";
                        } else {
                            var colors = "danger";
                            var isi = "In Active";
                        }
                        return '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + isi + '</span>'
                    }
                },
            ]
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            console.log(data.id);
            window.location.href = current_url + '' + data.id;
        });
    }
};
jQuery(document).ready(function() {
    DatatableEmployee.init();
});