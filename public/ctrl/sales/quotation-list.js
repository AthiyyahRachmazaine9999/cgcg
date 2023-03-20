var DatatableUsers = {
    init: function () {
        $("#quo_type").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $("#status").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $("#sales").select2({
            allowClear: true,
            placeholder: "Pilih Type"
        });
        $("#id_customer").select2({
            allowClear: true,
            placeholder: "Cari Customer",
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
        $("#id_product").select2({
            allowClear: true,
            placeholder: "Cari Product",
            ajax: {
                url: main_url + 'product/get_product',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: "[" + item.sku + "] " + item.name,
                                id: item.sku,
                               
                            }
                        })
                    };
                },
                cache: true
            }
        }).on("change", function(e) {
            var skus = $("#id_product option:last-child").val();

        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "5%", targets: [1] },
                { width: "20%", targets: [2, 3] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            order: [[0, "desc"]],
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
                url: main_url + "sales/get_quotation",
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
                "data": "quo_no",
                render: function (data, type, row) {
                    if (data == null) {
                        var num = '';
                    } else {
                        var num = data;
                    }
                    return '<span class="badge badge-' + row.quo_color + ' d-block">' + row.quo_type + '</span>\
                        <br>'+ num
                }
            }, {
                "data": "quo_name",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "id_customer",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "id_sales",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_order_at",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "quo_eksstatus",
                render: function (data, type, row) {
                    if (row.status == 'Negosiasi') {
                        var colors = "success";
                    } else {
                        var colors = "indigo";
                    }

                    if (row.quo_ekskondisi == "Batal") {
                        var now =
                            '<span class="badge badge-danger d-block">Batal</span>';
                    }else{
                        if (data == null) {
                            var now =
                                '<span class="badge badge-warning d-block">Pending</span>';
                        } else {
                            var now = data;
                        }
                    }
                    return '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + row.status.toUpperCase() + '</span>\
                    <br>'+ now;
                }
            },{
                "data": "posisi",
                render: function (data, type, row) {
                    return '<span class="badge bg-'+row.posisi_color+' d-block">' + data + '</span>';
                }
            }, {
                "data": "quo_price",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 0)
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
            console.log(data.id);
            window.location.href = main_url + "sales/quotation/" + data.id;
        });
        $("#filter").on('click', function (ele) {
            var quo_type   = $('#quo_type').val();
            var status     = $('#status').val();
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var sales      = $('#sales').val();
            var id_customer = $('#id_customer').val();
            var id_product  = $('#id_product').val();


            if(quo_type==''){var qt = 'kosong'}else{var qt = quo_type}
            if(status==''){var st = 'kosong'}else{var st = status}
            if(start_date==''){var sd = 'kosong'}else{var sd = start_date}
            if(end_date==''){var ed = 'kosong'}else{var ed = end_date}
            if(sales==''){var s = 'kosong'}else{var s = sales}
            if(id_customer==null){var ic = 'kosong'}else{var ic = id_customer}
            if(id_product==null){var ip = 'kosong'}else{var ip = id_product}

            
            console.log(qt);
            console.log(st);
            console.log(s);
            console.log(sd);
            console.log(ed);
            console.log(ic);
            console.log(ip);
            
            var urls      = main_url +"sales/quotation/filterData/"+qt+
            "/"+st+"/"+s+"/"+sd+"/"+ed+"/"+ic+"/"+ip;
            
            table.ajax.url(urls).load();        
        });

        $("#ex_quo").on('click', function(ele) {
            var quo_type = $('#quo_type').val();
            var status = $('#status').val();
            var sales = $('#sales').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var all = $('#All').prop('checked');


            if (quo_type == '') { var qt = 'kosong' } else { var qt = quo_type }
            if (status == '') { var st = 'kosong' } else { var st = status }
            if (start_date == '') { var sd = 'kosong' } else { var sd = start_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            if (sales == '') { var s = 'kosong' } else { var s = sales }
            if (all == false) { var all = 'kosong' } else { var all = all }

            window.location.href = main_url + "sales/quotation/ex_quo/" + qt +
                "/" + st + "/" + s + "/" + sd + "/" + ed + "/" + all;
        });

        // Reset
        $("#reset").on('click', function(ele) {
            $('#quo_type').val('').trigger("change");
            $('#status').val('').trigger("change");
            $('#start_date').val('');
            $('#end_date').val('');
            $('input:checkbox').each(function() { this.checked = false; });
            $('#sales').val('').trigger("change");
            table.ajax.url(main_url + "sales/get_quotation").load();
        });

    }
};
jQuery(document).ready(function () {
    $("body").toggleClass("sidebar-xs").removeClass("sidebar-mobile-main");
    DatatableUsers.init();
});