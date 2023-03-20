var DatatableUsers = {
    init: function() {
        $("#id_vendor").select2({
            allowClear: true,
            placeholder: "Find Vendor"
        });
        $("#so").select2({
            allowClear: true,
            placeholder: "Find Sales Order"
        });
        $("#po").select2({
            allowClear: true,
            placeholder: "Find Purchase Order"
        });
        $("#status").select2({
            allowClear: true,
            placeholder: "Find Status"
        });
        $("#export").select2({
            allowClear: true,
            placeholder: "Export Type"
        });
        $(".date").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
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
        $("#vendor").select2({
            allowClear: true,
            placeholder: "--Pilih Vendor--",
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "5%", targets: [1] },
                { width: "15%", targets: [2, 3] },
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
                url: main_url + "purchasing/get_purchase",
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
                "data": "id",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "po_number",
                render: function(data, type, row) {
                    return data
                }
            }, {
                "data": "id_quo",
                render: function(data, type, row) {
                    if (data == 0) {
                        data = "Stock";
                    } else {
                        data = data;
                    }
                    return data;
                }
            }, {
                "data": "id_vendor",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                render: function(data, type, row) {
                    if (data == 'approve') {
                        var colors = "success";
                    } else {
                        var colors = "danger";
                    }
                    return '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + data + '</span>'
                }
            }, {
                "data": "price",
                "className": "text-right",
                render: $.fn.dataTable.render.number(',', '.', 0)
            }, {
                "data": "created_at",
                "className": "text-center",
                render: function(data, type, row) {
                    return data;
                }
            }, {
                "data": "payment",
                render: function(data, type, row) {
                    if (data == "Done Paid") {
                        var colors = "primary";
                        var datas = data + " " + row.tgl_payment;
                        var rets = '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + datas + '</span>';
                    } else if (data == "Paid Parsial") {
                        var colors = "primary";
                        var datas = data;
                        var rets = '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + datas + '</span>';
                    } else if(data=="Reject"){
                        var colors = "danger";
                        var datas = data;
                        var rets = '<span class="badge badge-' + colors + ' d-block">' + datas + '</span>';
                    }else {
                        var colors = "danger";
                        var datas = data;
                        var rets = '<span class="badge badge-flat border-' + colors + ' text-' + colors + '-600 d-block">' + datas + '</span>';
                    }
                    return rets
                }
            }, ]
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            window.location.href = main_url + "purchasing/order/" + data.po_number;
        });
        $("#filter").on('click', function(ele) {
            var id_product = $('#id_product').val();
            var vendor     = $('#vendor').val();
            var status     = $('#status').val();
            var st_date    = $('#start_date').val();
            var end_date   = $('#end_date').val();

            if (status == '') { var st = 'kosong' } else { var st = status }
            if (vendor == null) { var vn = 'kosong' } else { var vn = vendor }
            if (id_product == null) { var idp = 'kosong' } else { var idp = id_product }
            if (st_date == '') { var sd = 'kosong' } else { var sd = st_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            console.log(status);
            console.log(vendor);
            console.log(id_product);
            console.log(st_date);
            console.log(end_date);

            var urls = main_url + "purchasing/order/filterData/" + st + "/" + vn + "/" + idp + "/" + sd + "/" + ed ;
            table.ajax.url(urls).load();
        });
        $("#ex_quo").on('click', function(ele) {
            var id_product = $('#id_product').val();
            var vendor     = $('#vendor').val();
            var status     = $('#status').val();
            var st_date    = $('#start_date').val();
            var end_date   = $('#end_date').val();
            var all        = $('#export').val();
            if(all==''){
                alert("Silhakan pilih Export Type");
            }else{
            console.log(status);
            console.log(vendor);
            console.log(id_product);
            console.log(st_date);
            console.log(end_date);
                
            if (status == '') { var st = 'kosong' } else { var st = status }
            if (vendor == null) { var vn = 'kosong' } else { var vn = vendor }
            if (id_product == null) { var idp = 'kosong' } else { var idp = id_product }
            if (st_date == '') { var sd = 'kosong' } else { var sd = st_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            if (all == '') { var all = 'kosong' } else { var all = all }

            window.location.href = main_url + "purchasing/order/export/"  + st + "/" + vn + "/" + idp + "/" + sd + "/" + ed + "/" + all;

            }
        });
        $('#rst_fil').click(function() {
            $('#id_product').val('').trigger("change");
            $('#vendor').val('').trigger("change");
            $('#status').val('').trigger("change");
            $('#start_date').val('');
            $('#end_date').val('');
            var url = main_url + "purchasing/get_purchase";
            table.ajax.url(url).load();
        });
    }
};
jQuery(document).ready(function() {
    DatatableUsers.init();
});