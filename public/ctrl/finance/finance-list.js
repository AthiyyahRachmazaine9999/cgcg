var DatatableVoucher = {
    init: function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".quos").select2({
            allowClear: true,
            placeholder: "*"
        }), $(".quos").on("select2:change", function() {
            e.element($(this))
        });
        $("#filter_lunas").select2({
            allowClear: true,
            placeholder: "Pilih Keterangan"
        }), $("#filter_lunas").on("select2:change", function() {
            e.element($(this))
        });
        $(".st_date").datepicker({
            todayHighlight: !0,
            autoclose: !0,
            format: "yyyy-mm-dd"
        });
        var table = $(".m_datatable").DataTable({
            autoWidth: false,
            columnDefs: [
                { width: "20%", targets: [3, 1] },
                { width: "20%", targets: [0, 2, 6] },
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            className: 'select-checkbox',
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
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
                url: main_url + "finance/get_invoicing",
                type: 'post',
                dataType: 'json',

                data: {
                    '_token': token
                }
            },
            columns: [{
                    "data": "id",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "id_quo",
                    "className": "text-center",
                    render: function(data, type, row) {
                        return '<span class="badge badge-pill badge-info">' + data + '</span></b>\
                  </p>\
                  </br>\
                  <p style="text-transform: uppercase"><b>' + row.quo_no + '';
                    }
                }, {
                    "data": "no_invoice",
                    render: function(data, type, row) {
                        return data;
                    }
                }, {
                    "data": "price",
                    "className": "text-right",
                    render: $.fn.dataTable.render.number(',', '.', 0)
                },{
                    "data": "payments",
                    "className": "text-right",
                    render: function(data, type, row) {
                        return data;
                    }
                },{
                    "data": "amount",
                    "className": "text-right",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "tgl_invoice",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "created_by",
                    render: function(data, type, row) {
                        return data;
                    }
                },
                {
                    "data": "note",
                    "className": "text-center",
                    render: function(data, type, row) {
                        value = data == null ? "-" : data;
                        if (row.notes == 'Unpaid') {
                            var colors = '<span class="badge badge-flat border-danger text-danger-600 d-block">' + row.ket_lunas + '</span>';
                        } else if (row.lunas == "Finish" && row.notes != 'Unpaid') {
                            var colors = '<span class="badge badge-primary border-primary text-white-600 d-block">' + row.ket_lunas + '</span>';
                        } else {
                            var colors = '<span class="badge badge-flat border-success text-success-600 d-block">' + row.ket_lunas + '</span>';
                        }
                        return '<p style="text-transform: uppercase"><b>' + value + '</b>\
                        </p>\
                        </br>' + colors + '';
                    }
                },
            ]
        });
        $('.m_datatable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            $('#myModal').modal('show');
            console.log(data.id);
            window.location.href = main_url + "finance/invoice/edit_invoice/" + data.id;
        });
        $("#inv_filter").on('click', function(ele) {
            var id_quo = $('#ids_quo').val();
            var st_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var ket_lunas = $('#filter_lunas').val();
            if (id_quo == '') { var id = 'kosong' } else { var id = id_quo }
            if (st_date == '') { var sd = 'kosong' } else { var sd = st_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            if (ket_lunas == '') { var kl = 'kosong' } else { var kl = ket_lunas }

            var urls = main_url + "finance/invoice/filter_data/" + id + "/" +
                sd + "/" + ed + "/" + kl;
            table.ajax.url(urls).load();
        });

        $('#inv_res_filter').click(function() {
            $('#ids_quo').val('').trigger("change");
            $('#start_date').val('');
            $('#end_date').val('');
            $('#filter_lunas').val('').trigger("change");
            var url = main_url + "finance/get_invoicing";
            table.ajax.url(url).load();

        });
    }
};
jQuery(document).ready(function() {
    $('body').toggleClass('sidebar-xs').removeClass('sidebar-mobile-main');
    DatatableVoucher.init();
});