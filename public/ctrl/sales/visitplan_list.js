var DatatableUsers = {
    init: function () {
        $("#status").select2({
            allowClear: true,
            placeholder: "Pilih Status"
        });
        $("#sales").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $("#customer").select2({
                allowClear: true,
                placeholder: "Plih Customer",
                ajax: {
                    url: main_url + 'sales/find_customer',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.company,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        $('.time').timepicker({
            'timeFormat': 'H:i A',
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "20%", targets: [1,4] },
                { width: "50%", targets: [3,2] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[6, "desc"]],
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
                url: main_url + "sales/getvisitplan",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },
            columns: [{
                "data": "id",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "tujuan",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "date",
                render: function (data, type, row) {
                    return '<strong>' + data + '</strong>' + '<br><br><em>' + row.meeting_point + '<em>';
                }
            }, {
                "data": "customer",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                render: function (data, type, row) {
                    if (data == "Open Plan") {
                        var color = "badge badge-flat border-blue text-blue-600 d-block";
                        var text = data;
                    } else {
                        var color = "badge badge-flat border-success text-success-600 d-block";
                        var text = data;
                    }
                    return '<span class="' + color + 'd-block text-center">' + text + '</span>';
                }
            }, {
                "data": "created_at",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "created_by",
                render: function (data, type, row) {
                    return data;
                }
            },],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td', nRow).css('cursor', 'pointer');
                if(aData.quo_ekskondisi == "Batal"){
                    $('td', nRow).css('text-decoration', 'line-through');
                    $('td', nRow).css('background-color', '#c9a7a7');
                    $('td', nRow).css('color', '#ffffff');
                }
                return nRow;
            },
        });
        $('.m_datatable tbody').on('click', 'tr', function () {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            var a= window.location.href = main_url + "sales/quotation/create/"+ data.id;
            // return fill_create(data.id);
            // var getdata = ajax_data("sales/quotation/datavisit", "&id_visit=" + data.id);
        });
        $("#filter").on('click', function (ele) {
            var status     = $('#status').val();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var sales      = $('#sales').val();
            var id_customer = $('#id_customer').val();


            if(status==''){var st = 'kosong'}else{var st = status}
            if(start_date==''){var sd = 'kosong'}else{var sd = start_date}
            if(end_date==''){var ed = 'kosong'}else{var ed = end_date}
            if(sales==''){var s = 'kosong'}else{var s = sales}
            if(id_customer==null){var ic = 'kosong'}else{var ic = id_customer}

            
            console.log(st);
            console.log(s);
            console.log(sd);
            console.log(ed);
            console.log(ic);
            
            var urls = main_url +"sales/filter_visit/"+st+
            "/"+sd+"/"+ed+"/"+s+"/"+ic;
            
            table.ajax.url(urls).load();        
        });

        $("#ex_visit").on('click', function(ele) {
            var status     = $('#status').val();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var sales      = $('#sales').val();
            var id_customer = $('#id_customer').val();

            if(status==''){var st = 'kosong'}else{var st = status}
            if(start_date==''){var sd = 'kosong'}else{var sd = start_date}
            if(end_date==''){var ed = 'kosong'}else{var ed = end_date}
            if(sales==''){var s = 'kosong'}else{var s = sales}
            if(id_customer==null){var ic = 'kosong'}else{var ic = id_customer}

            window.location.href = main_url +"sales/ex_visit/"+st+
            "/"+sd+"/"+ed+"/"+s+"/"+ic;
        });
        // Reset
        $("#reset").on('click', function(ele) {
            $('#status').val('').trigger("change");
            $('#start_date').val('');
            $('#end_date').val('');
            $('input:checkbox').each(function() { this.checked = false; });
            $('#sales').val('').trigger("change");
            $('#id_customer').val('').trigger("change");
            table.ajax.url(main_url + "sales/getvisitplan").load();
        });

    }
};
jQuery(document).ready(function () {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DatatableUsers.init();
});

