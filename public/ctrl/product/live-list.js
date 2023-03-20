var DatatableUsers = {
    init: function () {
            $("#status").select2({
                allowClear: true,
                placeholder: "Find Status"
            }), $("#status").on("select2:change", function() {
                e.element($(this))
            }),
             $("#brand").select2({
                allowClear: true,
                placeholder: "Find Brand"
            }), $("#brand").on("select2:change", function() {
                e.element($(this))
            }),


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
                url: main_url + "product/get_live",
                type: 'post',
                dataType: 'json',
                data: {
                    '_token': token
                }
            },
            columns: [{
                "data": "model",
                render: function (data, type, row) {
                    return data;
                }

            }, {
                "data": "sku",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "name",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "status",
                render: function (data, type, row) {
                    if(data==0){
                        data="In Active";
                    }else{
                        data="Active";
                    }
                    return data;
                }

            }, {
                "data": "price",
                "className":"text-right",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "date_added",
                render: function (data, type, row) {
                    return data;
                }
            }, {
                "data": "product_id",
                "className": "text-center",
                render: function (data, type, row) {
                    return '<div class="list-icons"><div class="dropdown"><a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu9"></i></a>\
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-158px, 19px, 0px); top: 0px; left: 0px; will-change: transform;">\
                    <a href="' + current_url + '' + data + '/edit" class="dropdown-item"><i class="fas fa-pencil-alt"></i>Edit</a>\
                    <a href="' + current_url + '' + data + '/show" class="dropdown-item"><i class="fas fa-eye"></i>Details</a>\
                    </div ></div ></div >'
                }
            }, ]
        });
        $("#filter").on('click', function(ele) {
            var status = $('#status').val();
            var brand = $('#brand').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();

            if (status == '') { var st = 'kosong' } else { var st = status }
            if (brand == '') { var br = 'kosong' } else { var br = brand }
            if (start_date == '') { var sd = 'kosong' } else { var sd = start_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }

            var urls = "live/filterData/" + st + "/" + br + "/" + sd + "/" + ed;
            table.ajax.url(urls).load();
        });
        $('#reset').click(function() {
            $('#status').val('').trigger("change");
            $('#brand').val('').trigger("change");
            $('#start_date').val('');
            $('#end_date').val('');
            var url = main_url + "product/get_live";
            table.ajax.url(url).load();
        });
        $("#export_live").on('click', function(ele) {
            var status = $('#status').val();
            var brand = $('#brand').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var all = $('#All').prop('checked');

            if (brand == '') { var br = 'kosong' } else { var br = brand }
            if (status == '') { var st = 'kosong' } else { var st = status }
            if (start_date == '') { var sd = 'kosong' } else { var sd = start_date }
            if (end_date == '') { var ed = 'kosong' } else { var ed = end_date }
            if (all == false) { var all = 'kosong' } else { var all = all }

            window.location.href = main_url + "product/live/ex_pro/" + st + "/" + br + "/" + sd + "/" + ed + "/" + all;
        });


    }
};

jQuery(document).ready(function () {
    DatatableUsers.init();
});

