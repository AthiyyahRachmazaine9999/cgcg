var DatatableUsers = {
    init: function () {
        $("#export").select2({
            allowClear: true,
            placeholder: "Pilih Tipe"
        });
        $("#vendor").select2({
            allowClear: true,
            placeholder: "Pilih Vendor",
            width: "100%",
            ajax: {
                url: main_url + 'sales/find_vendor',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.vendor_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $(".date").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
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
                url: main_url + "sales/get_vendor",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },

            columns: [{
                "data": "vendor_name",
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
                "data": "jumlah_po",
                render: function (data, type, row) {
                    return '<a data-toggle="modal" data-target="#m_modal" onClick="List_PO(this)" data-id_vendor="'+row.id+'" class="text-primary">'+data+'</a>';
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

        $("#ex_quo").on('click', function(ele) {
            var vendor     = $('#vendor').val();
            var st_date    = $('#start_date').val();
            var end_date   = $('#end_date').val();

            console.log(vendor);
            console.log(st_date);
            console.log(end_date);
                
            if (vendor == null) { var vn = 'kosong' } else { var vn = vendor }
            if (st_date == '') { var sd = 'kosong' } else { var sd = st_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            
            window.location.href = main_url + "sales/vendor/export/"  + vn + "/" + sd + "/" + ed;
        });
    }
};

jQuery(document).ready(function () {
    DatatableUsers.init();
});



function List_PO(ele) {
    var prospectRes = ajax_data("sales/vendor/getListPo", "&id_vendor="+$(ele).data("id_vendor"));

    $("#modalbody").html(prospectRes),
        $("#modaltitle").html("List PO");

    var DatatableVendorPO = {
    init: function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".datatable_vendor").DataTable({
            autoWidth: false,
            columnDefs: [{ 
                orderable: false,
                width: 100,
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
                url: main_url + "sales/vendor/ajax_listPO",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token,
                    'id_vendor' : $(".id_vendor").val(),
                }
            },

            columns: [{
                "data": "nomer_po",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "tipe_payment",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "total",
                "className": "text-right",
                render: function (data, type, row) {
                    return data;
                }
            },{
                "data": "created_at",
                render: function (data, type, row) {
                    return data;
                }
            },]
        });
    }
};
jQuery(document).ready(function () {
    DatatableVendorPO.init();
});

}