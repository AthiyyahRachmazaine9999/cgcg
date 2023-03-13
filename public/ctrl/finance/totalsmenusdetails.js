var DatatableCash = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "30%", targets: [0] },
                { width: "15%", targets: [2, 3, 4] },
                {
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [
                [3, "desc"],
            ],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            ordering: true,
            processing: true,
            dataSrc: "",
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
                url: main_url + "finance/settlement/{id}/setmenutotaldetails",

                // totalsmenustotalsmenus


                // totalsmenustotalsmenus
                
                type: 'get',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },


            


            columns: [{
                    "data": "emp",
                    
                },
                {
                    "data": "costsetmenu",
                    render: $.fn.dataTable.render.number(',', '.', 0)
                },
                
            ]

            
            

        });
    }
};
jQuery(document).ready(function() {
    
    DatatableCash.init();
});





function set_Complete(ele) {
    var getdata = ajax_data('finance/settlement/complete_sets', "&id=" + $(ele).data('id'));
    $("#modalbody").html(getdata),
        $("#modaltitle").html("Detail")
}
