var DatatableUsers = {
    init: function() {
        $("#quo_type").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "5%", targets: [1, 3] },
                { width: "5%", targets: [4, 2, 5] },
                {
                    "targets": [0],
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
                url: main_url + "sales/quotation/filter_home",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'what': 'hrd'
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
                    "data": "tujuan",
                    render: function(data, type, row) {
                        if(data==null){
                            datas = '-';
                        }else{
                            datas = data;
                        }
                        return datas;
                    }
                }, {
                    "data": "type",
                    "className": "text-center",
                    render: function(data, type, row) {
                        if (data == "Permission" || data == "Late Permission") {
                            var color = "badge badge-flat border-info text-info-600 d-block";
                            var text = data;
                        } else {
                            var color = "badge badge-flat border-success text-success-600 d-block";
                            var text = data;
                        }

                        return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                    }
                }, {
                    "data": "status",
                    "className": "text-center",
                    render: function(data, type, row) {
                        if (data == "Pending" || data == "Need Approval") {
                            var color = "badge badge-flat border-warning text-warning-600 d-block";
                            var text = data;
                        } else if (data == "Rejected") {
                            var color = "badge badge-flat border-danger text-danger-600 d-block";
                            var text = data;
                        } else {
                            var color = "badge badge-flat border-primary text-primary-600 d-block";
                            var text = data;
                        }

                        if (row.ref == "none") {
                            var now = "";
                        } else {
                            var now = "";
                        }
                        return '<span class="' + color + 'd-block text-center">' + text + '</span>' +
                            '<br><br>' + '<span class="badge border-info text-info d-block">' + now + '</span>';
                    }
                },
                {
                    "data": "created_by",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "created_at",
                    render: function(data, data1, type, row) {
                        return data;
                    }
                },
            ]
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            console.log(data.id);
            window.location.href = main_url + "hrm/request/leave/" + data.id + "/" + "show";
        });
    }
};
jQuery(document).ready(function() {
    DatatableUsers.init();
});


//Settlement
