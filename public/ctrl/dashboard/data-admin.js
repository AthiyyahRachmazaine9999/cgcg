var DatatableUsers = {
    init: function () {
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
                { width: "5%", targets: [1,3] },
                { width: "15%", targets: [4,5] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[ 5, "desc" ]],
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
                    'what':'admin'
                }
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                return nRow;
            },
            columns: [{
                "data": "id",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_no",
                render: function (data, type, row) {
                   if(data==null){
                       data= "RFQ";
                   }else{
                       data=data;
                   }
                    return data;
                }
            }, {
                "data": "quo_name",
                render: function (data, type, row) {
                    return '<p style="text-transform: uppercase"><b>'+data+'</b>\
                  </p>\
                  </br>\
                  <span>'+row.id_customer+'</span>';
                }
         },{
                "data": "id_sales",
                render: function (data, type, row) {
                    return data;
                }
         },{
                "data": "created_at",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_eksstatus",
                render: function (data,data1, type, row) {
                if(data!=null){
                return '<span>'+data+'</span>'
                    } else{
                    return ('-');
                    }
                }
            },]
        });
        $('.m_datatable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            console.log(data.id);
            window.location.href = main_url + "sales/quotation/"+data.id;
        });
    }
};
jQuery(document).ready(function () {
    DatatableUsers.init();
});